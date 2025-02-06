<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Home Map</title>
    <p style="color: green">Welcome {{ Auth::user()->name }}</p>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <script>
    const createRoomUrl = @json(route('createRoom'));
    </script>
@vite(['resources/css/SH_style.css', 'resources/js/2D.js'])
@vite(['resources/css/2D_style.css', 'resources/js/2D.js'])
  
    <div style="margin-top: 20px;">
        <button id="show-create-room-form" style="padding: 5px 10px; background-color: var(--accent-color); color: white;">Show Create Room Form</button>
        <form id="create-room-form" style="display: none; margin-top: 10px;">
            <label for="room-name" style="color: white;">New Room Name:</label>
            <input type="text" id="room-name" name="room-name" required style="padding: 5px; margin-right: 10px;"/>
            <button type="submit" style="padding: 5px 15px; background-color: var(--accent-color); color: white;">Create Room</button>
        </form>
    </div>

    
</head>
<body>
    <div id="device-menu">
        @foreach($deviceTypes as $deviceType)
            <div class="device {{ $deviceType->type }}" id="{{ $deviceType->type }}" draggable="true">
                {{ $deviceType->icon }}
            </div>
        @endforeach
    </div>

    <div id="device-menu">
        <!-- Example device icons -->
        <div class="device lightbulb" id="lightbulb" draggable="true">
            💡
        </div>
        <div class="device sensor" id="sensor" draggable="true">
            📟
        </div>
    </div>


    <div id="house-layout">
    @foreach($rooms as $room)
        <div class="room" id="room-{{ $room->id }}" data-room-id="{{ $room->id }}">
            <h2>{{ $room->name }}</h2>
        <!--     <button class="delete-room-btn" data-room-id="{{ $room->id }}">×</button>-->
        </div>
    @endforeach
</div>
  
</body>
</html>
