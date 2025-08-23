<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HeaderSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HeaderSectionController extends Controller
{
    public function index()
    {
        $headers = HeaderSection::ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $headers
        ]);
    }

    public function show($id)
    {
        $header = HeaderSection::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $header
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo_type' => 'required|in:image,text',
            'logo_text' => 'required_if:logo_type,text|nullable|string|max:255',
            'logo_image' => 'required_if:logo_type,image|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_tagline' => 'nullable|string|max:255',
            'buttons' => 'nullable|array',
            'logo_styles' => 'nullable|array',
            'tagline_styles' => 'nullable|array',
            'nav_styles' => 'nullable|array',
            'advanced_settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('logo_image');

        // Handle JSON fields
        $jsonFields = ['buttons', 'logo_styles', 'tagline_styles', 'nav_styles', 'advanced_settings', 'logo_link'];
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = json_decode($data[$field], true);
            }
        }

        if ($request->hasFile('logo_image')) {
            $path = $request->file('logo_image')->store('header-logos', 'public');
            $data['logo_image_path'] = Storage::url($path);
        }

        $header = HeaderSection::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Header created successfully',
            'data' => $header
        ]);
    }

    public function update(Request $request, $id)
    {
        $header = HeaderSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'logo_type' => 'sometimes|required|in:image,text',
            'logo_text' => 'required_if:logo_type,text|nullable|string|max:255',
            'logo_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_tagline' => 'nullable|string|max:255',
            'buttons' => 'nullable|array',
            'logo_styles' => 'nullable|array',
            'tagline_styles' => 'nullable|array',
            'nav_styles' => 'nullable|array',
            'advanced_settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('logo_image');

        // Handle JSON fields
        $jsonFields = ['buttons', 'logo_styles', 'tagline_styles', 'nav_styles', 'advanced_settings', 'logo_link'];
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = json_decode($data[$field], true);
            }
        }

        if ($request->hasFile('logo_image')) {
            // Delete old image if exists
            if ($header->logo_image_path) {
                $oldImage = str_replace('/storage/', '', $header->logo_image_path);
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('logo_image')->store('header-logos', 'public');
            $data['logo_image_path'] = Storage::url($path);
        }

        $header->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Header updated successfully',
            'data' => $header
        ]);
    }

    public function destroy($id)
    {
        $header = HeaderSection::findOrFail($id);

        // Delete image if exists
        if ($header->logo_image_path) {
            $oldImage = str_replace('/storage/', '', $header->logo_image_path);
            Storage::disk('public')->delete($oldImage);
        }

        $header->delete();

        return response()->json([
            'success' => true,
            'message' => 'Header deleted successfully'
        ]);
    }

    public function toggleActive($id)
    {
        $header = HeaderSection::findOrFail($id);

        // If activating this header, deactivate all others
        if (!$header->is_active) {
            HeaderSection::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $header->update(['is_active' => !$header->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Header status updated successfully',
            'data' => $header
        ]);
    }

    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'headers' => 'required|array',
            'headers.*.id' => 'required|exists:header_sections,id',
            'headers.*.sort_order' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->headers as $header) {
            HeaderSection::where('id', $header['id'])
                ->update(['sort_order' => $header['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully'
        ]);
    }

    public function getActive()
    {
        $header = HeaderSection::active()->ordered()->first();

        return response()->json([
            'success' => true,
            'data' => $header
        ]);
    }

    public function bulkToggle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header_ids' => 'required|array',
            'header_ids.*' => 'exists:header_sections,id',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If activating headers, deactivate all others first
        if ($request->is_active) {
            HeaderSection::whereNotIn('id', $request->header_ids)
                ->update(['is_active' => false]);
        }

        HeaderSection::whereIn('id', $request->header_ids)
            ->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Headers updated successfully'
        ]);
    }
}
