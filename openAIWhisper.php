<?php
$file_path = "recording.mp3";
$token = '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';

$ch = curl_init();

$headers = array(
  'Authorization: Bearer ' . $token,
  'Content-Type: multipart/form-data'
);

$postfields = array(
  'file' => new CurlFile($file_path),
  'model' => 'whisper-1'
);

curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/audio/transcriptions');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
