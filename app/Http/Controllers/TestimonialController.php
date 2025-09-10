<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialSectionSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::ordered()->get();
        $sectionSettings = TestimonialSectionSettings::getSettings();

        return response()->json([
            'testimonials' => $testimonials,
            'section_settings' => $sectionSettings
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'initials' => 'nullable|string|max:3',
            'avatar_type' => 'required|in:initials,image',
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'avatar_bg_color' => 'nullable|string',
            'avatar_text_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'name_color' => 'nullable|string',
            'role_color' => 'nullable|string',
            'text_font_size' => 'nullable|string',
            'name_font_size' => 'nullable|string',
            'role_font_size' => 'nullable|string',
            'text_font_weight' => 'nullable|string',
            'name_font_weight' => 'nullable|string',
            'role_font_weight' => 'nullable|string',
            'card_background' => 'nullable|string',
            'card_border_color' => 'nullable|string',
            'card_border_radius' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Handle avatar image upload to public/uploads/image
        if ($request->hasFile('avatar_image')) {
            $image = $request->file('avatar_image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Ensure the directory exists
            $uploadPath = public_path('uploads/image');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move the file to public/uploads/image
            $image->move($uploadPath, $filename);
            $data['avatar_url'] = '/uploads/image/' . $filename;
        }

        // Auto-generate initials if not provided and using initials type
        if ($data['avatar_type'] === 'initials' && empty($data['initials'])) {
            $nameParts = explode(' ', trim($data['name']));
            $data['initials'] = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
        }

        $testimonial = Testimonial::create($data);

        return response()->json([
            'message' => 'Testimonial created successfully',
            'testimonial' => $testimonial
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Normalize input before validation
        $data = $request->all();
        if ($request->has('is_active')) {
            $data['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        } else {
            // default to false if not provided
            $data['is_active'] = false;
        }

        $validator = Validator::make($data, [
            'text' => 'required|string',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'initials' => 'nullable|string|max:3',
            'avatar_type' => 'required|in:initials,image',
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'order' => 'sometimes|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle avatar image upload
        if ($request->hasFile('avatar_image')) {
            if ($testimonial->avatar_url) {
                $oldImagePath = public_path($testimonial->avatar_url);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('avatar_image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $uploadPath = public_path('uploads/image');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $filename);
            $data['avatar_url'] = '/uploads/image/' . $filename;
        }

        // Switch from image to initials
        if ($data['avatar_type'] === 'initials' && $testimonial->avatar_type === 'image') {
            if ($testimonial->avatar_url) {
                $oldImagePath = public_path($testimonial->avatar_url);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $data['avatar_url'] = null;
        }

        // Auto-generate initials
        if ($data['avatar_type'] === 'initials' && empty($data['initials'])) {
            $nameParts = explode(' ', trim($data['name']));
            $data['initials'] = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
        }

        $testimonial->update($data);

        return response()->json([
            'message' => 'Testimonial updated successfully',
            'testimonial' => $testimonial
        ]);
    }


    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Delete avatar image from public/uploads/image if exists
        if ($testimonial->avatar_url) {
            $imagePath = public_path($testimonial->avatar_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial deleted successfully'
        ]);
    }

    public function sectionSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'background_color' => 'required|string',
            'title_color' => 'required|string',
            'text_color' => 'required|string',
            'card_background' => 'required|string',
            'card_border_color' => 'required|string',
            'avatar_background' => 'required|string',
            'animation_style' => 'required|string',
            'animation_duration' => 'required|integer|min:100|max:3000',
            'columns' => 'required|string|in:1,2,3,auto'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $settings = TestimonialSectionSettings::getSettings();
        $settings->update($request->all());

        return response()->json([
            'message' => 'Section settings updated successfully',
            'settings' => $settings
        ]);
    }

    public function frontendIndex()
    {
        $testimonials = Testimonial::active()->ordered()->get();
        $sectionSettings = TestimonialSectionSettings::getSettings();

        return view('testimonials.index', compact('testimonials', 'sectionSettings'));
    }

    public function getTestimonials()
    {
        $testimonials = Testimonial::active()->ordered()->get();
        $sectionSettings = TestimonialSectionSettings::getSettings();

        return response()->json([
            'testimonials' => $testimonials,
            'section_settings' => $sectionSettings
        ]);
    }
}
