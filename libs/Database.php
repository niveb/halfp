<?php
/* Controller Index */

class Database extends PDO {
	
	public function __construct() 
	{
        try {
		    parent::__construct('mysql:host='.HOST.';dbname='.DB_NAME,HOST_NAME,HOST_PASSWORD);
            parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $err) {
            die("DB Error: " . $err->getMessage());
        }
	}
	
}
