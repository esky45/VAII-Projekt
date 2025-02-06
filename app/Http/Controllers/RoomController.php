<?php
// app/Http/Controllers/RoomController.php
namespace App\Http\Controllers;
use App\Models\Room;
use Illuminate\Http\Request;
class RoomController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Create a new room for the authenticated user
        $room = Room::create([
            'name' => $validated['name'],  // Use validated data
            'user_id' => auth()->id(),     // Assign the authenticated user ID
        ]);

        // Return a JSON response with the room data
        return response()->json([
            'success' => true,
            'room' => $room,
        ]);
    }
    public function destroy(Room $room)
    {
        try {
            // Verify room ownership
            if ($room->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }
    
            $room->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}