<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/db_connect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    // Login or Register
    public function addUser($name, $email, $gcmid) {
        $response = array();

        $stmt = $this->conn->prepare("INSERT INTO user(name, email, gcmid) values(?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $gcmid);
            $result = $stmt->execute();
            $stmt->close();

            if ($result) {
                // Success
                $meta = array();
                $meta["status"] = "success";
                $meta["code"] = "200";
                $response["_meta"] = $meta;
                $response["user"] = $this->getUserByEmail($email);
            } else {
                // Error 
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "100";
                $meta["message"] = "Error al registar Usuario";
                $response["_meta"] = $meta;
            }

        return $response;
    }

    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT userid, name, email, gcmid FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows >0){
            $data = $result->fetch_assoc();
            
            $response=$data;
            
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "No existe Usuario";
            $response["_meta"] = $meta;
        }
        
        return $response;
    }

    public function getGCM($id) {
        $stmt = $this->conn->prepare("SELECT gcmid FROM user WHERE userid = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $data = $result->fetch_assoc();
        $response = $data['gcmid'];   
        
        return $response;
    }

    public function getUserById($userId) {

        $response = array();
        $stmt = $this->conn->prepare("SELECT userid, name, email, gcmid FROM user WHERE userid = ?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows >0){
            $data = $result->fetch_assoc();
            
            $user = array();
            $user["userId"] = $data['userid'];
            $user["name"] = $data['name'];
            $user["email"] = $data['email'];
            $user["gcmId"] = $data['gcmid'];
            $meta = array();
            $meta["status"] = "success";
            $meta["code"] = "200";
            $response["_meta"] = $meta;
            $response["user"] = $user;
            
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "No existe Usuario";
            $response["_meta"] = $meta;
        }
        
        return $response;

    }


    public function getUserByIdWithoutFormat($userId) {

        $response = array();
        $stmt = $this->conn->prepare("SELECT userid, name, email, gcmid FROM user WHERE userid = ?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows >0){
            $data = $result->fetch_assoc();
            
            $user = array();
            $user["userId"] = $data['userid'];
            $user["name"] = $data['name'];
            $user["email"] = $data['email'];
            $user["gcmId"] = $data['gcmid'];
            $meta = array();
            $response = $user;   
        }
        return $response;

    }

    // All Users
    public function getAllUsers() {
        $response = array();
        $stmt = $this->conn->prepare("SELECT userid, name, email, gcmid FROM user ORDER BY name DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $data = array();

        if($result->num_rows >0){

            while ($dataQuery = $result->fetch_assoc()) {
                    $tmp = array();
                    $tmp["userid"] = $dataQuery['userid'];
                    $tmp["name"] = $dataQuery['name'];
                    $tmp["email"] = $dataQuery['email'];
                    $tmp["gcmid"] = $dataQuery['gcmid'];
                    array_push($data, $tmp);
                }

            
            $meta = array();
            $meta["status"] = "success";
            $meta["code"] = "200";
            $response["_meta"] = $meta;
            $response["user"] = $data;
            
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "No existen Usuarios";
            $response["_meta"] = $meta;
        }
        return $response;
    }

    // Group of Chat
    public function getAllGroupChat() {
        $response = array();
        $stmt = $this->conn->prepare("SELECT groupid, description FROM groupchat ORDER BY description DESC");

        if($stmt->execute()){
            $result = $stmt->get_result();
            $stmt->close();

            if($result->num_rows >0){
                
                $data = array();
                $meta = array();
                $meta["status"] = "success";
                $meta["code"] = "200";
                $response["_meta"] = $meta;

                while ($dataQuery = $result->fetch_assoc()) {
                    $tmp = array();
                    $tmp["groupid"] = $dataQuery['groupid'];
                    $tmp["description"] = $dataQuery['description'];
                    array_push($data, $tmp);
                }

                $response["data"] = $data;

                
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $meta["message"] = "No existen grupos";
                $response["_meta"] = $meta;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "Error al obtener grupos";
            $response["_meta"] = $meta;
        }

        return $response;
    }

    // Group of Chat
    public function getGroupChat($userId) {
        $response = array();
        $stmt = $this->conn->prepare("SELECT DISTINCT g.groupid, g.description FROM groupchatuser gu INNER JOIN groupchat g on g.groupid = gu.groupid WHERE gu.userid = $userId  ORDER BY description DESC");

        if($stmt->execute()){
            $result = $stmt->get_result();
            $stmt->close();

            if($result->num_rows >0){
                
                $data = array();
                $meta = array();
                $meta["status"] = "success";
                $meta["code"] = "200";
                $response["_meta"] = $meta;

                while ($dataQuery = $result->fetch_assoc()) {
                    $tmp = array();
                    $tmp["groupid"] = $dataQuery['groupid'];
                    $tmp["description"] = $dataQuery['description'];
                    array_push($data, $tmp);
                }

                $response["data"] = $data;

                
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $meta["message"] = "No existen grupos";
                $response["_meta"] = $meta;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "Error al obtener grupos";
            $response["_meta"] = $meta;
        }

        return $response;
    }


    // Messages of Chat
    function getChatRoom($chatId) {
        $stmt = $this->conn->prepare("SELECT u.name, m.messageid, m.userid, m.message, m.created FROM groupchat c INNER JOIN message m ON m.groupchatid = c.groupid INNER JOIN user u ON u.userid = m.userid WHERE c.groupid = ?");
        $stmt->bind_param("i", $chatId);

        if($stmt->execute()){
            $result = $stmt->get_result();
            $stmt->close();

            if($result->num_rows >0){
                
                $data = array();
                $meta = array();
                $meta["status"] = "success";
                $meta["code"] = "200";
                $response["_meta"] = $meta;

                while ($dataQuery = $result->fetch_assoc()) {
                    $tmp = array();
                    $tmp["name"] = $dataQuery['name'];
                    $tmp["messageid"] = $dataQuery['messageid'];
                    $tmp["userid"] = $dataQuery['userid'];
                    $tmp["message"] = $dataQuery['message'];
                    $tmp["created"] = $dataQuery['created'];
                    array_push($data, $tmp);
                }

                $response["data"] = $data;

                
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $meta["message"] = "No existen mensajes";
                $response["_meta"] = $meta;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "Error al obtener mensajes";
            $response["_meta"] = $meta;
        }

        
        return $response;
    }


    // message for all group chat
    public function sendMessage($userId, $groupId, $message) {
        $response = array();
        $db = new DbHandler();

        $stmt = $this->conn->prepare("INSERT INTO message (groupchatid, userid, message) values(?, ?, ?)");
        $stmt->bind_param("iis", $groupId, $userId, $message);

        if ($result = $stmt->execute()) {

            // get the last messageId inserted
            $message_id = $this->conn->insert_id;
            $stmt = $this->conn->prepare("SELECT messageid, userid, groupchatid, message, created FROM message WHERE messageid = ?");
            $stmt->bind_param("i", $message_id);
            if ($stmt->execute()) {
                $stmt->bind_result($messageid, $userid, $groupchatid, $message, $created);
                $stmt->fetch();
                $tmp = array();
                $tmp['messageId'] = $messageid;
                $tmp['userId'] = $userid;
                $tmp['groupChatId'] = $groupchatid;
                $tmp['message'] = $message;
                $tmp['created'] = $created;
                $meta = array();
                $meta["status"] = "success";
                $meta["code"] = "200";
                $response["_meta"] = $meta;
                $response['message'] = $tmp;

                require_once __DIR__ . '/../libs/gcm/gcm.php';
                require_once __DIR__ . '/../libs/gcm/push.php';
                $gcm = new GCM();
                $push = new Push();

                // get user using userId
                //$user = getUserById($userId);

                $dataNotification = array();
                $dataNotification['user'] = $db->getUserByIdWithoutFormat($userId);
                $dataNotification['message'] = $response['message'];
                $dataNotification['groupChatId'] = $groupId;

                $push->setTitle("Soy un Título");
                $push->setData($dataNotification);

                // sending push message to a topic
                $gcm->sendToTopic('topic_' . $groupId, $push->getPush());

            }


        } else {
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "Error al enviar mensaje";
            $response["_meta"] = $meta;

        }

        return $response;
    }


    // message for user
    public function sendMessageUser($usersend, $userrecept, $message) {
        $response = array();
        $db = new DbHandler();

        $stmt = $this->conn->prepare("INSERT INTO message2 (usersend, userrecept, message) values(?, ?, ?)");
        $stmt->bind_param("iis", $usersend, $userrecept, $message);

        if ($result = $stmt->execute()) {

            // get the last messageId inserted
            $message_id = $this->conn->insert_id;
            $stmt = $this->conn->prepare("SELECT messageid, usersend, u.name, userrecept, message, m.created FROM message2 m inner join user u on m.usersend = u.userid WHERE messageid = ?");
            $stmt->bind_param("i", $message_id);
            if ($stmt->execute()) {
                $stmt->bind_result($messageid, $usersend, $name, $userrecept, $message, $created);
                $stmt->fetch();
                $tmp = array();
                $tmp['messageId'] = $messageid;
                $tmp['usersend'] = $usersend;
                $tmp['userrecept'] = $userrecept;
                $tmp['name'] = $name;
                $tmp['message'] = $message;
                $tmp['created'] = $created;
                $meta = array();
                $meta["status"] = "success";
                $meta["code"] = "200";
                $response["_meta"] = $meta;
                $response['message'] = $tmp;

                require_once __DIR__ . '/../libs/gcm/gcm.php';
                require_once __DIR__ . '/../libs/gcm/push.php';
                $gcm = new GCM();
                $push = new Push();

                $data = array();

                $data['user'] = $db->getUserByIdWithoutFormat($usersend);
                $data['message'] = $tmp;
                $data['image'] = '';
                
                $push->setTitle("Soy un titulo");
                $push->setData($data);

                // sending push message to single user
                $gcm->send($db->getGCM($userrecept), $push->getPush());


            }


        } else {
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "Error al enviar mensaje";
            $response["_meta"] = $meta;

        }

        return $response;
    }

    // get messages of user
    public function getMessagesUser($usersend, $userrecept) {
        $response = array();
        $db = new DbHandler();

            $message_id = $this->conn->insert_id;
            $stmt = $this->conn->prepare("SELECT messageid, usersend, userrecept, message, created FROM message2 where usersend in ($usersend, $userrecept) and userrecept in ($usersend, $userrecept)");
            //$stmt->bind_param("i","i","i","i", $usersend, $userrecept);

            if($stmt->execute()){
                $result = $stmt->get_result();
                $stmt->close();

                if($result->num_rows >0){
                    
                    $data = array();
                    $meta = array();
                    $meta["status"] = "success";
                    $meta["code"] = "200";
                    $response["_meta"] = $meta;

                    while ($dataQuery = $result->fetch_assoc()) {
                        $tmp = array();
                        $tmp["messageid"] = $dataQuery['messageid'];
                        $tmp["usersend"] = $dataQuery['usersend'];
                        $tmp["userrecept"] = $dataQuery['userrecept'];
                        $tmp["message"] = $dataQuery['message'];
                        $tmp["created"] = $dataQuery['created'];
                        array_push($data, $tmp);
                    }

                    $response["message"] = $data;

                    
                }else{
                    $meta = array();
                    $meta["status"] = "error";
                    $meta["code"] = "101";
                    $meta["message"] = "No existen mensajes";
                    $response["_meta"] = $meta;
                }
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "100";
                $meta["message"] = "Error al obtener mensajes";
                $response["_meta"] = $meta;
            }

        return $response;
    }



/*

    // fetching multiple users by ids
    public function getUsers($user_ids) {

        $users = array();
        if (sizeof($user_ids) > 0) {
            $query = "SELECT user_id, name, email, gcm_registration_id, created_at FROM users WHERE user_id IN (";

            foreach ($user_ids as $user_id) {
                $query .= $user_id . ',';
            }

            $query = substr($query, 0, strlen($query) - 1);
            $query .= ')';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($user = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["user_id"] = $user['user_id'];
                $tmp["name"] = $user['name'];
                $tmp["email"] = $user['email'];
                $tmp["gcm_registration_id"] = $user['gcm_registration_id'];
                $tmp["created_at"] = $user['created_at'];
                array_push($users, $tmp);
            }
        }

        return $users;
    }

    

    */

}

?>
