<?php
    /**
     * Alle functies die ik ga gebruiken
     */
       function AjaxOnly() 
        {
            define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
            if(!IS_AJAX) {die(STR_ERROR);}
        }
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    function hg($datetime, $full = false) {

        $now = new DateTime;
        $ago = new DateTime("@".$datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => STR_YEARS,
            'm' => STR_MONTHS,
            'w' => STR_WEEKS,
            'd' => STR_DAYS,
            'h' => STR_HOURS,
            'i' => STR_MINUTES,
            's' => STR_SECONDS,
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v;
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . '' : '';
    }
    
    function find_hashtag($str){
        /* Still not supported
        $regex = "/#+([a-zA-Z0-9_]+)/";
        $str = preg_replace($regex, '<span style="color:#328AE7;">$0</span>', $str);
        */
        return($str);
    }
    
    function find_atuser($str){
            $regex = "/@+([a-zA-Z0-9_]+)/";
            $test = preg_replace($regex, '<a href="'.APP_URL.'user/$1"><span style="color:#328AE7;">$0</span></a>', $str);
            $str = $test;
            
            return($str);

    }
	function redirect($link)
	{
    $location = APP_URL.$link;

    header("Location: " . $location);
    exit;
	}

	function redirectHTML($link)
	{
    $location = APP_URL.$link;
	echo "<script>window.location.href='".$location."';</script>";  
    exit;
	}

    function getAge($date) {
        $tz  = new DateTimeZone('Europe/Brussels');
        $age = DateTime::createFromFormat('Y-m-d', $date, $tz)->diff(new DateTime('now', $tz))->y;
        return $age;
    }

?>
