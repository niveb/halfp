<?php 

class searchModel extends Model {
	
    function __construct() {
        parent::__construct();
    }

    function getUsers($mygender, $searchgender, $country = "", $city = "", $fromage = 0, $toage = 99, $withphoto = False) {
        $res = array();
        if ($mygender == null)
            return $res;
        $query = "SELECT * FROM users WHERE searchable = 1";
        if (($city != "") && ($country != "")) {
            $query .= " AND country = :country AND city = :city";
        }
        if ($searchgender == "same") {
            $query .= " AND gender = :gender";
        } else {
            $query .= " AND gender != :gender";
        }
        if ($withphoto) {
            $query .= " AND profile_image != '".DEFAULT_PROFILE_IMAGE."'";
        }
        $toborndate = strval(intval(date("Y")) - intval($fromage) + 1) . "-01-01";
        $fromborndate = strval(intval(date("Y")) - intval($toage)) . "-01-01";
        $query .= " AND (borndate BETWEEN :fromdate AND :todate) ORDER BY last_online DESC";
        $stmt = $this->db->prepare($query);
        if (($city != "") && ($country != "")) {
            $stmt->bindParam(':city', $city, PDO::PARAM_STR);
            $stmt->bindParam(':country', $country, PDO::PARAM_STR);
        }
        $stmt->bindParam(':gender', $mygender, PDO::PARAM_STR);
        $stmt->bindParam(':fromdate', $fromborndate, PDO::PARAM_STR);
        $stmt->bindParam(':todate', $toborndate, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $res;
    }
}// class

