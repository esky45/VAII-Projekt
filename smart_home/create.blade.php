<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Device</title>
    @vite(['resources/css/SH_style.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/SH_style.css') }}">
</head>
<body>
    <h1>Add New Smart Device</h1>
    <form action="{{ route('devices.store') }}" method="POST" id="deviceForm">
        @csrf <!-- CSRF token for security -->
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required minlength="3" maxlength="100"
               pattern="^[a-zA-Z0-9 ]+$" title="Only alphanumeric characters and spaces allowed"><br>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="Lightbulb">Lightbulb</option>
            <option value="Sensor">Sensor</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="On">On</option>
            <option value="Off">Off</option>
        </select><br>

        <label for="brightness">Brightness (if applicable):</label>
        <input type="number" name="brightness" id="brightness" min="0" max="100"><br>

        <label for="threshold">Threshold (if applicable):</label>
        <input type="number" name="threshold" id="threshold" min="0" max="100"><br>

        <button type="submit">Save Device</button>
    </form>

 
</body>
</html>