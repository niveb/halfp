<?php
require 'config.php';
require 'class/user.php';
require 'class/uploader.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION[SESSION_UID])) {
    // API can be accessed only by authenticated users
    die("{\"error\" : \"Only authenticated users can access the chat\"}");
}

class ChatAPI {
    function __construct() {
        $this->user = new User($_SESSION[SESSION_UID]);
    }
    public function getUserInfo($username) {
        global $database;
        if ($username == null) {
            return json_encode($this->user);
        } else {
            //Retrieve info of other users only by username (to avoid users enumeration using their incremental IDs)
            if (!is_numeric($username))
                return json_encode(new User($username));
            else
                return "{\"error\" : \"Operation not permitted\"}";
        }
    }
    public function getChatCount() {
        return "{\"chat_count\" : ".$this->user->getChatCount()."}";
    }
    public function didIBlock($dstuid) {
        if ($dstuid == null)
            return "{\"error\" : \"User id must be specified\"}";
        if (!is_numeric($dstuid))
            return "{\"error\" : \"Invalid user id\"}";
        if ($this->user->didIBlock($dstuid))
            return "{\"blocked\" : true}";
        else
            return "{\"blocked\" : false}";
    }
    public function UnblockOtherUser($dstuid) {
        if ($dstuid == null)
            return "{\"error\" : \"User id must be specified\"}";
        if (!is_numeric($dstuid))
            return "{\"error\" : \"Invalid user id\"}";
        if ($this->user->UnblockOtherUser($dstuid)) {
            return "{\"status\" : \"success\", \"user_id\" : ".$dstuid."}";
        } else {
            return "{\"status\" : \"failed\"}";
        }
    }
    public function blockOtherUser($dstuid) {
        if ($dstuid == null)
            return "{\"error\" : \"User id must be specified\"}";
        if (!is_numeric($dstuid))
            return "{\"error\" : \"Invalid user id\"}";
        if ($this->user->blockOtherUser($dstuid)) {
            return "{\"status\" : \"success\", \"user_id\" : ".$dstuid."}";
        } else {
            return "{\"status\" : \"failed\"}";
        }
    }
    public function getChatRecipients() {
        $dstusers = $this->user->getChatRecipients();
        return json_encode($dstusers);
    }
    public function getChatMessages($chatid) {
        if ($chatid == null)
            return "{\"error\" : \"Chat id must be specified\"}";
        if (!is_numeric($chatid))
            return "{\"error\" : \"Invalid chat id\"}";
        $messages = $this->user->getChatMessages($chatid);
        return json_encode($messages, JSON_THROW_ON_ERROR);
    }

    public function getLastChatMessages($chatid, $limit = 1) {
        if ($chatid == null)
            return "{\"error\" : \"Chat id must be specified\"}";
        if (!is_numeric($chatid))
            return "{\"error\" : \"Invalid chat id\"}";
        $messages = $this->user->getLastChatMessages($chatid, $limit);
        return json_encode($messages, JSON_THROW_ON_ERROR);
    }

    public function getUnreadMessages($fromuid) {
        $messages = $this->user->getUnreadMessages($fromuid);
        return json_encode($messages, JSON_THROW_ON_ERROR);
    }

    public function getLastActivity($uid) {
        if ($uid == null)
            return "{\"error\" : \"User id must be specified\"}";
        if (!is_numeric($uid))
            return "{\"error\" : \"Invalid user id\"}";
        $user = new User($uid);
        $time = strtotime($user->getLastActivity());
        return "{\"last_activity\" : \"".date("Y-m-d H:i:s", $time)."\"}";
    }

