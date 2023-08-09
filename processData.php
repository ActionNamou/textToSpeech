<?php
print_r($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio'])) {
    $file = $_FILES['audio'];
    $filePath = __DIR__ . '/recording.mp3';
echo "before save";
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        echo 'File saved successfully.';

       // $command1 = "whisper  $filePath";
        //$command2 = "faterWhisper.py";


        // print("output from assmebly AI ");
        // $output = array();
        // $arg = "recording.mp3";
        // $arg = realpath($arg);
        // $rel = "C#/bin/Debug/ConsoleApp1.exe";
	    // $absPath = realpath($rel);
        // exec("$absPath $arg", $output, $status);
        // print_r($output);
        // echo $status;

        
        // $command = escapeshellcmd('/usr/custom/test.py');
        // $output = shell_exec($command);

       // exec($command2, $output);
       // print_r($output);


        // $command = escapeshellcmd('/usr/custom/test.py');
        // $output = shell_exec($command);
        // echo $output;
        

       
        // $combinedString = implode("\n",   $output);
        // echo "Recording: ".$combinedString;

        print("output from fasterWhisper ");
        $output = array();
        $arg = "recording.mp3";
        $arg = realpath($arg);
        $rel = "fasterWhisper.py";
	    $absPath = realpath($rel);
        exec("$absPath $arg", $output, $status);
        print_r($output);

    } else {
        echo 'Error saving the file.';
    }
} else {
    echo 'Invalid request.';
    $output = array();
    exec("fasterWhisper.py", $output, $status);
    print_r($output);
    echo $status;
}



?>