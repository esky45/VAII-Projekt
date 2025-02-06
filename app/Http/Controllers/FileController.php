<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        //SELECT * FROM files WHERE user_id = ?;
        $files = File::where('user_id', auth()->id())->get();
        return view('tester.tester_index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $path = $request->file('file')->storeAs('files', uniqid() . '_' . $request->file('file')->getClientOriginalName(), 'public');


        File::create([
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('files.index')->with('success', 'File uploaded successfully.');
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);

        if ($file->user_id !== auth()->id()) {
            return redirect()->route('files.index')->with('error', 'Unauthorized action.');
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return redirect()->route('files.index')->with('success', 'File deleted successfully.');
    }

    public function rename(Request $request, $id)
    {
        $request->validate(['new_name' => 'required|string|max:255']);
        
        $file = File::findOrFail($id);
        $newName = $request->new_name;
        
        // Rename the file in storage
        $newPath = 'files/' . $newName;
        Storage::disk('public')->move($file->path, $newPath);
        
        // Update the name and path in DB
        $file->name = $newName;
        $file->path = $newPath;
        $file->save();
    
        return back()->with('success', 'File renamed successfully.');
    }



}
