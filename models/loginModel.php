<?php 

class loginModel extends Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function login() 
	{
                AjaxOnly();
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		// PREPARED STATEMENT HELPT TEGEN SQL INJECTION
		$stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$data = $stmt->fetch();
		
		$id = $data['id'];
		$password_db = $data['password'];
		
		$count = $stmt->rowCount();
		
		// Alles checken
		if(empty($username) || empty($password)){
			echo STR_ERRMISSINFO;
		} elseif ($count == 0){
			echo STR_INVALIDCREDS;
		} elseif (password_verify($password, $data['password']) == false) {
			echo STR_INVALIDCREDS;
		} elseif ($count > 0 && password_verify($password, $data['password']) == true) {
			Session::init();
			Session::set('loggedIn', true);
			Session::set('id', $data['id']);
            Session::set('username', $data['username']);
            redirectHTML("feed");
		} 
	}

    private function validEmail($email) {
		$stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		$stmt->fetch(PDO::FETCH_ASSOC);
        return ($stmt->rowCount() == 0);
    }

	public function register()
	{
        AjaxOnly();
        if (!isset($_POST['email']) || !isset($_POST['username']) || !isset($_POST['firstname']) || !isset($_POST['surname']) || !isset($_POST['password']) || !isset($_POST['country'])
            || !isset($_POST['city']) || !isset($_POST['phone']) || !isset($_POST['borndate']) || !isset($_POST['gender']))
            echo STR_ERRMISSINFO;
		$email = $_POST['email'];
		$username = $_POST['username'];
		$firstname = $_POST['firstname'];
		$surname = $_POST['surname'];
		$password = $_POST['password'];
        $country = strtolower(trim($_POST['country']));
        $city = strtolower(trim($_POST['city']));
        $phone = $_POST['phone'];
        $borndate = $_POST['borndate'];
        if ($_POST['gender'] == "male")
            $gender = "male";
        else
            $gender = "female";
        $reg_ip = $_SERVER['REMOTE_ADDR'];
		$hashed_pass = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
		
		// Google recaptcha
		$response = "fakecaptcha";/*$_POST["captcha"];
		$secret = PRIVATE_KEY;
		$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
		$captcha_success=json_decode($verify);*/

		$stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		
		//Kijken of gebruiker bestaat
		$count = $stmt->rowCount();
			
		if(empty($email) || empty($firstname) || empty($surname) || empty($username) || empty($password))
		{
			echo STR_ERRMISSINFO;
		} 
		elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
		{
			echo STR_INVALIDEMAIL;
		}
		elseif($count > 0) 
		{
			echo STR_ERRUSERNAMEUSED;
		} 
		elseif(!$this->validEmail($email))
		{
			echo STR_ERREMAILUSED;
		} 
		elseif(!preg_match("/^[a-zA-Z\'\-\040]+$/", $firstname))
		{
			echo STR_INVALIDNAME;
		}
		elseif(!preg_match("/^[a-zA-Z\'\-\040]+$/", $surname))
		{
			echo STR_INVALIDSURNAME;
		}
		elseif( !ctype_alnum( str_replace("_","",str_replace(".","",$username)) ) )
		{
			echo STR_INVALIDUSERNAME;
		}
		elseif( (strlen($phone) < 7) || (strlen($phone) > 14) || (!is_numeric($phone)) )
		{
			echo STR_INVALIDPHONE;
		}
		elseif (strlen($password) <= '6') 
		{
			echo STR_ERRPASSLEN;
/*		}
		elseif ($captcha_success->success==false && captcha == 1) 
		{
			echo "Captcha is niet ingevuld";*/
		} else {
			$stm = $this->db->prepare("INSERT INTO users (username, email, password, firstname, surname,country,city,phone,borndate,gender,profile_image,reg_ip,last_ip,reg_date,last_online) VALUES (:username, :email, :hashedpassword, :firstname,:surname,:country,:city,:phone,:borndate,:gender,:profile_image,:regIp,:lastIp ,UNIX_TIMESTAMP(),UNIX_TIMESTAMP())");
			$stm->execute([
                            ":username" => $username,
                            ":email" => $email,
                            ":hashedpassword" => $hashed_pass,
                            ":firstname" => $firstname,
                            ":surname" => $surname,
                            ":country" => $country,
                            ":city" => $city,
                            ":phone" => $phone,
                            ":borndate" => $borndate,
                            ":gender" => $gender,
                            ":profile_image" => DEFAULT_PROFILE_IMAGE,
                            ":regIp" => $reg_ip,
                            ":lastIp" => $reg_ip
                        ]);
                        
                        Session::init();
                        Session::set('username', $username);
			redirectHTML("login/welcome");
		}	
	}
        public function welcome($sName) 
        {
            $stm = $this->db->prepare("SELECT id FROM users WHERE username = :username");
            $stm->execute([':username' => $sName]);
            $id = $stm->fetch();
            
            Session::init();
            Session::set('loggedIn', true);
            Session::set('id', $id['id']);
			
            redirectHTML("settings");
        }
        
}
