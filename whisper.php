
<?php 
// $output = array();
// exec("fasterWhisper.py", $output, $status);
// print_r($output);
// echo $status;

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  print_r($_POST);
  // exec("fasterWhisper.py", $output, $status);
  // print_r($output);
  // echo $status;

}



?>

<!DOCTYPE html>
<html lang="en">
  <html>
    <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale0" />
      <title>Voice recorder</title>
      <style>
  * {
    margin: 0p;
    padding: 0;
    box-sizing: border-box;
  }
  body {
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
  }
  button {
    padding: 7px 20px;
    cursor: pointer;
  }
 
</style>
    </head>
    <body>
    <script>
        function uploadAudio() {
        const file = document.getElementById("audioUpload").files[0]; // Get the selected file
        const fileName = file.name; // Get the name of the file
    
        const formData = new FormData(); // Create a FormData object
        formData.append("audioFile", file, fileName); // Append the file to the FormData object
    
        // Send the FormData object to the server using AJAX or fetch
        // Example using fetch and assuming the server endpoint is named "upload.php"
        fetch("processData.php", {
                    method: "POST",
                    body: formData
                    })
                    .then(response => {
                        // Handle the response from the server
                        if (response.ok) {
                        return response.text(); // Extract the response body as text
                        } else {
                        throw new Error("Response was not OK.");
                        }
                    })
                    .then(textResponse => {
                        // Display the text response in the <div> element
                        document.getElementById("Transcript").textContent = textResponse;
                    })
                    .catch(error => {
                        // Handle any errors
                        console.error("Error uploading audio file:", error);
                    });
    }
    </script>
    <input type="file" accept="audio/*" id="audioUpload" onchange=""><button id='submitAudio' onclick="uploadAudio(event)">upload</button>
      <button id="startRecording">Start</button>
      <button id="stopRecording">Stop</button>
      <br />
      <p id="isRecording">Click start button to record</p>
      <audio src="" id="audioElement" controls></audio>

      <hr>
      Transcript:
      <textarea id="Transcript" style="width:50%; height:400px"></textarea>  

    </body>
  </html>
</html>

<script defer src="app.js"></script>

