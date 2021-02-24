<?php
//Debug info
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

//Database configuration
define('HOST','localhost');
define('HOST_NAME','admin');
define('HOST_PASSWORD','password');
define('DB_NAME','sitedb');

// Site configuration
define('ANNOUNCE', ''); //Set a message here that will be shown to every user (only the first time)
define('SITE_URL','http://localhost/');
define('APP_URL','/app/');
define('SITE_NAME','Halfp');
define('DEFAULT_PROFILE_IMAGE', 'no_profile.png');
// Number of reports that will automatically delete a post/comment/account
define('REPORT_THRESHOLD', '10');
define('CONTACT_EMAIL', 'example@example.com');

define('captcha', '2'); // 0 = geen captcha / 1 = werkende captcha / 2 = captcha zichtbaar maar je kan registeren zonder te gebruiken

/* Als je captcha gebruikt */
define('PUBLIC_KEY', 'pubkey'); // SITE_KEY - reCaptcha Public key
define('PRIVATE_KEY', 'secretkey'); // SECRET_KEY - reCaptcha Private key

define('LIBS', 'libs/');

//Set the language
if(!isset($_COOKIE["lang"])) {
    setcookie("lang", "en", time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
}

$lang = $_COOKIE["lang"];
if ((strlen($lang) == 2) && (file_exists("languages/" . $lang . ".php"))) {
    require "languages/" . $lang . ".php";
} else {
    require "languages/en.php";
}

