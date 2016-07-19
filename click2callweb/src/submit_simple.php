<?php
require('../vendor/autoload.php');
$api = new \CALLR\API\Client;

if((getenv('CALLR_LOGIN') & getenv('CALLR_PASS') & getenv('CALLR_TARGET') & getenv('APP_ID')) == ""){
    echo 'Missing environment variable: CALLR_LOGIN, CALLR_PASS, CALLR_TARGET or APP_ID';
} else {
    $api->setAuthCredentials(getenv('CALLR_LOGIN'), getenv('CALLR_PASS'));
    $target_number = getenv('CALLR_TARGET');
}

$client_phone = new stdClass;
$client_phone->number = $_POST['customer_phone'];
$client_phone->timeout = 30;

$target = new stdClass;
$target->number = $target_number;
$target->timeout = 30;

$result = new stdClass;

try {
    $appId = getenv('APP_ID');
    $call_id = $api->call('clicktocall/calls.start_2', [$appId,[$client_phone],[$target], NULL]);
    $result->ok = "Your call is being connected! (ID:{$call_id})";
} catch(Exception $e){
    if($e->getCode() == 22){
        $result->error = "Exception: Authentication failure";    
    } else {
        $result->error = "Exception: Click to call failed\r\n";
    }
    $result->errorcode = "Code: {$e->getCode()}";
    $result->errormsg = "Message {$e->getMessage()}";
    $result->errortrace = "Trace: {$e->getTraceAsString()}";
} finally {
    print_r(json_encode($result));
    exit;
}
?>