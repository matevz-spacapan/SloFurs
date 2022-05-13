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
        $sql = 'SELECT registration.id as id, ticket, registration.created as created, confirmed, fursuiter, artist, username, type, price, room_confirmed, language, dob, notes, account.id AS accID FROM registration INNER JOIN account ON registration.acc_id=account.id LEFT JOIN room ON registration.room_id=room.id WHERE event_id=:id ORDER BY registration.created ASC';
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

    // Exports registered users into release forms
    public function exportForms($id, $all)
    {
        $sql = 'SELECT fname, lname, dob, address, address2, post, city, country, gender, language, username FROM account INNER JOIN registration ON account.id=registration.acc_id WHERE event_id=:id';
        if (!$all) {
            $sql .= ' AND confirmed=1';
        }
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $accounts = $query->fetchAll();
        $mpdf = new \Mpdf\Mpdf();
        foreach ($accounts as $account) {
            $dob = $this->convertViewable($account->dob, 1);
            if ($account->language == 'si') {
                if ($account->gender == 'female') {
                    $pripona = 'a';
                    $podpisani = 'podpisana';
                    $navedel = 'navedla';
                } else {
                    $pripona = '';
                    $podpisani = 'podpisani';
                    $navedel = 'navedel';
                }
                $text = "<b>{$account->username}</b><br><br><br>
                Spodaj $podpisani {$account->fname} {$account->lname} rojen$pripona $dob s stalnim prebivališčem na naslovu<br><br>
				{$account->address}<br>";
                if ($account->address2 != '' || $account->address2 != null) {
                    $text .= "{$account->address2}<br>";
                }
                $text .= "{$account->post}&nbsp;{$account->city}<br><br>
				izjavljam, da:

				<ul>
				  <li>Sem natančno prebral$pripona, sem seznanjen$pripona in se v celoti strinjam s Pravili in pogoji, objavljenimi na spletni strani SloFurs: http://slofurs.org/pravila/ v času trajanja dogodka. Upošteval$pripona bom vsa pravila in pogoje, navedene v omenjenem besedilu in se strinjam z vsemi možnimi sankcijami, ki jih lahko določi organizator.</li>
				  <li>Sem natančno prebral$pripona, sem seznanjen$pripona in se v celoti strinjam s Politiko o zasebnosti, objavljeno na spletni strani SloFurs dogodkov: http://events.slofurs.org/privacy v času dogodka.</li>
				  <li>Se zavedam, da organizator srečanja ni odgovoren za kakršnokoli poškodbo ali škodo, ki jo lahko doživim ali utrpim med srečanjem. Poleg tega prevzemam polno odgovornost za svoja dejanja med srečanjem.</li>
				  <li>Se zavedam, da mi organizator ni dolžan nikakršne povrnitve stroškov, v primeru neudeležbe, prepovedi udeležbe ali odstranitve s srečanja zaradi kršitve pravil srečanja.</li>
				  <li>Bom upošteval$pripona navodila organizatorja na srečanju in se zavedam, da ima organizator popolno pravico do spreminjanja, dodajanja k in tolmačenja Pravil in pogojev.</li>
				  <li>Se zavedam, da se srečanja odvijajo na ozemlju Republike Slovenije, torej na srečanjih veljajo vsi zakoni ustave Republike Slovenije.</li>
				  <li>So vsi podatki, ki sem jih $navedel ob prijavi pravilni in resnični.</li>
				  <li>Se zavedam, da se s podpisom te izjave nepreklicno zavezujem k spoštovanju in upoštevanju vseh členov navedenih v tej izjavi, ter v primeru neupoštevanja le-teh dovoljujem organizatorju izvedbo kakršne koli sankcije.</li>
				</ul><br><br><br><br><br><br>
				Podpis: ____________________________";
            } else {
                $text = "I, {$account->fname} {$account->lname}, signed below, born on $dob with a permanent residence on the address<br><br>
				{$account->address}<br>";
                if ($account->address2 != '' || $account->address2 != null) {
                    $text .= "{$account->address2}<br>";
                }
                $text .= "{$account->post}&nbsp;{$account->city}<br>
				{$account->country}<br><br>
				declare that:

				<ul>
				  <li>I have read, am aware and completely agree with the Rules of conduct published on the SloFurs website: http://slofurs.org/pravila/ at the time of the event. I will obide to all the stated rules in the before mentioned document and agree with all possible sanctions, which can be decided by the organizer.</li>
				  <li>I have read, am aware and completely agree with the Privacy policy published on the SloFurs website: http://events.slofurs.org/privacy at the time of the event.</li>
				  <li>I am aware, that the event organizer is not held responsible for any injury or damage that can happen to me during the event. I also take full responsibility for my actions during the event.</li>
				  <li>I am aware, that the organizer is not obligated to any refund or reimbursment of costs in the case of not attending the event, in case of being banned from the event or removal from the event because of a breach in the Rules of conduct.</li>
				  <li>I will follow the instructions and rules from the organizer during the meet and I am aware, that the organizer has the full right to alter, add or interpret the Rules of conduct.</li>
				  <li>I am aware, that because the event is held in the Republic of Slovenia, that all laws of the constitution of Slovenia are also enforced and have to be followed.</li>
				  <li>All the personal information stated at the time of registration and stated above is true and accurate.</li>
				  <li>I am aware, that by signing this declaration, I irrevocably commit to adhering to and following all the articles listed in this declaration and that, in the event of not doing so, I allow the organizer to carry out any sanction.</li>
				</ul><br><br><br><br><br><br><br><br>
				Signature: ____________________________";
            }
            $mpdf->WriteHTML($text);
            $mpdf->AddPage();
        }
        $mpdf->WriteHTML("Spodaj podpisani/-a __________________________ rojen/-a ______________ s stalnim prebivališčem na naslovu<br><br>
		__________________________________________________________________<br>
		izjavljam, da:
		<ul>
			<li>Sem natančno prebral/-a, sem seznanjen/-a in se v celoti strinjam s Pravili in pogoji, objavljenimi na spletni strani SloFurs: http://slofurs.org/pravila/ v času trajanja dogodka. Upošteval/-a bom vsa pravila in pogoje, navedene v omenjenem besedilu in se strinjam z vsemi možnimi sankcijami, ki jih lahko določi organizator.</li>
			<li>Sem natančno prebral/-a, sem seznanjen/-a in se v celoti strinjam s Politiko o zasebnosti, objavljeno na spletni strani SloFurs dogodkov: http://events.slofurs.org/privacy v času dogodka.</li>
			<li>Se zavedam, da organizator srečanja ni odgovoren za kakršnokoli poškodbo ali škodo, ki jo lahko doživim ali utrpim med srečanjem. Poleg tega prevzemam polno odgovornost za svoja dejanja med srečanjem.</li>
			<li>Se zavedam, da mi organizator ni dolžan nikakršne povrnitve stroškov, v primeru neudeležbe, prepovedi udeležbe ali odstranitve s srečanja zaradi kršitve pravil srečanja.</li>
			<li>Bom upošteval/-a navodila organizatorja na srečanju in se zavedam, da ima organizator popolno pravico do spreminjanja, dodajanja k in tolmačenja Pravil in pogojev.</li>
			<li>Se zavedam, da se srečanja odvijajo na ozemlju Republike Slovenije, torej na srečanjih veljajo vsi zakoni ustave Republike Slovenije.</li>
			<li>So vsi podatki, ki sem jih navedel/-la ob prijavi pravilni in resnični.</li>
			<li>Se zavedam, da se s podpisom te izjave nepreklicno zavezujem k spoštovanju in upoštevanju vseh členov navedenih v tej izjavi, ter v primeru neupoštevanja le-teh dovoljujem organizatorju izvedbo kakršne koli sankcije.</li>
		</ul><br><br><br><br><br><br>
		Podpis: ____________________________");
        $mpdf->AddPage();

        $mpdf->WriteHTML("I, ______________________________, signed below, born on __________________ with a permanent residence on the address<br><br>
		________________________________________________________________________________<br>
		declare that:

		<ul>
			<li>I have read, am aware and completely agree with the Rules of conduct published on the SloFurs website: http://slofurs.org/pravila/ at the time of the event. I will obide to all the stated rules in the before mentioned document and agree with all possible sanctions, which can be decided by the organizer.</li>
			<li>I have read, am aware and completely agree with the Privacy policy published on the SloFurs website: http://events.slofurs.org/privacy at the time of the event.</li>
			<li>I am aware, that the event organizer is not held responsible for any injury or damage that can happen to me during the event. I also take full responsibility for my actions during the event.</li>
			<li>I am aware, that the organizer is not obligated to any refund or reimbursment of costs in the case of not attending the event, in case of being banned from the event or removal from the event because of a breach in the Rules of conduct.</li>
			<li>I will follow the instructions and rules from the organizer during the meet and I am aware, that the organizer has the full right to alter, add or interpret the Rules of conduct.</li>
			<li>I am aware, that because the event is held in the Republic of Slovenia, that all laws of the constitution of Slovenia are also enforced and have to be followed.</li>
			<li>All the personal information stated at the time of registration and stated above is true and accurate.</li>
			<li>I am aware, that by signing this declaration, I irrevocably commit to adhering to and following all the articles listed in this declaration and that, in the event of not doing so, I allow the organizer to carry out any sanction.</li>
		</ul><br><br><br><br><br><br><br><br>
		Signature: ____________________________");

        $mpdf->SetTitle('Release forms for event ID ' . $id);
        $mpdf->Output('Release_forms_' . $id . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    // Exports invoices for registered users
    public function exportInvoices($id, $all)
    {
        $sql = 'SELECT registration.id AS id, name, fname, lname, address, address2, post, city, country, language, event_start, regular_price, regular_title, sponsor_price, sponsor_title, super_price, super_title, ticket FROM account INNER JOIN registration ON account.id=registration.acc_id INNER JOIN event ON event.id=registration.event_id WHERE event_id=:id';
        if (!$all) {
            $sql .= ' AND confirmed=1';
        }
        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));
        $invoices = $query->fetchAll();
        $mpdf = new \Mpdf\Mpdf(['format' => 'A5']);
        $stylesheet = file_get_contents('public/invoicepdf.css');
        $mpdf->WriteHTML($stylesheet, 1);
        foreach ($invoices as $invoice) {
            $today = date("d.m.Y");
            $eventTime = $this->convertViewable($invoice->event_start, true);
            $due = date('d.m.Y', (strtotime('-1 day', strtotime($eventTime))));
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
                $text = "<div>
				  <div>
				    <table class='table'>
				      <tr>
				        <td>
				          <h3>RAČUN ŠT.: {$invoice->id}</h3>
				          Datum izdaje:$today<br>Datum storitve:$eventTime<br>Rok plačila:$due
				        </td>
				        <td class='text-right'><b>Društvo SloFurs</b><br>Gregorčičeva ulica 33<br>5000 Nova Gorica<br>ID za DDV: 73456012<br>Matična št.: 4121988000<br>IBAN: SI56 6100 0002 4500 122</td>
				      </tr>
				    </table>
				  </div>
				  <div class='mb-5'>
				     <p><b>{$invoice->fname} {$invoice->lname}</b><br>{$invoice->address}$addr2<br>{$invoice->post} {$invoice->city}<br>{$invoice->country}</p>
				  </div>
				  <table class='table'>
				    <tr>
				      <th>Opis</th>
				      <th>Cena</th>
				      <th>Količina</th>
				      <th>Znesek</th>
				    </tr>
				    <tr>
				      <td>Vstopnina na dogodek<br><small><i>{$invoice->name} - $ticketType</i></small></td>
				      <td>$ticketPrice €</td>
				      <td>1</td>
				      <td>$ticketPrice €</td>
				    </tr>
				    <tr>
				      <td></td>
				      <td></td>
				      <td><b>Skupaj</b></td>
				      <td><b>$ticketPrice €</b></td>
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
                $text = "<div>
				  <div>
				    <table class='table'>
				      <tr>
				        <td>
				          <h3>INVOICE No.: {$invoice->id}</h3>
				          Date of issue:$today<br>Date of event:$eventTime<br>Due date:$due
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
				      <th>Description</th>
				      <th>Price</th>
				      <th>Qty</th>
				      <th>Sum</th>
				    </tr>
				    <tr>
				      <td>Attendance fee<br><small><i>{$invoice->name} - $ticketType</i></small></td>
				      <td>$ticketPrice €</td>
				      <td>1</td>
				      <td>$ticketPrice €</td>
				    </tr>
				    <tr>
				      <td></td>
				      <td></td>
				      <td><b>Total</b></td>
				      <td><b>$ticketPrice €</b></td>
				    </tr>
				  </table>
				  <p>When paying the invoice write this message: Invoice {$invoice->id}.</p>
				  <p>Based on Slovenian law, we do not collect value added tax (v skladu s 1. odstavkom 94. člena ZDDV davek na dodano vrednost ni obračunan).</p>
				</div>";
            }
            $mpdf->WriteHTML($text, 2);
            $mpdf->AddPage();
        }


        $mpdf->SetTitle('Invoices for event ID ' . $id);
        $mpdf->Output('Invoices_' . $id . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    // Exports registered users contact info for NIJZ compliance
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
}
