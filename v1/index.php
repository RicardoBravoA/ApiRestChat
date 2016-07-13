<?php

error_reporting(-1);
ini_set('display_errors', 'On');

require_once '../include/db_handler.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User login
$app->post('/user/login', function() use ($app) {
    
    verifyRequiredParams(array('name', 'email', 'gcmId'));

    $name = $app->request->post('name');
    $email = $app->request->post('email');
    $gcmid = $app->request->post('gcmId');

    $db = new DbHandler();
    $response = $db->addUser($name, $email, $gcmid);

    echoResponse(200, $response);
});

// User by Id
$app->get('/user/:id', function($userId) use ($app) {
    global $app;

    $db = new DbHandler();
    $response = $db->getUserById($userId);
    echoResponse(200, $response);
});

// All Users
$app->get('/user/', function() use ($app) {
    global $app;

    $db = new DbHandler();
    $response = $db->getAllUsers();
    echoResponse(200, $response);
});

//Group of Chat
$app->get('/group', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->getAllGroupChat();
    echoResponse(200, $response);
});

//Chat Group of User
$app->post('/group/user', function() use ($app) {
    global $app;
    $db = new DbHandler();
    verifyRequiredParams(array('userId'));

    $userId = $app->request->post('userId');

    $response = $db->getGroupChat($userId);
    echoResponse(200, $response);
});

// All messages of chat
$app->get('/group/:id', function($groupId) {
    global $app;
    $db = new DbHandler();

    $response = $db->getChatRoom($groupId);
    echoResponse(200, $response);
});


// Message for all group chat
$app->post('/group/:id/message', function($groupId) {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('userId', 'message'));

    $userId = $app->request->post('userId');
    $message = $app->request->post('message');

    $response = $db->sendMessage($userId, $groupId, $message);
    echoResponse(200, $response);
});

// Message for user
$app->post('/message', function() use ($app) {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('userSend', 'userRecept', 'message'));

    $userSend = $app->request->post('userSend');
    $userRecept = $app->request->post('userRecept');
    $message = $app->request->post('message');

    $response = $db->sendMessageUser($userSend, $userRecept, $message);
    echoResponse(200, $response);
});

// All Message from user
$app->post('/message/user', function() use ($app) {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('userSend', 'userRecept'));

    $userSend = $app->request->post('userSend');
    $userRecept = $app->request->post('userRecept');

    $response = $db->getMessagesUser($userSend, $userRecept);
    echoResponse(200, $response);
});



function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();

    $app->status($status_code);
    $app->contentType('application/json');

    echo json_encode($response);
}

function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;

    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    //error format of key null or empty
    if ($error) {
        $response = array();
        $app = \Slim\Slim::getInstance();

        $meta = array();
        $meta["status"] = "error";
        $meta["code"] = "1000";
        $meta["message"] = 'Campo requerido ' . substr($error_fields, 0, -2) . ', se encuentra vacio o nulo';
        $response["_meta"] = $meta;
        echoResponse(400, $response);
        $app->stop();
    }
}
















/**
 * Sending push notification to a single user
 * We use user's gcm registration id to send the message
 * * */
$app->post('/users/:id/message', function($to_user_id) {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('message'));

    $from_user_id = $app->request->post('user_id');
    $message = $app->request->post('message');

    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
    $gcm = new GCM();
    $push = new Push();

    $fromuser = $db->getUser($from_user_id);
    $user = $db->getUser($to_user_id);
    
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $fromuser;
    $data['message'] = $msg;
    $data['image'] = '';

    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending push message to single user
    $gcm->send($user['gcm_registration_id'], $push->getPush());

    $response['user'] = $user;
    $response['error'] = false;


    echoResponse(200, $response);
});

$app->post('/users/push_test', function() {
    global $app;

    verifyRequiredParams(array('message', 'api_key', 'token'));

    $message = $app->request->post('message');
    $apiKey = $app->request->post('api_key');
    $token = $app->request->post('token');
    $image = $app->request->post('include_image');

    $data = array();
    $data['title'] = 'Google Cloud Messaging';
    $data['message'] = $message;
    if ($image == 'true') {
        $data['image'] = 'http://api.androidhive.info/gcm/panda.jpg';
    } else {
        $data['image'] = '';
    }
    $data['created_at'] = date('Y-m-d G:i:s');

    $fields = array(
        'to' => $token,
        'data' => $data,
    );

    // Set POST variables
    $url = 'https://gcm-http.googleapis.com/gcm/send';

    $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $response = array();

    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        $response['error'] = TRUE;
        $response['message'] = 'Unable to send test push notification';
        echoResponse(200, $response);
        exit;
    }

    // Close connection
    curl_close($ch);

    $response['error'] = FALSE;
    $response['message'] = 'Test push message sent successfully!';

    echoResponse(200, $response);
});


/**
 * Sending push notification to multiple users
 * We use gcm registration ids to send notification message
 * At max you can send message to 1000 recipients
 * * */
$app->post('/users/message', function() use ($app) {

    $response = array();
    verifyRequiredParams(array('user_id', 'to', 'message'));

    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';

    $db = new DbHandler();

    $user_id = $app->request->post('user_id');
    $to_user_ids = array_filter(explode(',', $app->request->post('to')));
    $message = $app->request->post('message');

    $user = $db->getUser($user_id);
    $users = $db->getUsers($to_user_ids);

    $registration_ids = array();

    // preparing gcm registration ids array
    foreach ($users as $u) {
        array_push($registration_ids, $u['gcm_registration_id']);
    }

    // insert messages in db
    // send push to multiple users
    $gcm = new GCM();
    $push = new Push();

    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = '';

    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending push message to multiple users
    $gcm->sendMultiple($registration_ids, $push->getPush());

    $response['error'] = false;

    echoResponse(200, $response);
});

$app->post('/users/send_to_all', function() use ($app) {

    $response = array();
    verifyRequiredParams(array('user_id', 'message'));

    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';

    $db = new DbHandler();

    $user_id = $app->request->post('user_id');
    $message = $app->request->post('message');

    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
    $gcm = new GCM();
    $push = new Push();

    // get the user using userid
    $user = $db->getUser($user_id);

    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = 'http://api.androidhive.info/gcm/panda.jpg';

    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending message to topic `global`
    // On the device every user should subscribe to `global` topic
    $gcm->sendToTopic('global', $push->getPush());

    $response['user'] = $user;
    $response['error'] = false;

    echoResponse(200, $response);
});



$app->run();
?>