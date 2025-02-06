<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:light,sensor',
            'position' => 'required|array',
            'room_id' => 'required|exists:rooms3D,id'
        ]);
        // INSERT INTO devices (type, position, room_id) VALUES (?, ?, ?);
        return Device::create($validated);
    }

    public function updateStatus(Device $device)
    {
        $device->update([
            'status' => $device->status === 'on' ? 'off' : 'on'
        ]);

        return response()->json($device);
    }
}