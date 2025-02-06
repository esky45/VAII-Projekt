<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Home Devices Dashboard</title>
    @vite(['resources/css/SH_style.css', 'resources/js/app.js'])

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <header class="text-center">
            <h1>Smart Devices Dashboard</h1>
            <p style="color: green">Welcome {{ Auth::user()->name }}</p>
            <p>Manage your smart home devices seamlessly.</p>
            
            <a href="{{ route('devices.create') }}" class="button mt-20">+ Add New Device</a>
            <br/>
            <a href="{{ route('devices.map') }}" class="button mt-20">Device Map</a>
            <a href="{{ route('devices.map3D') }}" class="button mt-20">3D Device Map</a>
        </header>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Device Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Brightness</th>
                        <th>Threshold</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($devices->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">No devices found. Add a new device to get started.</td>
                        </tr>
                    @else
                        @foreach($devices as $device)
                            @if($device->user_id === auth()->id()) 
                                <tr>
                                    <td>{{ $device->name }}</td>
                                    <td>{{ $device->type }}</td>
                                    <td>
                                        <div class="status-indicator">
                                            <span class="status-light {{ strtolower($device->status) === 'on' ? 'on' : 'off' }}"></span>
                                            {{ $device->status }}
                                        </div>
                                    </td>
                                    <td>
                                        @if(strtolower($device->type) === 'lightbulb')
                                            <div class="slider-container">
                                                <input type="range" id="brightness-{{ $device->id }}" class="brightness-slider" name="brightness" min="0" max="100" value="{{ $device->brightness }}" step="1">
                                                <span id="brightness-value-{{ $device->id }}" class="slider-value">{{ $device->brightness }}%</span>
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if(strtolower($device->type) === 'sensor')
                                            <div class="slider-container">
                                                <input type="range" id="threshold-{{ $device->id }}" class="threshold-slider" name="threshold" min="0" max="100" value="{{ $device->threshold }}" step="1">
                                                <span id="threshold-value-{{ $device->id }}" class="slider-value">{{ $device->threshold }}%</span>
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('devices.edit', $device->id) }}" class="button">Edit</a>
                                        <form action="{{ route('devices.destroy', $device->id) }}" method="POST" class="delete-form" onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    
</body>
</html>
