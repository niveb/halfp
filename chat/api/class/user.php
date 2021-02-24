<?php
require 'config.php';
require 'class/chat.php';
require 'class/message.php';
require 'class/cipher.php';

class User {
    public $id;
    public $username;
    public $profile_image;

    function __construct($id) {
        global $database;
        if (is_numeric($id)) { //Check that the user id is valid
            //Retrieve user information from DB
            $stmt = $database->prepare("SELECT * FROM ".TABLE_USERS." WHERE id = :id");
            $stmt->execute([':id' => $id]);
            if ($stmt->rowCount() == 0)
                throw new Exception("Invalid user id");
            $result = $stmt->fetch();
            $this->id = $id;
            $this->username = $result['username'];
            $this->profile_image = $result['profile_image'];
        } elseif (is_string($id) && (strlen($id) > 3)) { //or check that this is a valid username
            $stmt = $database->prepare("SELECT * FROM ".TABLE_USERS." WHERE username = :uname");
            $stmt->execute([':uname' => $id]);
            if ($stmt->rowCount() == 0)
                throw new Exception("Invalid user id");
            $result = $stmt->fetch();
            $this->id = $result['id'];
            $this->username = $id;
            $this->profile_image = $result['profile_image'];
        } else {
            throw new Exception("Invalid user id");
        }
    }
    function didIBlock($dstuid) {
        global $database;
        $stmt = $database->prepare("SELECT * FROM ".TABLE_BLOCKS." WHERE (user_dst_id = :duid AND user_src_id = :suid)");
        $stmt->execute([':suid' => $this->id, ':duid' => $dstuid]);
        return ($stmt->rowCount() > 0);
    }

    private function isBlocked($dstuid) {
        global $database;
        $stmt = $database->prepare("SELECT * FROM ".TABLE_BLOCKS." WHERE (user_dst_id = :suid AND user_src_id = :duid) OR (user_dst_id = :duid AND user_src_id = :suid)");
        $stmt->execute([':suid' => $this->id, ':duid' => $dstuid]);
        return ($stmt->rowCount() > 0);
    }

    function blockOtherUser($dstuid) {
        global $database;
        //Check if we have already blocked that user
        $stmt = $database->prepare("SELECT * FROM ".TABLE_BLOCKS." WHERE user_dst_id = :duid AND user_src_id = :suid");
        $stmt->execute([':suid' => $this->id, ':duid' => $dstuid]);
        if ($stmt->rowCount() == 0) {
            $stmt = $database->prepare("INSERT INTO ".TABLE_BLOCKS." (user_src_id, user_dst_id) VALUES (:suid, :duid)");
            $stmt->execute([':suid' => $this->id, ':duid' => $dstuid]);
        }
        return true;
    }

    function UnblockOtherUser($dstuid) {
        global $database;
        //Check if we have already blocked that user
        $stmt = $database->prepare("DELETE FROM ".TABLE_BLOCKS." WHERE user_dst_id = :duid AND user_src_id = :suid");
        $stmt->execute([':suid' => $this->id, ':duid' => $dstuid]);
        return true;
    }

    function getChatCount() {
        global $database;
        $stmt = $database->prepare("SELECT COUNT( DISTINCT user_dst_id) as chat_count FROM ".TABLE_CHATS." WHERE user_src_id = :id");
        $stmt->execute([':id' => $this->id]);
        $result = $stmt->fetch();
        return $result['chat_count'];
    }

    function getUnreadMessages($fromuid) {
        global $database;
        $stmt = null;
        if ($fromuid == null) {
            $stmt = $database->prepare("SELECT * FROM ".TABLE_CHATS." WHERE is_read = 0 AND user_dst_id = :uid ORDER BY time DESC");
            $stmt->execute([':uid' => $this->id]);
        } else {
            $stmt = $database->prepare("SELECT * FROM ".TABLE_CHATS." WHERE is_read = 0 AND user_dst_id = :uid AND user_src_id = :fromuid ORDER BY time DESC");
            $stmt->execute([':uid' => $this->id, ':fromuid' => $fromuid]);
        }
        $res = $stmt->fetchAll();
        return $this->fetchMessages($res);
    }

    function getChatRecipients() {
        global $database;
        $stmt = $database->prepare("SELECT DISTINCT user_dst_id,chat_id FROM ".TABLE_CHATS." WHERE user_src_id = :id UNION SELECT DISTINCT user_src_id,chat_id FROM ".TABLE_CHATS." WHERE user_dst_id = :id");
        $stmt->execute([':id' => $this->id]);
        $result = $stmt->fetchAll();
        $dstusers = array();
        foreach($result as &$res) {
            $chat = new Chat();
            $chat->chat_id = $res['chat_id'];
            $chat->users = new User($res['user_dst_id']);
            array_push($dstusers, $chat);
        }
        return $dstusers;
    }

    private function fetchMessages($result) {
        $messages = array();
        foreach($result as &$res) {
            $message = new Message();
            $message->content = Cipher::decrypt($res['message']);
            if ($message->content == ":init:") //Skip the special initialization message
                continue;
            $message->id = $res['id'];
            $message->chat_id = $res['chat_id'];
            $message->dstuser = new User($res['user_dst_id']);
            $message->srcuser = new User($res['user_src_id']);
            $message->type = $res['message_type'];
            $message->time = $res['time'];
            $message->read = $res['is_read'];
            array_push($messages, $message);
        }
        return $messages;
    }

