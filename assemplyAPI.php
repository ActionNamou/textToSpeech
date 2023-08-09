<?php

// Welcome to AssemblyAI! Get started with the API by transcribing
// a file using PHP.
//
// In this example, we'll transcribe a local file. Get started by
// downloading the snippet, then update the 'filename' variable
// to point to the local path of the file you want to upload and
// use the API to transcribe.
//
// IMPORTANT: Update line 101 to point to a local file.
//
// Have fun!

// Function to upload a local file to the AssemblyAI API
function upload_file($api_token, $path) {
    $url = 'https://api.assemblyai.com/v2/upload';
    $data = file_get_contents($path);

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-type: application/octet-stream\r\nAuthorization: $api_token",
            'content' => $data
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($http_response_header[0] == 'HTTP/1.1 200 OK') {
        $json = json_decode($response, true);
        return $json['upload_url'];
    } else {
        echo "Error: " . $http_response_header[0] . " - $response";
        return null;
    }
}

// Function to create a transcript using AssemblyAI API
function create_transcript($api_token, $audio_url) {
    $url = "https://api.assemblyai.com/v2/transcript";

    $headers = array(
        "authorization: " . $api_token,
        "content-type: application/json"
    );

    $data = array(
        "audio_url" => $audio_url
    );

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);

    $transcript_id = $response['id'];

    $polling_endpoint = "https://api.assemblyai.com/v2/transcript/" . $transcript_id;

    while (true) {
        $polling_response = curl_init($polling_endpoint);

        curl_setopt($polling_response, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($polling_response, CURLOPT_RETURNTRANSFER, true);

        $transcription_result = json_decode(curl_exec($polling_response), true);

        if ($transcription_result['status'] === "completed") {
            return $transcription_result;
        } else if ($transcription_result['status'] === "error") {
            throw new Exception("Transcription failed: " . $transcription_result['error']);
        } else {
            sleep(3);
        }
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio'])) {
    $file = $_FILES['audio'];
    $filePath = __DIR__ . '/recording.mp3';

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        echo 'File saved successfully.';
// Upload a file and create a transcript using the AssemblyAI API
    try {
        // Your API token is already set in this variable
        $api_token = "4a920130b6224e369e93fd1ef57c7794";

        // -----------------------------------------------------------------------------
        // Update the file path here, pointing to a local audio or video file.
        // If you don't have one, download a sample file: https://storage.googleapis.com/aai-web-samples/espn-bears.m4a
        // You may also remove the upload step and update the 'audio_url' parameter in the
        // 'create_transcript' function to point to a remote audio or video file.
        // -----------------------------------------------------------------------------
        //$path = "./recording";
        $upload_url = upload_file($api_token, $filePath);

        $transcript = create_transcript($api_token, $upload_url);
        echo $transcript['text'];
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
    } else {
        echo 'Error saving the file.';
    }
} else {
    //echo 'Invalid request.';
    $output = array();
    exec("fasterWhisper.py", $output, $status);
    print_r($output);
    echo $status;
}



?>