<?php

namespace App\Http\Controllers;

use App\Models\Room3D;
use Illuminate\Http\Request;

class RoomController3D extends Controller
{
    public function index()
    {
        return Room3D::with('devices')->get();
    }



public function getRoomFromUrl(Request $request)
{
    $roomData = [
        'name' => $request->query('room-name'),
        'color' => ltrim($request->query('room-color'), '#'), // Remove '#' for storage
        'size' => [
            'x' => (int) $request->query('room-size-x'),
            'y' => (int) $request->query('room-size-y'),
            'z' => (int) $request->query('room-size-z'),
        ],
    ];

    return response()->json($roomData);
}

public function storeRoomFromUrl(Request $request)
{
    $room = Room3D::create([
        'name' => $request->query('room-name'),
        'color' => ltrim($request->query('room-color'), '#'),
        'size' => [
            'x' => (int) $request->query('room-size-x'),
            'y' => (int) $request->query('room-size-y'),
            'z' => (int) $request->query('room-size-z'),
        ],
        'position' => ['x' => 0, 'y' => 0, 'z' => 0] // Default position
    ]);

    return response()->json(['message' => 'Room stored successfully', 'room' => $room]);
}

public function storeRoomsFromJS(Request $request)
{
    $rooms = $request->input('rooms'); // Getting the rooms array from the request

    foreach ($rooms as $room) {
        Room3D::updateOrCreate(
            ['name' => $room['name']], // Prevent duplicates based on name
            [
                'color' => dechex($room['color']), // Ensure color is saved in hex string format
                'size' => json_encode($room['size']), // Ensure size is properly formatted in JSON
                'position' => json_encode($room['position']) // Ensure position is properly formatted in JSON
            ]
        );
    }

    return response()->json(['message' => 'Rooms inserted successfully']);
}




    public function updatePosition(Request $request, Room3D $room)
    {
        $validated = $request->validate([
            'position' => 'required|array',
            'position.x' => 'required|numeric',
            'position.y' => 'sometimes|numeric',
            'position.z' => 'required|numeric'
        ]);

        $room->update([
            'position' => $validated['position']
        ]);

        return response()->json($room);
    }

    public function storeInitialRooms()
    {
        $rooms = [
            [
                'name' => 'Living Room',
                'color' => '4A90E2',
                'size' => ['x' => 8, 'y' => 5, 'z' => 6],
                'position' => ['x' => 0, 'y' => 0, 'z' => 0],
            ],
            [
                'name' => 'Kitchen',
                'color' => '50E3C2',
                'size' => ['x' => 6, 'y' => 5, 'z' => 4],
                'position' => ['x' => 10, 'y' => 0, 'z' => 0],
            ],
            [
                'name' => 'Bedroom',
                'color' => 'E3507A',
                'size' => ['x' => 8, 'y' => 5, 'z' => 6],
                'position' => ['x' => 20, 'y' => 0, 'z' => 0],
            ],
            [
                'name' => 'Hall',
                'color' => '000000',
                'size' => ['x' => 30, 'y' => 5, 'z' => 6],
                'position' => ['x' => 5, 'y' => 0, 'z' => 5],
            ]
        ];

        foreach ($rooms as $room) {
            Room3D::updateOrCreate(
                ['name' => $room['name']], // Prevent duplicates
                $room
            );
        }

        return response()->json(['message' => 'Rooms initialized successfully']);
    }
}
