<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FooterSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FooterSectionController extends Controller
{
    public function index()
    {
        $sections = FooterSection::latest()->get();
        return response()->json($sections);
    }

    public function store(Request $request)
    {
        // Convert JSON strings back to arrays if needed
        $data = $request->all();
        $this->convertJsonArrays($data);

        $validator = Validator::make($data, [
            'description' => 'required|string',
            'tagline' => 'required|string',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'nullable|string',
            'social_links.*.url' => 'nullable|string',
            'social_links.*.icon' => 'nullable|string',
            'quick_links' => 'nullable|array',
            'quick_links.*.text' => 'nullable|string',
            'quick_links.*.url' => 'nullable|string',
            'programs' => 'nullable|array',
            'programs.*.text' => 'nullable|string',
            'programs.*.url' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'working_hours' => 'required|string',
            'background_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'accent_color' => 'nullable|string',
            'link_color' => 'nullable|string',
            'link_hover_color' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $this->handleLogoUpload($request->file('logo'), $data);
            $data['logo_path'] = $logoPath;
        }

        // Remove logo from data since it's not a database field
        unset($data['logo']);

        $footerSection = FooterSection::create($data);

        return response()->json([
            'message' => 'Footer section created successfully',
            'data' => $footerSection
        ], 201);
    }

    public function show(FooterSection $footerSection)
    {
        return response()->json($footerSection);
    }

    public function update(Request $request, FooterSection $footerSection)
    {
        // Convert JSON strings back to arrays if needed
        $data = $request->all();
        $this->convertJsonArrays($data);

        $validator = Validator::make($data, [
            'description' => 'required|string',
            'tagline' => 'required|string',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'nullable|string',
            'social_links.*.url' => 'nullable|string',
            'social_links.*.icon' => 'nullable|string',
            'quick_links' => 'nullable|array',
            'quick_links.*.text' => 'nullable|string',
            'quick_links.*.url' => 'nullable|string',
            'programs' => 'nullable|array',
            'programs.*.text' => 'nullable|string',
            'programs.*.url' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'working_hours' => 'required|string',
            'background_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'accent_color' => 'nullable|string',
            'link_color' => 'nullable|string',
            'link_hover_color' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($footerSection->logo_path && file_exists(public_path('uploads/image/' . $footerSection->logo_path))) {
                unlink(public_path('uploads/image/' . $footerSection->logo_path));
            }

            $logoPath = $this->handleLogoUpload($request->file('logo'), $data);
            $data['logo_path'] = $logoPath;
        }

        // Remove logo from data since it's not a database field
        unset($data['logo']);

        $footerSection->update($data);

        return response()->json([
            'message' => 'Footer section updated successfully',
            'data' => $footerSection->fresh()
        ]);
    }

    public function destroy(FooterSection $footerSection)
    {
        // Don't allow deletion of active footer
        if ($footerSection->is_active) {
            return response()->json([
                'message' => 'Cannot delete active footer section'
            ], 422);
        }

        // Delete associated logo file
        if ($footerSection->logo_path && file_exists(public_path('uploads/image/' . $footerSection->logo_path))) {
            unlink(public_path('uploads/image/' . $footerSection->logo_path));
        }

        $footerSection->delete();

        return response()->json([
            'message' => 'Footer section deleted successfully'
        ]);
    }

    public function activate(FooterSection $footerSection)
    {
        $footerSection->activate();

        return response()->json([
            'message' => 'Footer section activated successfully',
            'data' => $footerSection
        ]);
    }

    public function getActive()
    {
        $activeFooter = FooterSection::getActive();

        if (!$activeFooter) {
            return response()->json([
                'message' => 'No active footer section found'
            ], 404);
        }

        return response()->json($activeFooter);
    }

    /**
     * Handle logo upload with unique filename (fixed version)
     */
    private function handleLogoUpload($logoFile, $data)
    {
        // Generate unique filename using tagline or description
        $baseName = isset($data['tagline']) ? Str::slug($data['tagline']) : 'footer_logo';
        $extension = $logoFile->getClientOriginalExtension();
        $filename = time() . '_' . $baseName . '.' . $extension;

        // Ensure directory exists
        if (!file_exists(public_path('uploads/image'))) {
            mkdir(public_path('uploads/image'), 0755, true);
        }

        // Move the file to public/uploads/image directory
        $logoFile->move(public_path('uploads/image'), $filename);

        // Return just the filename (not the full path)
        return $filename;
    }

    /**
     * Convert JSON strings to arrays for validation
     */
    private function convertJsonArrays(&$data)
    {
        $arrayFields = ['social_links', 'quick_links', 'programs'];

        foreach ($arrayFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $decoded = json_decode($data[$field], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $data[$field] = $decoded;
                }
            }
        }
    }
}
