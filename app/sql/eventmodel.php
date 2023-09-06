<?php

class EventModel
{
    // Database
    function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
        }
    }

    //Changes storage
    public function changes($who, $what, $for_who)
    {
        $sql = "INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, NOW())";
        $query = $this->db->prepare($sql);
        $query->execute(array(':who' => $who, ':what' => $what, ':for_who' => $for_who));
    }

    // Get all current/upcoming events
    public function getCEvents()
    {
        $sql = 'SELECT * FROM event WHERE (event_start<=NOW() AND event_end>=NOW()) OR event_start>NOW() ORDER BY event_start ASC';
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    // Get all past events
    public function getPEvents()
    {
        $sql = 'SELECT * FROM event WHERE event_end<NOW() ORDER BY event_end ASC';
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    //Get info from event ID...
    public function getEvent($id)
    {
        $sql = 'SELECT * FROM event WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        return $query->fetch();
    }

    //Get rooms for event ID...
    public function getRooms($id)
    {
        $sql = 'SELECT room.id AS id, etr.id AS etrid, quantity, type, persons, price FROM event_to_room AS etr INNER JOIN room ON etr.room_id=room.id WHERE event_id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        return $query->fetchAll();
    }

    //Get number of booked rooms for the given room ID
    public function getBooked($id)
    {
        $sql = 'SELECT count(id) AS counter FROM registration WHERE room_id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        return $query->fetch();
    }

    //Get registered accounts for event ID...
    public function getRegistered($id)
    {
        $sql = 'SELECT registration.id as id, ticket, registration.created as created, confirmed, fursuiter, artist, username, type, price, room_confirmed, language, dob, notes, account.id AS accID, permanent_waiver AS waiver FROM registration INNER JOIN account ON registration.acc_id=account.id LEFT JOIN room ON registration.room_id=room.id WHERE event_id=:id ORDER BY registration.created ASC';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        return $query->fetchAll();
    }

    //Get fursuits that have in_use=1 and on this event ID...
    public function getFursuits($id)
    {
        $sql = 'SELECT username, name, animal, img FROM fursuit INNER JOIN account ON fursuit.acc_id=account.id INNER JOIN registration ON registration.acc_id=account.id WHERE event_id=:id AND in_use=1 ORDER BY name';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        return $query->fetchAll();
    }

    // Convert MySQL datetime to HTML datetime
    public function convert($date)
    {
        return date_format(new DateTime($date), "Y-m-d\TH:i");
    }

    //how=true or 1: just date; how=false or 0: just hours; how=2: both
    public function convertViewable($date, $how)
    {
        if (is_bool($how)) {
            return ($how) ? date_format(new DateTime($date), "d.m.Y") : date_format(new DateTime($date), "H:i");
        } else {
            if ($how == 0) {
                return date_format(new DateTime($date), "H:i");
            } elseif ($how == 1) {
                return date_format(new DateTime($date), "d.m.Y");
            } else {
                return date_format(new DateTime($date), "d.m.Y H:i");
            }
        }
    }
    // Add new event
    /* Fields in following order:
    ** name
    ** (event) start
    ** (event) end
    ** location
    ** description
    ** reg_start
    ** pre_reg
    ** reg_end
    ** age: 0<=age<=99
    ** restricted_age: 0<=age<=99
    ** restricted_text (for age restrictions, if applicable)
    ** ticket: free, regular, sponsor, super
    **** regular_price, sponsor_price, super_price (if checked above)
    **** type#, persons#, price#, quantity# (0 or more times)
    */
    public function addEvent($fields, $image)
    {
        $sql = 'SELECT * FROM account WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $_SESSION['account']));
        $account = $query->fetch();
        //account doesn't have privileges
        if ($account->status < ADMIN) {
            $this->changes($_SESSION['account'], 'attempted to create an event', $_SESSION['account']);
            return L::alerts_d_cantDoThat;
        }

        //EVENT
        $name = strip_tags($fields['name']);
        $start = strip_tags($fields['start']);
        $end = strip_tags($fields['end']);
        $location = strip_tags($fields['location']);
        $description = $fields['description'];
        $descriptionEn = $fields['descriptionEn'];
        $reg_start = strip_tags($fields['reg_start']);
        $pre_reg = strip_tags($fields['pre_reg']);
        if ($pre_reg == '') {
            $pre_reg = $reg_start;
        }
        $reg_end = strip_tags($fields['reg_end']);
        if ($reg_end == '') {
            $reg_end = $start;
        }
        $viewable = strip_tags($fields['viewable']);
        if ($viewable == '') {
            $viewable = $reg_start;
        }
        $autoconfirm = (array_key_exists('autoconfirm', $fields)) ? strip_tags($fields['autoconfirm']) : 0;
        $age = strip_tags($fields['age']);
        $restricted_age = strip_tags($fields['restricted_age']);
        $restricted_text = $fields['restricted_text'];
        $regular_price = 0;
        $regular_text = '';
        $sponsor_price = -1;
        $sponsor_text = '';
        $super_price = -1;
        $super_text = '';
        //if price!=free, then update all prices
        switch (strip_tags($fields['ticket'])) {
            case 'super':
                $super_price = strip_tags($fields['super_price']);
                $super_text = $fields['super_text'];
            case 'sponsor':
                $sponsor_price = strip_tags($fields['sponsor_price']);
                $sponsor_text = $fields['sponsor_text'];
            case 'regular':
                $regular_price = strip_tags($fields['regular_price']);
                $regular_text = $fields['regular_text'];
            default:
                break;
        }
        //photo
        $file_name = null;
        $err = null;
        if ($image['size'] != 0) {
            $target_dir = 'public/events/';
            $file_name = '';
            while (true) {
                $file_name = substr(bin2hex(random_bytes(32)), 0, 30);
                if (!file_exists($target_dir . $file_name . '.jpg')) {
                    break;
                }
            }
            $img_param = getimagesize($image['tmp_name']);
            if (!$img_param) {
                $err = L::alerts_d_onlyPic;
                $file_name = null;
                goto inserting;
            }
            list($width, $height) = $img_param;
            if ($width < 300 || $height < 158 || round($width / $height, 2) != 1.90) {
                $err = L::alerts_d_not170;
                $file_name = null;
                goto inserting;
            }
            $target_file = $target_dir . $file_name . '.jpg';
            if (!imagejpeg(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)) {
                $err = L::alerts_d_errorupload;
                $file_name = null;
                goto inserting;
            }
        }
        inserting:
        //create event, get event ID for accomodation creation
        $sql = "INSERT INTO event(name, event_start, event_end, reg_start, pre_reg_start, reg_end, location, description, description_en, age, restricted_age, restricted_text, regular_price, regular_text, sponsor_price, sponsor_text, super_price, super_text, autoconfirm, img, viewable) VALUES (:name, :event_start, :event_end, :reg_start, :pre_reg_start, :reg_end, :location, :description, :description_en, :age, :restricted_age, :restricted_text, :regular_price, :regular_text, :sponsor_price, :sponsor_text, :super_price, :super_text, :autoconfirm, :img, :viewable)";
        $query = $this->db->prepare($sql);
        $query->execute(array(':name' => $name, ':event_start' => $start, ':event_end' => $end, ':reg_start' => $reg_start, ':pre_reg_start' => $pre_reg, ':reg_end' => $reg_end, ':location' => $location, ':description' => $description, ':description_en' => $descriptionEn, 'age' => $age, 'restricted_age' => $restricted_age, 'restricted_text' => $restricted_text,
            'regular_price' => $regular_price, ':regular_text' => $regular_text, ':sponsor_text' => $sponsor_text, ':super_text' => $super_text, 'sponsor_price' => $sponsor_price, 'super_price' => $super_price, ':autoconfirm' => $autoconfirm, ':img' => $file_name, ':viewable' => $viewable));
        $event_ID = $this->db->lastInsertId();

        //ACCOMODATION
        $keys = preg_grep('/(type\d+x)+/m', array_keys($fields));
        if (!empty($keys)) {
            foreach ($keys as $key) {
                //type#*, persons#*, price#*, quantity#*
                $id = substr($key, 4);
                $type = strip_tags($fields["type$id"]);
                $persons = strip_tags($fields["persons$id"]);
                $price = strip_tags($fields["price$id"]);
                $quantity = strip_tags($fields["quantity$id"]);

                //check if item (room) with these parameters already exists to prevent duplicate entries
                $sql_check = 'SELECT id FROM room WHERE type=:type AND persons=:persons AND price=:price';
                $query_check = $this->db->prepare($sql_check);
                $query_check->execute(array(':type' => $type, ':persons' => $persons, ':price' => $price));
                //NEW ROOM
                $room_ID = null;
                if ($query_check->rowCount() == 0) {
                    $sql = "INSERT INTO room(type, persons, price) VALUES (:type, :persons, :price)";
                    $query = $this->db->prepare($sql);
                    $query->execute(array(':type' => $type, ':persons' => $persons, ':price' => $price));
                    $room_ID = $this->db->lastInsertId();
                } //EXISTING ROOM
                else {
                    $room_ID = $query_check->fetch()->id;
                }

                //EVENT_TO_ROOM
                $sql = "INSERT INTO event_to_room(quantity, event_id, room_id) VALUES (:quantity, :event_id, :room_id)";
                $query = $this->db->prepare($sql);
                $query->execute(array(':quantity' => $quantity, ':event_id' => $event_ID, ':room_id' => $room_ID));
            }
        }
        $this->changes($_SESSION['account'], "created an event $event_ID ($name)", $_SESSION['account']);
        if ($err == null) {
            return L::alerts_s_evtCreated;
        } else {
            $err = substr($err, 1);
            return L::alerts_s_evtCreated . '<br>' . $err;
        }
    }

    /*
     *
     * Edit an event
     *
     */
    public function editEvent($id, $fields, $image)
    {
        $sql = 'SELECT * FROM account WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $_SESSION['account']));
        $account = $query->fetch();
        if ($account->status < ADMIN) {
            $this->changes($_SESSION['account'], "attempted to edit event ID {$fields['name']}", $_SESSION['account']);
            return L::alerts_d_cantDoThat;
        }
        //EVENT
        $id = strip_tags($id);
        $name = strip_tags($fields['name']);
        $start = strip_tags($fields['start']);
        $end = strip_tags($fields['end']);
        $location = strip_tags($fields['location']);
        $description = $fields['description'];
        $descriptionEn = $fields['descriptionEn'];
        $reg_start = strip_tags($fields['reg_start']);
        $pre_reg = strip_tags($fields['pre_reg']);
        if ($pre_reg == '') {
            $pre_reg = $reg_start;
        }
        $reg_end = strip_tags($fields['reg_end']);
        if ($reg_end == '') {
            $reg_end = $start;
        }
        $viewable = strip_tags($fields['viewable']);
        if ($viewable == '') {
            $viewable = $reg_start;
        }
        $autoconfirm = (array_key_exists('autoconfirm', $fields)) ? strip_tags($fields['autoconfirm']) : 0;
        $age = strip_tags($fields['age']);
        $restricted_age = strip_tags($fields['restricted_age']);
        $restricted_text = $fields['restricted_text'];
        $regular_price = 0;
        $regular_text = '';
        $sponsor_price = -1;
        $sponsor_text = '';
        $super_price = -1;
        $super_text = '';
        //if price!=free, then update all prices
        switch (strip_tags($fields['ticket'])) {
            case 'super':
                $super_price = strip_tags($fields['super_price']);
                $super_text = $fields['super_text'];
            case 'sponsor':
                $sponsor_price = strip_tags($fields['sponsor_price']);
                $sponsor_text = $fields['sponsor_text'];
            case 'regular':
                $regular_price = strip_tags($fields['regular_price']);
                $regular_text = $fields['regular_text'];
            default:
                break;
        }
        //create event, get event ID for accomodation creation
        $sql = "UPDATE event SET name=:name, event_start=:event_start, event_end=:event_end, reg_start=:reg_start, pre_reg_start=:pre_reg_start, reg_end=:reg_end, location=:location, description=:description, description_en=:description_en, age=:age, restricted_age=:restricted_age, restricted_text=:restricted_text, regular_price=:regular_price, regular_text=:regular_text, sponsor_price=:sponsor_price, sponsor_text=:sponsor_text, super_price=:super_price, super_text=:super_text, autoconfirm=:autoconfirm, viewable=:viewable WHERE id=:id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':name' => $name, ':event_start' => $start, ':event_end' => $end, ':reg_start' => $reg_start, ':pre_reg_start' => $pre_reg, ':reg_end' => $reg_end, ':location' => $location, ':description' => $description, ':description_en' => $descriptionEn, 'age' => $age, 'restricted_age' => $restricted_age, 'restricted_text' => $restricted_text, 'regular_price' => $regular_price,
            ':regular_text' => $regular_text, ':sponsor_text' => $sponsor_text, ':super_text' => $super_text, 'sponsor_price' => $sponsor_price, 'super_price' => $super_price, ':autoconfirm' => $autoconfirm, ':id' => $id, ':viewable' => $viewable));

        //IMAGE
        $err = null;
        $file_name = '';
        $target_dir = 'public/events/';
        if ($image['size'] != 0) {
            while (true) {
                $file_name = substr(bin2hex(random_bytes(32)), 0, 30);
                if (!file_exists($target_dir . $file_name . '.jpg')) {
                    break;
                }
            }
        }
        if ($file_name != '') {
            $img_param = getimagesize($image['tmp_name']);
            if (!$img_param) {
                $err = L::alerts_d_onlyPic;
                goto skipping;
            }
            list($width, $height) = $img_param;
            if ($width < 300 || $height < 158 || round($width / $height, 2) != 1.90) {
                $err = L::alerts_d_not170;
                goto skipping;
            }
            $target_file = $target_dir . $file_name . '.jpg';
            if (!imagejpeg(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)) {
                $err = L::alerts_d_errorupload;
                goto skipping;
            }
            $sql = 'SELECT img FROM event WHERE id=:id';
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
            $event = $query->fetch();
            if (file_exists($target_dir . $event->img . '.jpg')) {
                unlink($target_dir . $event->img . '.jpg');
            }
            $sql = 'UPDATE event SET img=:img WHERE id=:id';
            $query = $this->db->prepare($sql);
            $query->execute(array(':img' => $file_name, ':id' => $id));
        }
        skipping:
        //ACCOMODATION
        //get all rooms for this event, compare for deleted rooms, check for new ones, update all others
        $evt_id = $id;
        $existing = $this->getRooms($id);
        $keys = preg_grep('/(type\d+x*)+/m', array_keys($fields));
        if (!empty($keys)) {
            foreach ($keys as $key) {
                //type#, persons#, price#, quantity#
                $id = substr($key, 4);
                $type = strip_tags($fields["type$id"]);
                $persons = strip_tags($fields["persons$id"]);
                $price = strip_tags($fields["price$id"]);
                $quantity = strip_tags($fields["quantity$id"]);

                //new room
                if (strpos($id, '*') !== false) {
                    //check if item (room) with these parameters already exists to prevent duplicate entries
                    $sql_check = 'SELECT id FROM room WHERE type=:type AND persons=:persons AND price=:price';
                    $query_check = $this->db->prepare($sql_check);
                    $query_check->execute(array(':type' => $type, ':persons' => $persons, ':price' => $price));
                    //NEW ROOM
                    $room_ID = null;
                    if ($query_check->rowCount() == 0) {
                        $sql = "INSERT INTO room(type, persons, price) VALUES (:type, :persons, :price)";
                        $query = $this->db->prepare($sql);
                        $query->execute(array(':type' => $type, ':persons' => $persons, ':price' => $price));
                        $room_ID = $this->db->lastInsertId();
                    } //EXISTING ROOM
                    else {
                        $room_ID = $query_check->fetch()->id;
                    }

                    //EVENT_TO_ROOM
                    $sql = "INSERT INTO event_to_room(quantity, event_id, room_id) VALUES (:quantity, :event_id, :room_id)";
                    $query = $this->db->prepare($sql);
                    $query->execute(array(':quantity' => $quantity, ':event_id' => $evt_id, ':room_id' => $room_ID));
                } //room already exists, save new values
                else {
                    $sql = "UPDATE room SET type=:type, persons=:persons, price=:price WHERE id=:id";
                    $query = $this->db->prepare($sql);
                    $query->execute(array(':type' => $type, ':persons' => $persons, ':price' => $price, ':id' => $id));
                    $sql = "UPDATE event_to_room SET quantity=:quantity WHERE event_id=:event_id AND room_id=:room_id)";
                    $query = $this->db->prepare($sql);
                    $query->execute(array(':quantity' => $quantity, ':event_id' => $evt_id, ':room_id' => $id));
                }
            }
        }
        //check for deleted rooms
        if (!empty($existing)) {
            foreach ($existing as $room) {
                $id = $room->id;
                if (!array_key_exists("type$id", $fields)) {
                    $sql = "DELETE FROM event_to_room WHERE id=:id";
                    $query = $this->db->prepare($sql);
                    $query->execute(array(':id' => $room->etrid));
                    $sql = "DELETE FROM room WHERE id=:id";
                    $query = $this->db->prepare($sql);
                    $query->execute(array(':id' => $id));
                }
            }
        }
        $this->changes($_SESSION['account'], "edited an event $id ($name)", $_SESSION['account']);
        if ($err == null) {
            return L::alerts_s_saved;
        } else {
            $err = substr($err, 1);
            return L::alerts_s_saved . '<br>' . $err;
        }
    }

    //Delete event photo
    public function deletePhoto($id)
    {
        $sql = 'SELECT * FROM account WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $_SESSION['account']));
        $account = $query->fetch();
        if ($account->status < ADMIN) {
            $this->changes($_SESSION['account'], "attempted to delete event ID $id photo", $_SESSION['account']);
            return L::alerts_d_cantDoThat;
        }
        $sql = 'SELECT img FROM event WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $event = $query->fetch();
        $target_dir = 'public/events/';
        if (file_exists($target_dir . $event->img . '.jpg')) {
            unlink($target_dir . $event->img . '.jpg');
        } else {
            return L::alerts_d_noPhoto;
        }
        $sql = 'UPDATE event SET img=null WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $this->changes($_SESSION['account'], "deleted the event ID $id photo", $_SESSION['account']);
        return L::alerts_s_evtPhotoReset;
    }

    //Change confirmed status of Attendees for event ID
    public function editConfirm($event, $ids)
    {
        $sql = 'SELECT * FROM account WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $_SESSION['account']));
        $account = $query->fetch();
        if ($account->status < ADMIN) {
            $this->changes($_SESSION['account'], "attempted to change confirmed statuses of event ID $event", $_SESSION['account']);
            return L::alerts_d_cantDoThat;
        }
        $sql = 'SELECT id, confirmed FROM registration WHERE event_id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $event));
        $attendees = $query->fetchAll();
        foreach ($attendees as $attendee) {
            $confirmed = (array_key_exists($attendee->id, $ids)) ? 1 : 0;
            if ($confirmed == $attendee->confirmed) {
                continue;
            }
            $sql = "UPDATE registration SET confirmed=:confirmed WHERE id=:id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':confirmed' => $confirmed, ':id' => $attendee->id));
        }
        $this->changes($_SESSION['account'], "changed confirmed statuses of users for event ID $event", $_SESSION['account']);
        return L::alerts_s_confStatus;
    }

    //Add manual payment for given ID
    public function addPayment($id, $amount)
    {
        $sql = "INSERT INTO payment(amount, reg_id, verified, start_time, manual) VALUES (:amount, :reg_id, 1, NOW(), :manual)";
        $query = $this->db->prepare($sql);
        $query->execute(array(':amount' => $amount, ':reg_id' => $id, ':manual' => $_SESSION['account']));
    }

    //gets sum up payments for registration ID
    public function getSumPayments($id)
    {
        $sql = 'SELECT SUM(amount) AS paid FROM payment WHERE reg_id=:id AND verified=1';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        return $query->fetch();
    }

    //Get payments event ID
    public function getPayments($id)
    {
        $sql = 'SELECT payment.id as id, amount, session_id AS session, username, verified, start_time AS paytime, manual FROM payment INNER JOIN registration ON payment.reg_id=registration.id INNER JOIN account ON registration.acc_id=account.id WHERE event_id=:id ORDER BY paytime ASC';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        return $query->fetchAll();
    }

    //Get username of account with given account ID
    public function getUsername($id)
    {
        $sql = 'SELECT username FROM account WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        return $query->fetch();
    }

    //Verify payment
    public function verifyPayment($id)
    {
        $sql = "UPDATE payment SET verified=1 WHERE id=:id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $this->changes($_SESSION['account'], "verified payment ID $id", $_SESSION['account']);
    }

    //Unverify payment
    public function unverifyPayment($id)
    {
        $sql = "UPDATE payment SET verified=0 WHERE id=:id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $this->changes($_SESSION['account'], "unverified payment ID $id", $_SESSION['account']);
    }

    public function deletePayment($id)
    {
        $sql = 'DELETE FROM payment WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $this->changes($_SESSION['account'], "deleted payment ID $id", $_SESSION['account']);
    }

    // Delete registration with given ID
    public function deleteReg($id)
    {
        $sql = 'SELECT * FROM account WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $_SESSION['account']));
        $account = $query->fetch();
        if ($account->status < ADMIN) {
            $this->changes($_SESSION['account'], "attempted to delete registration ID $id", $_SESSION['account']);
            return L::alerts_d_cantDoThat;
        }
        $sql = 'DELETE FROM registration WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $this->changes($_SESSION['account'], "deleted registration ID $id", $_SESSION['account']);
        return L::alerts_s_regDeleted;
    }

    public function exportForms($id){
        $sql = 'SELECT fname, lname, dob, address, address2, post, city, country, username FROM account INNER JOIN registration ON account.id=registration.acc_id WHERE registration.event_id=:id AND confirmed=1 AND permanent_waiver=0 ORDER BY username';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $accounts = $query->fetchAll();
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 10,
            'format' => [135, 139],
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0
        ]);
        $locations=[[2, 5], [2, 72], [37, 5], [37, 72], [72, 5], [72, 72], [107, 5], [107, 72]];
        $text="";
        for ($i = 0; $i < count($accounts); $i++){
            $account=$accounts[$i];
            if($i%8 == 0){
                if($i != 0){
                    $mpdf->WriteHTML($text);
                    $mpdf->AddPage();
                }
                $text="";
            }
            $location = $locations[$i%8];
            $text.="<div style='position: absolute; top:{$location[0]}mm; left:{$location[1]}mm'>{$account->fname} {$account->lname}<br>{$account->address}<br>{$account->post} {$account->city}<br>{$account->country}<br><br>Nickname: {$account->username}</div>";
        }
        $mpdf->WriteHTML($text);
        $mpdf->SetTitle('Event name stickers');
        $mpdf->Output('Event_name_stickers_' . $id . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    // Exports invoices for registered users
    public function exportInvoices($id)
    {
        $sql = 'SELECT registration.id AS id, name, fname, lname, address, address2, post, city, country, language, event_start, regular_price, regular_title, sponsor_price, sponsor_title, super_price, super_title, ticket, amount, payment_due, start_time, room.type AS room_type, room.price AS room_price FROM account INNER JOIN registration ON account.id=registration.acc_id INNER JOIN event ON event.id=registration.event_id INNER JOIN payment ON payment.reg_id=registration.id LEFT JOIN room ON room.id=registration.room_id WHERE event_id=:id AND payment.manual IS NOT NULL';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $invoices = $query->fetchAll();
        $mpdf = new \Mpdf\Mpdf(['format' => 'A5']);
        $stylesheet = file_get_contents('public/invoicepdf.css');
        $mpdf->WriteHTML($stylesheet, 1);
        foreach ($invoices as $invoice) {
            $eventTime = $this->convertViewable($invoice->event_start, true);
            if(!is_null($invoice->payment_due)){
                $dueDate = date('d.m.Y', strtotime($invoice->payment_due));
            }
            else{
                $dueDate = date('d.m.Y', strtotime('-1 day', strtotime($eventTime)));
            }
            // issue on the date of payment
            if(!is_null($invoice->start_time)){
                $issueDate = date('d.m.Y', strtotime($invoice->start_time));
            }
            // issue today if date of service is in the future
            else if(strtotime($invoice->event_start) > strtotime("now")){
                $issueDate = date("d.m.Y");
            }
            // issue on the due date
            else{
                $issueDate = $dueDate;
            }
            $addr2 = '';
            if ($invoice->address2 != '') {
                $addr2 = '<br>' . $invoice->address2;
            }
            if ($invoice->language == 'si') {
                switch ($invoice->ticket) {
                    case 'regular':
                        $ticketType = ($invoice->regular_title == '') ? 'redna cena' : $invoice->regular_title;
                        $ticketPrice = $invoice->regular_price;
                        break;
                    case 'sponsor':
                        $ticketType = ($invoice->sponsor_title == '') ? 'redna cena' : $invoice->sponsor_title;
                        $ticketPrice = $invoice->sponsor_price;
                        break;
                    case 'super':
                        $ticketType = ($invoice->super_title == '') ? 'redna cena' : $invoice->super_title;
                        $ticketPrice = $invoice->super_price;
                        break;
                }
                $ticketPaid = $invoice->amount >= $ticketPrice ? 'PLAČANO V CELOTI' : '';
                $text = "<div>
				  <div>
				    <table class='table'>
				      <tr>
				        <td>
				          <h3>RAČUN ŠT.: {$invoice->id}</h3>
				          Datum izdaje: $issueDate<br>
				          Datum storitve: $eventTime
				        </td>
				        <td class='text-right'><b>Društvo SloFurs</b><br>Gregorčičeva ulica 33<br>5000 Nova Gorica<br>Davčna št.: 73456012<br>Matična št.: 4121988000<br>IBAN: SI56 6100 0002 4500 122</td>
				      </tr>
				    </table>
				  </div>
				  <div class='mb-5'>
				     <p><b>{$invoice->fname} {$invoice->lname}</b><br>{$invoice->address}$addr2<br>{$invoice->post} {$invoice->city}<br>{$invoice->country}</p>
				  </div>
				  <table class='table'>
				    <tr>
				      <th style='border-bottom: 1px solid #000;'>Opis</th>
				      <th style='border-bottom: 1px solid #000;'>Cena</th>
				      <th style='border-bottom: 1px solid #000;'>Količina</th>
				      <th style='border-bottom: 1px solid #000;'>Znesek</th>
				    </tr>
				    <tr>
				      <td>Vstopnina na dogodek<br><small><i>{$invoice->name} - $ticketType</i></small></td>
				      <td>$ticketPrice €</td>
				      <td>1</td>
				      <td>$ticketPrice €</td>
				    </tr>";
                if($invoice->room_price){
                    $text = $text . "<tr>
				      <td>Nastanitev<br><small><i>{$invoice->room_type}</i></small></td>
				      <td>{$invoice->room_price} €</td>
				      <td>1</td>
				      <td>{$invoice->room_price} €</td>
				    </tr>";
                    $ticketPrice += $invoice->room_price;
                }
                $text = $text . "<tr>
				      <td style='border-top: 1px solid #000;'><b>$ticketPaid</b></td>
				      <td style='border-top: 1px solid #000;'></td>
				      <td style='border-top: 1px solid #000;'><b>Skupaj</b></td>
				      <td style='border-top: 1px solid #000;'><b>$ticketPrice €</b></td>
				    </tr>
				  </table>
				  <p>Pri plačilu uporabite sklic SI00 {$invoice->id}.</p>
				  <p>V skladu s 1. odstavkom 94. člena ZDDV davek na dodano vrednost ni obračunan.</p>
				</div>";
            } else {
                switch ($invoice->ticket) {
                    case 'regular':
                        $ticketType = ($invoice->regular_title == '') ? 'regular price' : $invoice->regular_title;
                        $ticketPrice = $invoice->regular_price;
                        break;
                    case 'sponsor':
                        $ticketType = ($invoice->sponsor_title == '') ? 'sponsor' : $invoice->sponsor_title;
                        $ticketPrice = $invoice->sponsor_price;
                        break;
                    case 'super':
                        $ticketType = ($invoice->super_title == '') ? 'super sponsor' : $invoice->super_title;
                        $ticketPrice = $invoice->super_price;
                        break;
                }
                $ticketPaid = $invoice->amount >= $ticketPrice ? 'FULLY PAID' : '';
                $text = "<div>
				  <div>
				    <table class='table'>
				      <tr>
				        <td>
				          <h3>INVOICE No.: {$invoice->id}</h3>
				          Date of issue: $issueDate<br>
				          Date of event: $eventTime<br>
				          Due date: $dueDate
				        </td>
				        <td class='text-right'><b>Društvo SloFurs</b><br>Gregorčičeva ulica 33<br>5000 Nova Gorica<br>Tax No.: 73456012<br>ID No.: 4121988000<br>IBAN: SI56 6100 0002 4500 122</td>
				      </tr>
				    </table>
				  </div>
				  <div class='mb-5'>
				     <p><b>{$invoice->fname} {$invoice->lname}</b><br>{$invoice->address}$addr2<br>{$invoice->post} {$invoice->city}<br>{$invoice->country}</p>
				  </div>
				  <table class='table'>
				    <tr>
				      <th style='border-bottom: 1px solid #000;'>Description</th>
				      <th style='border-bottom: 1px solid #000;'>Price</th>
				      <th style='border-bottom: 1px solid #000;'>Qty</th>
				      <th style='border-bottom: 1px solid #000;'>Sum</th>
				    </tr>
				    <tr>
				      <td>Attendance fee<br><small><i>{$invoice->name} - $ticketType</i></small></td>
				      <td>$ticketPrice €</td>
				      <td>1</td>
				      <td>$ticketPrice €</td>
				    </tr>";
                if($invoice->room_price){
                    $text = $text . "<tr>
				      <td>Accomodation<br><small><i>{$invoice->room_type}</i></small></td>
				      <td>{$invoice->room_price} €</td>
				      <td>1</td>
				      <td>{$invoice->room_price} €</td>
				    </tr>";
                    $ticketPrice += $invoice->room_price;
                }
                $text = $text . "<tr>
				      <td style='border-top: 1px solid #000;'><b>$ticketPaid</b></td>
				      <td style='border-top: 1px solid #000;'></td>
				      <td style='border-top: 1px solid #000;'><b>Total</b></td>
				      <td style='border-top: 1px solid #000;'><b>$ticketPrice €</b></td>
				    </tr>
				  </table>
				  <p>When paying the invoice write this message: SI00 {$invoice->id}.</p>
				  <p>Based on Slovenian law, we do not collect value added tax (section 1. of clause 94. of the ZDDV law).</p>
				</div>";
            }
            $mpdf->WriteHTML($text, 2);
            $mpdf->AddPage();
        }


        $mpdf->SetTitle('Invoices for event ID ' . $id);
        $mpdf->Output('Invoices_' . $id . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    // Exports registered users contact info
    public function exportContactData($id)
    {
        $sql = 'SELECT fname, lname, address, address2, post, city, country, phone, location, event_start AS es, event_end AS ee FROM account INNER JOIN registration ON account.id=registration.acc_id INNER JOIN event ON event.id=registration.event_id WHERE event.id=:id ORDER BY lname ASC, fname ASC';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $accounts = $query->fetchAll();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML("<h1>Seznam udeležencev dogodka</h1>
		<p>Kraj zbiranja: {$accounts[0]->location}</p>
		<p>Čas zbiranja: {$accounts[0]->es} - {$accounts[0]->ee}</p><br>
		<table>
		<tr>
		<th>Ime & priimek</th>
		<th>Naslov</th>
		<th>Telefonska št.</th>
		</tr>");
        foreach ($accounts as $account) {
            $text = "<tr>
			<td>{$account->fname} {$account->lname}</td>
			<td>{$account->address} {$account->address2}<br>{$account->post} {$account->city}<br>{$account->country}</td>
			<td>{$account->phone}</td>
			</tr>";
            $mpdf->WriteHTML($text);
        }
//        $text = "<tr>
//		<td>__________________________________</td>
//		<td><br><br><br>__________________________________<br><br>__________________________________<br><br>__________________________________</td>
//		<td>__________________________________</td>
//		</tr>";
//        for ($i = 0; $i < 3; $i++) {
//            $mpdf->WriteHTML($text);
//        }
        $mpdf->WriteHTML("</table>");
        $mpdf->SetTitle('Contact data for event ID ' . $id);
        $mpdf->Output('Contacts_' . $id . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    // Exports registered users names, price and line for signature (for Društvo SloFurs)
    public function exportDrustvoData($id)
    {
        $sql = 'SELECT fname, lname, location, username, event_start AS es, event_end AS ee FROM account INNER JOIN registration ON account.id=registration.acc_id INNER JOIN event ON event.id=registration.event_id WHERE event.id=:id ORDER BY lname ASC, fname ASC';
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $accounts = $query->fetchAll();
        $eventTime = $this->convertViewable($accounts[0]->es, true);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML("<h1>Pristojbina na dogodek</h1>
		<p>Kraj zbiranja: {$accounts[0]->location}</p>
		<p>Čas dogodka: {$accounts[0]->es} - {$accounts[0]->ee}</p><br>
		<table>
		<tr>
		<th>Vzdevek</th>
		<th>Ime & priimek</th>
		<th>Znesek</th>
		<th>Podpis</th>
		</tr>");
        foreach ($accounts as $account) {
            $text = "<tr>
			<td>{$account->username} </td>
			<td>{$account->fname} {$account->lname} </td>
			<td>________€ </td>
			<td>____________________________</td>
			</tr>";
            $mpdf->WriteHTML($text);
        }
//        $text = "<tr>
//		<td>__________________________________</td>
//		<td><br><br><br>__________________________________<br><br>__________________________________<br><br>__________________________________</td>
//		<td>__________________________________</td>
//		</tr>";
//        for ($i = 0; $i < 3; $i++) {
//            $mpdf->WriteHTML($text);
//        }
        $mpdf->WriteHTML("</table><br><br>
			V/Na ____________________________, dne $eventTime");
        $mpdf->SetTitle('Pristojbina na dogodku ' . $id);
        $mpdf->Output('Money_collection_' . $id . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }
}
