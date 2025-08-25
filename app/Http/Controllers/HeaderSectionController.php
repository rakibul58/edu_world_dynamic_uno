<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HeaderSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
        try {
            // Debug: Log incoming request data
            Log::info('HeaderSection Store Request:', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'logo_type' => 'required|in:image,text',
                'logo_text' => 'nullable|string|max:255',
                'logo_tagline' => 'nullable|string|max:255',
                'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'buttons' => 'nullable',
                'logo_styles' => 'nullable',
                'tagline_styles' => 'nullable',
                'nav_styles' => 'nullable',
                'advanced_settings' => 'nullable',
                'logo_link' => 'nullable',
                'is_active' => 'nullable|boolean',
                'sort_order' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->except(['logo_image']);

            // Handle JSON fields - decode if they're strings
            $jsonFields = ['buttons', 'logo_styles', 'tagline_styles', 'nav_styles', 'advanced_settings', 'logo_link'];
            foreach ($jsonFields as $field) {
                if (isset($data[$field])) {
                    if (is_string($data[$field])) {
                        $decoded = json_decode($data[$field], true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $data[$field] = $decoded;
                        }
                    }
                }
            }

            // Handle logo image upload
            if ($request->hasFile('logo_image')) {
                $file = $request->file('logo_image');
                Log::info('File upload attempt:', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'is_valid' => $file->isValid()
                ]);

                if ($file->isValid()) {
                    try {
                        // Create directory if it doesn't exist
                        $uploadPath = public_path('uploads/image');
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }

                        // Generate unique filename
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Move file to public/uploads/image
                        $file->move($uploadPath, $filename);

                        // Store relative path
                        $data['logo_image_path'] = '/uploads/image/' . $filename;

                        Log::info('File uploaded successfully:', [
                            'path' => $data['logo_image_path'],
                            'full_path' => $uploadPath . '/' . $filename
                        ]);
                    } catch (\Exception $uploadError) {
                        Log::error('File upload error:', [
                            'message' => $uploadError->getMessage(),
                            'trace' => $uploadError->getTraceAsString()
                        ]);
                        throw $uploadError;
                    }
                } else {
                    Log::error('Invalid file upload:', [
                        'errors' => $file->getErrorMessage()
                    ]);
                }
            } else {
                Log::info('No file uploaded in request');
            }

            // Set defaults if not provided
            $data['is_active'] = $data['is_active'] ?? false;
            $data['sort_order'] = $data['sort_order'] ?? HeaderSection::max('sort_order') + 1;

            // If activating this header, deactivate all others
            if ($data['is_active']) {
                HeaderSection::where('is_active', true)->update(['is_active' => false]);
            }

            $header = HeaderSection::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Header created successfully',
                'data' => $header
            ]);
        } catch (\Exception $e) {
            Log::error('HeaderSection Store Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create header: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $header = HeaderSection::findOrFail($id);

            // Debug: Log incoming request data
            Log::info('HeaderSection Update Request:', [
                'id' => $id,
                'method' => $request->method(),
                'has_file' => $request->hasFile('logo_image'),
                'content_type' => $request->header('Content-Type'),
                'data' => $request->except(['logo_image']),
                'current_header' => $header->toArray()
            ]);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'logo_type' => 'sometimes|required|in:image,text',
                'logo_text' => 'nullable|string|max:255',
                'logo_tagline' => 'nullable|string|max:255',
                'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'buttons' => 'nullable',
                'logo_styles' => 'nullable',
                'tagline_styles' => 'nullable',
                'nav_styles' => 'nullable',
                'advanced_settings' => 'nullable',
                'logo_link' => 'nullable',
                'is_active' => 'nullable|boolean',
                'sort_order' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                Log::error('HeaderSection Update Validation Failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Start with existing data
            $updateData = [];

            // Handle simple fields
            $simpleFields = ['name', 'logo_type', 'logo_text', 'logo_tagline', 'is_active', 'sort_order'];
            foreach ($simpleFields as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->input($field);
                }
            }

            // Handle JSON fields - the model mutators will handle encoding
            $jsonFields = ['buttons', 'logo_styles', 'tagline_styles', 'nav_styles', 'advanced_settings', 'logo_link'];
            foreach ($jsonFields as $field) {
                if ($request->has($field)) {
                    $value = $request->input($field);
                    if (is_string($value)) {
                        // Try to decode if it's a JSON string
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $updateData[$field] = $decoded;
                        } else {
                            $updateData[$field] = $value;
                        }
                    } else {
                        $updateData[$field] = $value;
                    }
                }
            }

            // Handle logo image upload
            if ($request->hasFile('logo_image')) {
                $file = $request->file('logo_image');
                Log::info('File upload attempt in update:', [
                    'header_id' => $id,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'is_valid' => $file->isValid(),
                    'current_logo_path' => $header->logo_image_path
                ]);

                if ($file->isValid()) {
                    try {
                        // Delete old image if exists
                        if ($header->logo_image_path) {
                            $oldImageFullPath = public_path($header->logo_image_path);
                            if (file_exists($oldImageFullPath)) {
                                unlink($oldImageFullPath);
                                Log::info('Old image deleted:', ['path' => $oldImageFullPath]);
                            }
                        }

                        // Create directory if it doesn't exist
                        $uploadPath = public_path('uploads/image');
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                            Log::info('Created upload directory:', ['path' => $uploadPath]);
                        }

                        // Generate unique filename
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Move file to public/uploads/image
                        $moved = $file->move($uploadPath, $filename);

                        if ($moved) {
                            // Store relative path
                            $updateData['logo_image_path'] = '/uploads/image/' . $filename;

                            Log::info('File updated successfully:', [
                                'path' => $updateData['logo_image_path'],
                                'full_path' => $uploadPath . '/' . $filename,
                                'file_exists' => file_exists($uploadPath . '/' . $filename)
                            ]);
                        } else {
                            Log::error('Failed to move uploaded file');
                            throw new \Exception('Failed to move uploaded file');
                        }
                    } catch (\Exception $uploadError) {
                        Log::error('File upload error in update:', [
                            'message' => $uploadError->getMessage(),
                            'trace' => $uploadError->getTraceAsString(),
                            'header_id' => $id
                        ]);
                        throw $uploadError;
                    }
                } else {
                    Log::error('Invalid file upload in update:', [
                        'error_code' => $file->getError(),
                        'header_id' => $id
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid file upload'
                    ], 400);
                }
            }

            Log::info('Update data prepared:', $updateData);

            // If activating this header, deactivate all others
            if (isset($updateData['is_active']) && $updateData['is_active'] && !$header->is_active) {
                HeaderSection::where('id', '!=', $id)->update(['is_active' => false]);
                Log::info('Deactivated other headers for activation');
            }

            // Update the header
            $updated = $header->update($updateData);
            Log::info('Header update result:', ['success' => $updated]);

            if (!$updated) {
                throw new \Exception('Failed to update header in database');
            }

            $freshHeader = $header->fresh();
            Log::info('Updated header data:', $freshHeader->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Header updated successfully',
                'data' => $freshHeader
            ]);
        } catch (\Exception $e) {
            Log::error('HeaderSection Update Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'header_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update header: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $header = HeaderSection::findOrFail($id);

            // Delete image if exists
            if ($header->logo_image_path) {
                $oldImageFullPath = public_path($header->logo_image_path);
                if (file_exists($oldImageFullPath)) {
                    unlink($oldImageFullPath);
                    Log::info('Image deleted:', ['path' => $oldImageFullPath]);
                }
            }

            $header->delete();

            return response()->json([
                'success' => true,
                'message' => 'Header deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('HeaderSection Delete Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete header: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive($id)
    {
        try {
            $header = HeaderSection::findOrFail($id);

            // If activating this header, deactivate all others
            if (!$header->is_active) {
                HeaderSection::where('id', '!=', $id)->update(['is_active' => false]);
            }

            $header->update(['is_active' => !$header->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Header status updated successfully',
                'data' => $header->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('HeaderSection Toggle Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update header status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('HeaderSection Order Update Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
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
        try {
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
        } catch (\Exception $e) {
            Log::error('HeaderSection Bulk Toggle Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update headers: ' . $e->getMessage()
            ], 500);
        }
    }
}
