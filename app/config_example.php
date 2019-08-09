<?php
// MySQL
define("DB_TYPE", "mysql");
define("DB_HOST", "localhost");
define("DB_NAME", "");
define("DB_USER", "");
define("DB_PASS", "");
// General
define("THEME", "2019"); //set the displayed "theme" of the website, if you want to change the looks periodically.
define("URL", "https://localhost/events/"); //the URL to the website as used in the browser
define("BASEURL", "https://localhost"); //the base URL with no subfolders

// Privileges
define("OWNER",99);
define("SUPER", 5);
define("ADMIN", 4);
define("STAFF", 3);
define("PRE_REG", 2);
define("ATTENDEE", 1);

putenv("SENDGRID_API_KEY="); //API key to send emails which you receive on the SendGrid website
?>
