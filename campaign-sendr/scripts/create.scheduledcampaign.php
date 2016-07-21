<?php
/* create.scheduledcampaign.php <addressbook_hash> <phone_number> <media_id> [media_id...]
Please modify the variables below for your use case;
*/

$CONCURRENT_CALLS_LIMIT = 10;
$CLI = "BLOCKED"; // use BLOCKED for anonymous calls or a valid +E.164 phone number (CALLR validation required)

// **********
require 'vendor/autoload.php';

$name = $argv[1];
$addrId = $argv[2];
$bridge_phone_number = $argv[3];
$media = array_splice($argv, 4);

$api = new \CALLR\API\Client;
$api->setAuthCredentials(getenv("CALLR_LOGIN"), getenv("CALLR_PASS"));

$campaign;

try {
    // get campaign object template
    $campaign = $api->call('sendr/10/campaign.get_object_template', ["VOICE_IVR"]);

    // set campaign name
    $campaign->name = $name;

    // assign addressbook to campaign
    $campaign->addressbook->hash = $addrId;

    // define campaign CLI
    $campaign->options->cli = $CLI;

    // set concurrent calls limit
    $campaign->options->ccl = $CONCURRENT_CALLS_LIMIT;

    // record calls (false by default)
    //$campaign->options->call_recording = true;


    // media files to be played, passed as arguments to the script
    $campaign->ivr->broadcast = $media;
    $campaign->ivr->prompt = $media;
    $campaign->ivr->voicemail_detect_method = "AUTO";

    // connect user to real person if they press 1
    $target = new stdClass;
    $target->number = $bridge_phone_number;
    $target->timeout = 15;
    $ivrObjects = $api->call('sendr/10/campaign.get_voice_ivr_action_objects_templates', []);

    $bridgeAction = $ivrObjects->BRIDGE;
    $bridgeAction->targets = [$target];
    
    $campaign->ivr->keys->key_1->action = "BRIDGE";
    $campaign->ivr->keys->key_1->params = $bridgeAction;

    // schedule campaign to run from Monday to Friday between 6pm and 8pm
    $scheduleDays = new stdClass();
    foreach(['mon','tue','wed','thu','fri'] as $day){
        $scheduleDays->$day = [
            (object)['action' => 'unpause', 'hour' => '18:00'],
            (object)['action' => 'pause', 'hour' => '20:00']
        ];
    }
    $campaign->schedule->enabled = true;
    $campaign->schedule->days = $scheduleDays;

    // checking campaign configuration
    $errors = $api->call('sendr/10/campaign.check', [$campaign]);
    if(count($errors) != 0){
        print_r($errors);
        throw new Exception("Campaign config check failed!");
    }

    $campaign = $api->call('sendr/10/campaign.save', [$campaign]);
    echo "Campaign created ID: {$campaign->hash}\r\n";

} catch(Exception $e){
        if($e->getCode() == 22){
            echo "Exception: Authentication failure\r\n";
        } else {
            echo "Exception: Campaign creation failed\r\n";
        }
        echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
        echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
        exit;
}

?>