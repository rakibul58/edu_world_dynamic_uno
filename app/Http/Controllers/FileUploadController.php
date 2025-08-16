<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileUploadController extends Controller
{

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,ico|max:5120', // 5MB max
            'type' => 'required|in:logo,favicon,image,hero,gallery'
        ]);

        $file = $request->file('file');
        $type = $request->input('type');
        
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = "uploads/{$type}";
        
        // Create directory if it doesn't exist
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Initialize ImageManager with GD driver
        $manager = new ImageManager(new Driver());

        // Handle different file types
        switch ($type) {
            case 'logo':
                $image = $manager->read($file);
                $image->scaleDown(width: 200);
                $image->save(storage_path("app/public/{$path}/{$filename}"));
                break;
                
            case 'favicon':
                $image = $manager->read($file);
                $image->resize(32, 32);
                $image->save(storage_path("app/public/{$path}/{$filename}"));
                break;
                
            case 'hero':
                $image = $manager->read($file);
                $image->scaleDown(width: 1920, height: 1080);
                $image->save(storage_path("app/public/{$path}/{$filename}"));
                break;
                
            default:
                $file->storeAs($path, $filename, 'public');
                break;
        }

        $url = asset("storage/{$path}/{$filename}");

        return response()->json([
            'success' => true,
            'url' => $url,
            'filename' => $filename,
            'path' => "{$path}/{$filename}"
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $path = $request->input('path');
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => 'File not found'], 404);
    }
}