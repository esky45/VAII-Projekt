<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SmartThings Home View</title>
  @vite(['resources/css/3D_style.css', 'resources/js/app.js'])
</head>
<body>
  <div id="ui">
    <h2 style="color: #00a5e5;">SmartThings Home View</h2>
    <div class="device-panel" id="device-controls"></div>
    <button id="device-placement-toggle">Toggle Device Placement</button>
    <div>
      <button onclick="setDeviceType('light')">Place Light</button>
      <button onclick="setDeviceType('sensor')">Place Sensor</button>
    </div>

    <!-- Add Room Form -->
 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
  <script src="app.js"></script>
</body>
</html>