    function getChatMessages($cid) {
        global $database;
        $stmt = $database->prepare("SELECT * FROM ".TABLE_CHATS." WHERE chat_id = :cid AND (user_src_id = :uid OR user_dst_id = :uid) ORDER BY time ASC");
        $stmt->execute([':cid' => $cid, ':uid' => $this->id]);
        $result = $stmt->fetchAll();
        return $this->fetchMessages($result);
    }

    function getLastChatMessages($cid, $limit) {
        global $database;
        $stmt = $database->prepare("SELECT * FROM ".TABLE_CHATS." WHERE chat_id = :cid AND (user_src_id = :uid OR user_dst_id = :uid) ORDER BY time DESC LIMIT ".(int)($limit));
        $stmt->execute([':cid' => $cid, ':uid' => $this->id]);
        $res = $stmt->fetchAll();
        return $this->fetchMessages($res);
    }

    function getLastActivity() {
        global $database;
        $stmt = $database->prepare("SELECT time FROM ".TABLE_CHATS." WHERE user_src_id = :uid ORDER BY time DESC LIMIT 1");
        $stmt->execute([':uid' => $this->id]);
        $res = $stmt->fetch();
        return $res['time'];
    }

    function exists($uid) {
        //Check if user id is valid
        global $database;
        $stmt = $database->prepare("SELECT * FROM ".TABLE_USERS." WHERE id = :uid");
        $stmt->execute([':uid' => $uid]);
        return ($stmt->rowCount() > 0);
    }

    private function checkMessagePolicy($msg) {
        //Check the validity of the message
        if (strlen($msg) > 1000)
            throw new Exception("Message too long");
        $forbidden_words = explode(",",FORBIDDEN_WORDLIST);
        $lmsg = strtolower($msg);
        //The following if checks that we didn't explode an empty forbidden wordlist
        if ($forbidden_words[0] != "") {
            foreach ($forbidden_words as &$word) {
                if (strpos($lmsg, $word) !== false)
                    throw new Exception("The message contains the forbidden word '".$word."'");
            }
        }
    }

    function postChatMessage($msg, $dstuser, $chatid, $type, $time) {
        global $database;
        if (!$this->exists($dstuser))
            throw new Exception("Invalid destination user");
        if ($this->isBlocked($dstuser))
            throw new Exception("The chat is blocked");
        $msg = utf8_encode(htmlentities($msg));
        $this->checkMessagePolicy($msg);
        $isread = 0;
        if ($msg == ":init:")
            $isread = 1;
        $stmt = $database->prepare("INSERT INTO ".TABLE_CHATS." (chat_id,user_src_id,user_dst_id,message,is_read,message_type,time) VALUES (:cid,:suid,:duid,:msg,:read,:type,FROM_UNIXTIME(:time))");
        $stmt->execute([':cid' => $chatid,':suid' => $this->id, ':duid' => $dstuser, ':msg' => Cipher::encrypt($msg), ':read'=> $isread,':type' => $type, ':time' => $time]);
        return true;
    }

    function deleteChatMessage($msgid) {
        global $database;
        $stmt = $database->prepare("SELECT * FROM ".TABLE_CHATS." WHERE id = :mid AND user_src_id = :id");
        $stmt->execute([':mid' => $chatid, ':id' => $this->id]);
        $message = $stmt->fetch();
        //Delete uploaded files
        if ($message['message_type'] == 'file')
            unlink(UPLOAD_DIR."/".Cipher::decrypt($message['message']));
        //Check that the message exists and belongs to the current user
        $stmt = $database->prepare("DELETE FROM ".TABLE_CHATS." WHERE id = :mid AND user_src_id = :id");
        $stmt->execute([':mid' => $msgid, ':id' => $this->id]);
        return true;
    }

    function deleteChat($chatid) {
        global $database;
        //The sql query checks that the chat exists and belongs to the current user
        $stmt = $database->prepare("SELECT * FROM ".TABLE_CHATS." WHERE chat_id = :cid AND (user_src_id = :id OR user_dst_id = :id)");
        $stmt->execute([':cid' => $chatid, ':id' => $this->id]);
        $result = $stmt->fetchAll();
        //Delete uploaded files
        foreach ($result as &$message) {
            if ($message['message_type'] == 'file')
                unlink(UPLOAD_DIR."/".Cipher::decrypt($message['message']));
        }
        $stmt = $database->prepare("DELETE FROM ".TABLE_CHATS." WHERE chat_id = :cid AND (user_src_id = :id OR user_dst_id = :id)");
        $stmt->execute([':cid' => $chatid, ':id' => $this->id]);
        return true;
    }

    function setReadMessage($msgid) {
        global $database;
        $stmt = $database->prepare("UPDATE ".TABLE_CHATS." SET is_read = 1 WHERE id = :mid AND user_dst_id = :id");
        $stmt->execute([':mid' => $msgid, ':id' => $this->id]);
        return true;
    }
}

?>
