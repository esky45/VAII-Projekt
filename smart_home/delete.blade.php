<!-- resources/views/smart_home/delete.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Device</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container text-center">
        <h1>Delete Device</h1>
        <p>Are you sure you want to delete the device "{{ $device->name }}"?</p>

        <!-- Form to submit DELETE request -->
        <form action="{{ route('devices.destroy', $device->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="button danger">Yes, Delete</button>
            <a href="{{ route('smart_home.index') }}" class="button">Cancel</a>
        </form>
    </div>
</body>
</html>
