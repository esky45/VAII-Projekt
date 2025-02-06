<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Device</title>
    @vite(['resources/css/SH_style.css', 'resources/js/app.js'])
</head>
<body>
    <div class="form-container">
        <h1>Edit Device</h1>
        <form action="{{ route('smarthome_index.devices.update', $device->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Device Name -->
    <label for="name">Device Name:</label>
    <input type="text" name="name" id="name" value="{{ $device->name }}" required minlength="3" maxlength="100">

    <!-- Device Type -->
    <label for="type">Device Type:</label>
    <select name="type" id="type" required>
        <option value="Lightbulb" {{ $device->type === 'Lightbulb' ? 'selected' : '' }}>Lightbulb</option>
        <option value="Sensor" {{ $device->type === 'Sensor' ? 'selected' : '' }}>Sensor</option>
        <option value="Other" {{ $device->type === 'Other' ? 'selected' : '' }}>Other</option>
    </select>

    <!-- Device Status -->
    <label for="status">Device Status:</label>
    <div class="status-container">
        <select name="status" id="status" required>
            <option value="On" {{ $device->status === 'On' ? 'selected' : '' }}>On</option>
            <option value="Off" {{ $device->status === 'Off' ? 'selected' : '' }}>Off</option>
        </select>

        <!-- Display status indicator -->
        <div class="status-indicator">
            <span class="status-light {{ strtolower($device->status) === 'on' ? 'on' : 'off' }}"></span>
            {{ $device->status }}
        </div>
    </div>

    <!-- Brightness -->
    <label for="brightness">Brightness (0-100):</label>
    <input type="number" name="brightness" id="brightness" value="{{ $device->brightness }}" min="0" max="100">

    <!-- Threshold -->
    <label for="threshold">Threshold (0-100):</label>
    <input type="number" name="threshold" id="threshold" value="{{ $device->threshold }}" min="0" max="100">

    <button type="submit">Save Changes</button>
</form>

    </div>
</body>
</html>