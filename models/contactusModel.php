<?php 
class contactusModel extends Model {
	
	function __construct() {
            parent::__construct();
	}

    function sendEmail($username, $email, $message) {
        $message = "User: " . $username . "\r\n" . "Email: " . $email . "\r\n" . "Message:\r\n" . $message;
        mail(CONTACT_EMAIL,"New message from " . SITE_NAME, $message);
        return "Sent";
    }
}
