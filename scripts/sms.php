<?php
// sms.php send <number +E.164> "<message>"
// sms.php status <sms_hash>

require 'vendor/autoload.php';

if((getenv("CALLR_LOGIN") & getenv("CALLR_PASS")) == ""){
    echo "Please set CALLR_LOGIN and CALLR_PASS environment variables with your credentials\r\n";
    exit;
}

echo "Starting script $argv[0]\r\n";

$api = new \CALLR\API\Client;
$api->setAuthCredentials(getenv("CALLR_LOGIN"), getenv("CALLR_PASS"));

$action = strtolower($argv[1]);
$to  = $smsId = $argv[2];
$text = $argv[3];

try {
    switch($action){
        case 'send':
                echo "To: {$to}\r\n";
                echo "Message: {$text}\r\n";
                $result = $api->call('sms.send', ['', $to, $text, null]);
                echo "\r\nReturned result from sms.send: {$result}\r\n";
                break;

        case 'status':
                $result = $api->call('sms.get', [$smsId]);
                print_r($result);
                break;
    }

} catch(Exception $e){
    if($e->getCode() == 22){
        echo "Exception: Authentication failure\r\n";    
    } else {
        echo "Exception: Send sms failed\r\n";
    }

    echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
    echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
} 
?>