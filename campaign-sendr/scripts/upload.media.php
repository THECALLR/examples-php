<?php
// upload.media.php MEDIA_FILE 'Short Name'

require 'vendor/autoload.php';

$api = new \CALLR\API\Client;
$api->setAuthCredentials(getenv("CALLR_LOGIN"), getenv("CALLR_PASS"));

$file = $argv[1];
$name = $argv[2];
$description = $argv[3];
$data = file_get_contents($file);
$base64 = base64_encode($data);

// push media file encoded in base64 to server
$uploadJobId;
try {
    $uploadJobId = $api->call('media.import_file_from_base64_async', [$base64, null]);
    echo "Pushing media file, job id: {$uploadJobId} ";
} catch(Exception $e){
    if($e->getCode() == 22){
        echo "Exception: Authentication failure\r\n";
    } else {
        echo "Exception: Media file push failed\r\n";
    }
    echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
    echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
    exit;
}

// poll for job status, could be replaced by a webhook (http://thecallr.com/docs/webhooks)
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

// create the new media file
$mediaFileId;
try {
    $mediaFileId = $api->call('media/library.create', [$name]);
    echo "Media file id: {$mediaFileId}\r\n";
} catch(Exception $e){
    echo "Exception: Media file creation failed\r\n";
    echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
    echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
    exit;
}

// import uploaded file into the newly created media file
try {
    $api->call('media/library.set_content_from_file', [$mediaFileId, $job->result->filename]);
    echo " Done!\r\nSuccessfully imported media file\r\n";
} catch(Exception $e){
    echo "Exception: Media file import failed\r\n";
    echo "Code: {$e->getCode()}, Message {$e->getMessage()} \r\n";
    echo "Trace: \r\n{$e->getTraceAsString()}\r\n";
    exit;
}

?>
