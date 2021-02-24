<?php
//Debug info
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

//Database settings
define('HOST','localhost');
define('HOST_NAME','admin');
define('HOST_PASSWORD','password');
define('DB_NAME','sitedb');
define('TABLE_USERS','users');
define('TABLE_CHATS','chats');
define('TABLE_BLOCKS','chat_blocks');
//Encryption key used to store encrypted messages on the db. Leave blank to save in clear-text
define('ENCRYPTION_KEY', 'ChatSystem');

// Chat settings
define('CHAT_URL','http://localhost/chat/');
define('CHAT_NAME','ChatApp');
//Session key to look for in order to get the id of current logged user
define('SESSION_UID', 'id');
//Base directory where to save uploaded images
define('UPLOAD_DIR', '../uploads');
//Define maximum size allowed for uploaded images
define('MAX_IMAGE_WIDTH', 1920);
define ('MAX_IMAGE_HEIGHT', 1920);

//Define a comma separated list of forbidden words (e.g. 'word1,word2,word3')
define('FORBIDDEN_WORDLIST', '');

// Create global database PDO
$database = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, HOST_NAME,HOST_PASSWORD);
$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
