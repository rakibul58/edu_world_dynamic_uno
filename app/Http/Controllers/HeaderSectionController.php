<?php

namespace App\Http\Controllers;

use App\Models\HeaderSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HeaderSectionController extends Controller
{
    public function index()
    {
        $header = HeaderSection::getActiveHeader();
        
        if (!$header) {
            return response()->json([
                'success' => false,
                'message' => 'No active header section found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $header
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo_type' => 'required|in:image,text',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_text' => 'nullable|string|max:255',
            'logo_styles' => 'nullable|array',
            'logo_link' => 'nullable|array',
            'logo_responsive' => 'nullable|array',
            'logo_tagline' => 'nullable|string|max:255',
            'tagline_styles' => 'nullable|array',
            'buttons' => 'nullable|array',
            'buttons.*.text' => 'required|string|max:255',
            'buttons.*.url' => 'required|string|max:255',
            'buttons.*.style' => 'required|string|max:255',
            'buttons.*.type' => 'required|string|max:255',
            'buttons.*.custom_styles' => 'nullable|array',
            'nav_styles' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Deactivate any existing active headers
        HeaderSection::where('is_active', true)->update(['is_active' => false]);

        $headerData = $request->only([
            'logo_type', 
            'logo_text', 
            'logo_styles',
            'logo_link',
            'logo_responsive',
            'logo_tagline',
            'tagline_styles',
            'buttons', 
            'nav_styles'
        ]);
        
        $headerData['is_active'] = true;

        // Handle logo image upload
        if ($request->hasFile('logo_image') && $request->logo_type === 'image') {
            $path = $request->file('logo_image')->store('public/header');
            $headerData['logo_image_path'] = Storage::url($path);
        }

        $header = HeaderSection::create($headerData);

        return response()->json([
            'success' => true,
            'message' => 'Header section created successfully',
            'data' => $header
        ], 201);
    }

    public function update(Request $request, HeaderSection $headerSection)
    {
        $validator = Validator::make($request->all(), [
            'logo_type' => 'sometimes|required|in:image,text',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_text' => 'nullable|string|max:255',
            'logo_styles' => 'nullable|array',
            'logo_link' => 'nullable|array',
            'logo_responsive' => 'nullable|array',
            'logo_tagline' => 'nullable|string|max:255',
            'tagline_styles' => 'nullable|array',
            'buttons' => 'nullable|array',
            'buttons.*.text' => 'required|string|max:255',
            'buttons.*.url' => 'required|string|max:255',
            'buttons.*.style' => 'required|string|max:255',
            'buttons.*.type' => 'required|string|max:255',
            'buttons.*.custom_styles' => 'nullable|array',
            'nav_styles' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $headerData = $request->only([
            'logo_type', 
            'logo_text', 
            'logo_styles',
            'logo_link',
            'logo_responsive',
            'logo_tagline',
            'tagline_styles',
            'buttons', 
            'nav_styles'
        ]);

        // Handle logo image upload
        if ($request->hasFile('logo_image') && $request->logo_type === 'image') {
            // Delete old image if exists
            if ($headerSection->logo_image_path) {
                $oldImage = str_replace('/storage', 'public', $headerSection->logo_image_path);
                Storage::delete($oldImage);
            }
            
            $path = $request->file('logo_image')->store('public/header');
            $headerData['logo_image_path'] = Storage::url($path);
        }

        $headerSection->update($headerData);

        return response()->json([
            'success' => true,
            'message' => 'Header section updated successfully',
            'data' => $headerSection
        ]);
    }

    public function destroy(HeaderSection $headerSection)
    {
        // Delete associated image
        if ($headerSection->logo_image_path) {
            $imagePath = str_replace('/storage', 'public', $headerSection->logo_image_path);
            Storage::delete($imagePath);
        }

        $headerSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Header section deleted successfully'
        ]);
    }
}