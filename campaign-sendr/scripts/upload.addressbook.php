<?php
/*
upload.addressbook.php ADDRESSBOOK_FILE 'Short Name' 'Description'
Please modify the variables below for your use case;
*/

$ADDRESSBOOK_PHONENUMBER_COL_INDEX = 2; // 0 based index of phone number column
$ADDRESSBOOK_COUNTRY_CODE = "FR";       // country code for addressbook.

// *****

require 'vendor/autoload.php';

$api = new \CALLR\API\Client;
$api->setAuthCredentials(getenv("CALLR_LOGIN"), getenv("CALLR_PASS"));

$file = $argv[1];
$name = $argv[2];
$description = $argv[3];
$data = file_get_contents($file);
$base64 = base64_encode($data);

// push addressbook encoded in base64 to server as a media file
$uploadJobId;
try {
    $uploadJobId = $api->call('media.import_file_from_base64_async', [$base64, null]);
    echo "Pushing addressbook, job id: {$uploadJobId} ";
} catch(Exception $e){
    if($e->getCode() == 22){
        echo "Exception: Authentication failure\r\n";
    } else {
        echo "Exception: Addressbook push failed\r\n";
    }
    echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
    echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
    exit;
}

// poll for job status, should be replaced by a webhook (http://thecallr.com/docs/webhooks)
$job;
do {
    sleep(1);
    try {
        $job = $api->call('jobs.get', [$uploadJobId]);
        echo '.';
    } catch(Exception $e){
        echo "Failed to fetch status for job id: {$uploadJobId}\r\n";
        exit;
    }
} while($job->status != "DONE");
echo " Done!\r\n";

// create a new addressbook
$addressBook;
try {
    $addressBook = $api->call('sendr/10/addressbook.create', [$name, $description]);
    echo "Addressbook id: {$addressBook->hash}\r\n";
} catch(Exception $e){
    echo "Exception: Addressbook creation failed\r\n";
    echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
    echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
    exit;
}

// import uploaded file into the newly created addressbook
try {
    $importJobId = $api->call('sendr/10/addressbook.append_file_async', [$addressBook->hash, $job->result->filename,
        $ADDRESSBOOK_PHONENUMBER_COL_INDEX, $ADDRESSBOOK_COUNTRY_CODE, null]);
    echo "Addressbook import job id: {$importJobId} ";

    // poll for job status, could be replaced by a webhook (http://thecallr.com/docs/webhooks)
    do {
        sleep(1);
        $job = $api->call('jobs.get', [$importJobId]);
        echo '.';
    } while($job->status != "DONE");
    echo " Done!\r\nSuccessfully imported {$job->result->numbers_imported} numbers\r\n";
} catch(Exception $e){
    echo "Exception: Addressbook import failed\r\n";
    echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
    echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
    exit;
}

?>