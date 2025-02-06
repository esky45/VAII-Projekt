<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Room;
use App\Models\DeviceType;
use App\Models\Device3D;
use App\Models\Room3D;
class DeviceController extends Controller
{
    //SMARTHOME index
    public function index()
{
    // SELECT * FROM devices WHERE user_id = ?;
    $devices = auth()->user()->devices; // Fetch only the user's devices
    return view('smart_home.smarthome_index', compact('devices'));
}

// create tlacitko v smarthome idnex
    public function create()
    {
        return view('smart_home.create');
    }

    //ulozenie a serverova validacia
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|string',
        'status' => 'required|string',
        'brightness' => 'nullable|integer|min:0|max:100',
        'threshold' => 'nullable|integer|min:0|max:100',
    ]);

    //INSERT INTO devices (name, type, status, brightness, threshold, user_id)  VALUES (?, ?, ?, ?, ?, ?);
    auth()->user()->devices()->create($request->all());

    return redirect()->route('devices.index')->with('success', 'Device added successfully.');
}
// tlacitko edit
    public function edit(Device $device)
    {
        if ($device->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    

        return view('smart_home.edit', compact('device'));
    }

      public function toggleStatus($id)
    {
        //SELECT * FROM devices WHERE id = ?;
        $device = Device::findOrFail($id);
        //UPDATE devices SET status = ? WHERE id = ?;
        // Toggle the device status between 'on' and 'off'
        $device->status = ($device->status === 'on') ? 'off' : 'on';
        $device->save();

        // Return a response
        return response()->json(['success' => true, 'status' => $device->status]);
    }
    //slider update
    public function updateDeviceSettings(Request $request, Device $device)
    {
        // Verify device ownership first
        if ($device->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }
    
        try {
            $validated = $request->validate([
                'brightness' => 'nullable|integer|min:0|max:100',
                'threshold' => 'nullable|integer|min:0|max:100',
            ]);
            //UPDATE devices SET brightness = ?, threshold = ? WHERE id = ?;
            $device->update($validated);
    
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully'
            ]);
    
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // funkcia na udatovaci formular
public function update(Request $request, Device $device)
{
    if ($device->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    // Validate the incoming request
    $request->validate([
        'name' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]+$/',
        'type' => 'required|in:Lightbulb,Sensor,Other',
        'status' => 'required|in:On,Off',
        'brightness' => 'nullable|integer|min:0|max:100',
        'threshold' => 'nullable|integer|min:0|max:100',
    ]);

    // Update the brightness or threshold values if they exist in the request
    if ($request->has('brightness')) {
        $device->brightness = $request->input('brightness');
    }

    if ($request->has('threshold')) {
        $device->threshold = $request->input('threshold');
    }

    // Update other fields like name, type, and status
    $device->name = $request->input('name');
    $device->type = $request->input('type');
    $device->status = $request->input('status');
    
    // Save the updated device
    $device->save();
    //UPDATE devices 
    //SET name = ?, type = ?, status = ?, brightness = ?, threshold = ? 
    //WHERE id = ?;
    return redirect()->route('smart_home.index')->with('success', 'Device updated successfully.');
}

// mazanie zariadeni
    public function destroy(Request $request, $id)
    {
        if ($device->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    

        // Find the device by ID and delete it
        $device = Device::findOrFail($id);
        //DELETE FROM devices WHERE id = ?;
        $device->delete();
        return redirect()->route('smart_home.index')->with('success', 'Device deleted successfully.');
    }

// delete confirm
    public function showDeleteConfirmation($id)
    {
        $device = Device::findOrFail($id);
        return view('smart_home.delete', compact('device'));
    }

    
   // 2D mapa
   public function map()
   {
       $user = auth()->user();
   
       // Ensure rooms and devices are always collections, even if empty
      // SELECT * FROM rooms WHERE user_id = ?; 
       $rooms = $user->rooms()->get(); // Fetch rooms as Eloquent models
   
       // Fetch distinct device types from the database
       //SELECT DISTINCT type FROM devices;
       $deviceTypes = Device::select('type')->distinct()->get();
   
       // Ensure device types are not null and are always iterable
       if ($deviceTypes->isEmpty()) {
           $deviceTypes = collect([
               (object) ['type' => 'lightbulb', 'icon' => '💡'],
               (object) ['type' => 'sensor', 'icon' => '📟'],
           ]);
       }
   
       // Ensure rooms are not null and are always iterable
       if ($rooms->isEmpty()) {
           $rooms = collect([
               (object) ['name' => 'Living Room'],
               (object) ['name' => 'Bedroom'],
           ]);
       }
   
       return view('smart_home.map', compact('rooms', 'deviceTypes'));
   }
   

// 3D mapa
public function map3D()
{
    // Fetch rooms and device types lightbulb, sensor from the database or hardcoded
   // SELECT * FROM rooms;
    $rooms = Room::all(); // Fetch rooms from the database
    //SELECT * FROM device_types;
    $deviceTypes = DeviceType::all();
    
    // Return the view with rooms and device types data
    return view('smart_home.3Dmap', compact('rooms', 'deviceTypes'));
}
// ukaz mapu
public function showMap()
{
    // Fetch rooms and device types (lightbulb, sensor) from the database or hardcoded
    //SELECT * FROM rooms;
    $rooms = Room::all(); // Fetch rooms from the database
    $deviceTypes = [
        ['type' => 'lightbulb', 'icon' => '💡'],
        ['type' => 'sensor', 'icon' => '📟'],
    ];
    //SELECT * FROM device_types;
    $deviceTypes = DeviceType::all();
 
    // Return the view with rooms and device types data
    return view('smart_home.map', compact('rooms', 'deviceTypes'));
}


}

