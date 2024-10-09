
<html>
<head>
  <meta charset="utf-8">
  <title>jsQR Demo</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="{{ asset('js/qrcode-scanner.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Ropa+Sans" rel="stylesheet">
  <style>
    body {
      font-family: 'Ropa Sans', sans-serif;
      color: #333;
      margin: 0;
      padding: 0;
      overflow: hidden;
    }

    #canvas {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    #loadingMessage {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 20px;
    }

    #output {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
    }

    #outputMessage {
      margin-top: 20px;
      font-size: 24px;
    }

    #outputData {
      font-size: 18px;
    }
  </style>
</head>
<body>
  <div id="loadingMessage">🎥 Unable to access video stream (please make sure you have a webcam enabled)</div>
  <canvas id="canvas" hidden></canvas>
  <div id="output" hidden>
    <div id="outputMessage">No QR code detected.</div>
    <div hidden><b>Data:</b> Response<span id="outputData"></span></div>
  </div>
  <script>
    var video = document.createElement("video");
    var canvasElement = document.getElementById("canvas");
    var canvas = canvasElement.getContext("2d");
    var loadingMessage = document.getElementById("loadingMessage");
    var outputContainer = document.getElementById("output");
    var outputMessage = document.getElementById("outputMessage");
    var outputData = document.getElementById("outputData");

    function drawLine(begin, end, color) {
      canvas.beginPath();
      canvas.moveTo(begin.x, begin.y);
      canvas.lineTo(end.x, end.y);
      canvas.lineWidth = 4;
      canvas.strokeStyle = color;
      canvas.stroke();
    }

    // Use facingMode: environment to attemt to get the front camera on phones
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
      video.srcObject = stream;
      video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
      video.play();
      requestAnimationFrame(tick);
    });

    function tick() {
      loadingMessage.innerText = "⌛ Loading video..."
      if (video.readyState === video.HAVE_ENOUGH_DATA) {
        loadingMessage.hidden = true;
        canvasElement.hidden = false;
        outputContainer.hidden = false;

        canvasElement.height = video.videoHeight;
        canvasElement.width = video.videoWidth;
        canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
        var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height, {
          inversionAttempts: "dontInvert",
        });
        if (code) {
          drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
          drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
          drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
          drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
          outputMessage.hidden = true;
          outputData.parentElement.hidden = false;
          //outputData.innerText = code.data;

          // Make AJAX request
          $.ajax({
              url: "./ajax/event",
              method: "POST",
              data: { op: "scan", qrcode: code.data, _token: '{{ csrf_token() }}' },
              success: function(result) {
                  if (result.result) {
                      // Redirect to profile page with the scanned ID
                      window.location.href = "../../profile/" + result.user_id;
                  } else {
                      // Handle error case if needed
                  }

                  // Display the result in the outputData element
                  //outputData.innerText = result.result ? "User ID: " + result.user_id : "Invalid QR Code";
              },
              error: function(xhr, status, error) {
                  // Handle error case and alert the user
                  //alert("Error occurred: " + xhr.responseText);

                  // Display a generic error message in the outputData element
                  //outputData.innerText = "Error occurred. Please try again.";
              }

          });


        } else {
          outputMessage.hidden = false;
          outputData.parentElement.hidden = true;
        }
      }
      requestAnimationFrame(tick);
    }
  </script>
</body>
</html>
