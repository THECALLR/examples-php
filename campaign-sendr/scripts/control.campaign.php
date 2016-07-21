<?php
// control.campaign.php <start | pause | status | dumpconfig | stop> CAMPAIGN_ID
require 'vendor/autoload.php';

echo "Starting script $argv[0]\r\n";

$api = new \CALLR\API\Client;
$api->setAuthCredentials(getenv("CALLR_LOGIN"), getenv("CALLR_PASS"));

$action = strtolower($argv[1]);
$campaignId = $argv[2];

try {
    if($action == "start"){
        $result = $api->call('sendr/10/campaign.start', [$campaignId]);
        echo "Campaign {$campaignId} has been {$result->state}\r\n";
    } elseif ($action == "stop"){
        $result = $api->call('sendr/10/campaign.stop', [$campaignId]);
        echo "Campaign {$campaignId} has been {$result->state}\r\n";
    } elseif ($action == "pause"){
        $result = $api->call('sendr/10/campaign.pause', [$campaignId]);
        echo "Campaign {$campaignId} has been {$result->state}\r\n";
    } elseif ($action == "status"){
        $result = $api->call('sendr/10/campaign.get', [$campaignId]);
        $status = $result->status;
        echo "Campaign {$campaignId} status info: \r\n";
        echo json_encode($status, JSON_PRETTY_PRINT);
    } elseif ($action == "dumpconfig"){
        $result = $api->call('sendr/10/campaign.get', [$campaignId]);
        $status = $result->status;
        echo "Campaign {$campaignId} configuration: \r\n";
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
} catch(Exception $e){
    if($e->getCode() == 22){
        echo "Exception: Authentication failure\r\n";
    } else {
        echo "Exception: {$action} campaign failed\r\n";
    }
    echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
    echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
    exit;
}

?>