    private function getChatID($srcid, $dstid) {
        global $database;
        $srcuser = new User($srcid);
        $dstuser = new User($dstid);
        //Check if our pair of users have already a chat id assigned
        $stmt = $database->prepare("SELECT chat_id FROM ".TABLE_CHATS." WHERE (user_src_id = :suid AND user_dst_id = :duid) OR (user_dst_id = :suid AND user_src_id = :duid)");
        $stmt->execute([':suid' => $srcid, ':duid' => $dstid]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            return $result['chat_id'];
        } else {
            //Get the last chat_id and add one
            $stmt = $database->prepare("SELECT chat_id FROM ".TABLE_CHATS." ORDER BY chat_id DESC LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch();
            return intval($result['chat_id'])+1;
        }
    }

    public function postNewMessage() {
        global $database;
        if (!isset($_POST['msg']) || !isset($_POST['to']) || !isset($_POST['type'])) {
            return "{\"error\" : \"Missing required data\"}";
        }
        $msg = $_POST['msg'];
        $dstuser = $_POST['to'];
        if (!is_numeric($dstuser))
            return "{\"error\" : \"Invalid user id\"}";
        $type = $_POST['type'];
        //Ensure that type has the value of 'text' or 'file' only
        if ($type != "file")
            $type = "text";
        $time = time();
        $ret = false;
        try {
            $database->beginTransaction();
            //Calculate the chatid based on the two users involved
            $chatid = $this->getChatID($this->user->id, $dstuser);
            $ret = $this->user->postChatMessage($msg, $dstuser, $chatid, $type, $time);
            $database->commit();
        } catch (PDOException $e) {
                $database->rollBack();
                //throw new Exception($e->getMessage());
        }
        if ($ret) {
            return "{\"status\" : \"success\", \"chat_id\" : ".$chatid."}";
        } else {
            return "{\"status\" : \"failed\"}";
        }
    }

    public function deleteMessage($msgid) {
        if ($_SERVER['REQUEST_METHOD'] != "DELETE")
            return "{\"error\" : \"Invalid HTTP method\"}";
        if ($msgid == null)
            return "{\"error\" : \"Message id must be specified\"}";
        if (!is_numeric($msgid))
            return "{\"error\" : \"Invalid message id\"}";
        if ($this->user->deleteChatMessage($msgid)) {
            return "{\"status\" : \"success\", \"id\" : ".$msgid."}";
        } else {
            return "{\"status\" : \"failed\"}";
        }
    }

    public function deleteChat($chatid) {
        if ($_SERVER['REQUEST_METHOD'] != "DELETE")
            return "{\"error\" : \"Invalid HTTP method\"}";
        if ($chatid == null)
            return "{\"error\" : \"Chat id must be specified\"}";
        if (!is_numeric($chatid))
            return "{\"error\" : \"Invalid chat id\"}";
        if ($this->user->deleteChat($chatid)) {
            return "{\"status\" : \"success\", \"id\" : ".$chatid."}";
        } else {
            return "{\"status\" : \"failed\"}";
        }
    }

    public function setReadMessages() {
        if (!isset($_POST['id']) || !is_array($_POST['id']))
            return "{\"error\" : \"Missing required data\"}";
        $ret = true;
        foreach ($_POST['id'] as &$msgid)
            $ret = $ret && $this->user->setReadMessage($msgid);
        if ($ret) {
            return "{\"status\" : \"success\"}";
        } else {
            return "{\"status\" : \"failed\"}";
        }
    }

    public function upload($dstuser) {
        if ($dstuser == null)
            return "{\"error\" : \"Invalid user\"}";
        $uploader = new Uploader();
        $filename = $uploader->uploadPhoto();
        $_POST['msg'] = $filename;
        $_POST['to'] = $dstuser;
        $_POST['type'] = 'file';
        return $this->postNewMessage();
    }

}

//Dispatcher
//Check the parameters and call the proper methods
$method = $_GET['m'];
$method = rtrim($method, '/');
$method = explode('/', $method);

$api = new ChatAPI();

if(!empty($method[0])) {
    if (method_exists($api, $method[0])) {
        $param = null;
        $param2 = null;
        if (!empty($method[1]))
            $param = $method[1];
        if (!empty($method[2]))
            $param2 = $method[2];
        $reflection = new ReflectionMethod($api, $method[0]);
        if ($reflection->isPublic()) {
            try {
                echo $api->{$method[0]}($param, $param2);
            } catch (Exception $e) {
                echo "{\"error\" : \"".$e->getMessage()."\"}";
            }
        }
    } else {
        die("{\"error\" : \"Method not found\"}");
    }
}

?>
