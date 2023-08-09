document.getElementById("startRecording").addEventListener("click", initFunction);
let isRecording = document.getElementById("isRecording");

function initFunction() {
  // Display recording
  async function getUserMedia(constraints) {
    if (navigator.mediaDevices) {
      return navigator.mediaDevices.getUserMedia(constraints);
    }
    let legacyApi =
      navigator.getUserMedia ||
      navigator.webkitGetUserMedia ||
      navigator.mozGetUserMedia ||
      navigator.msGetUserMedia;
    if (legacyApi) {
      return new Promise(function (resolve, reject) {
        legacyApi.bind(navigator)(constraints, resolve, reject);
      });
    } else {
      alert("User API not supported");
    }
  }

  isRecording.textContent = "Recording...";

  let audioChunks = [];
  let rec;

  function handlerFunction(stream) {
    rec = new MediaRecorder(stream);
    rec.start();
    rec.ondataavailable = async (e) => {
       audioChunks.push(e.data);
      // let blob = new Blob(audioChunks, { type: "audio/mp3" });
      // await sendToPhpScript(blob);
      if (rec.state == "inactive") {
        let blob = new Blob(audioChunks, { type: "audio/mp3" });
       
        console.log(blob);
        document.getElementById("audioElement").src = URL.createObjectURL(blob);
       // saveRecording(blob);
        await sendToPhpScript(blob);
   
      }
    };



  }

  function startUsingBrowserMicrophone(boolean) {
    getUserMedia({ audio: boolean })
      .then((stream) => {
        handlerFunction(stream);
        // console.log(stream);
      })
      .catch((error) => {
        console.error("Error accessing microphone:", error);
      });
  }

  function saveRecording(blob) {
    let url = URL.createObjectURL(blob);
    let a = document.createElement("a");
    a.href = url;
    a.download = "recording.mp3";
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  }

  startUsingBrowserMicrophone(true);



  function startStopInterval(){
    rec.stop();
   // rec.start();
    startUsingBrowserMicrophone(true);
  }

  // Call myFunction every 5 seconds (5000 milliseconds)
  const intervalId = setInterval(startStopInterval, 5000);
  // Stop recording
  document.getElementById("stopRecording").addEventListener("click", (e) => {
    rec.stop();
    isRecording.textContent = "Click play button to start listening";
    clearInterval(intervalId);


  });

  async function sendToPhpScript(blob) {
    const formData = new FormData();
    formData.append('audio', blob, 'recording.mp3');
    var startTime, endTime;
    startTime = performance.now();
    try {
      const response = await fetch('assemplyAPI.php', {
        method: 'POST',
        body: formData
      });
  
      if (response.ok) {
        const responseText = await response.text();
        console.log('Response from PHP script:', responseText);
        // Using substring() and indexOf()
       
        const word = "Recording:";
        
        const splitArray = responseText.split(word);
        const cutString = responseText.substring(responseText.indexOf(word));
        const result = cutString + splitArray[splitArray.length - 1];

        
        document.getElementById("Transcript").value = responseText
        //result;
        endTime = performance.now();
        var timeDiff = endTime - startTime; //in ms 
        // strip the ms 
        timeDiff /= 1000; 
        
        // get seconds 
        var seconds = Math.round(timeDiff);
        console.log(seconds + " seconds");


      } else {
        console.error('Error sending audio blob to PHP script:', response.status);
      }
    } catch (error) {
      console.error('Error sending audio blob to PHP script:', error);
    }
  }
  
  
  

  

}
