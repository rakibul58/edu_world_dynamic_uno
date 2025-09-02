<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\AboutStat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutSectionController extends Controller
{
    public function index()
    {
        $sections = AboutSection::with('stats')->orderBy('created_at', 'desc')->get();
        return response()->json($sections);
    }

    public function show($id)
    {
        $section = AboutSection::with('stats')->findOrFail($id);
        return response()->json($section);
    }

    public function getActive()
    {
        $section = AboutSection::with('stats')->where('is_active', true)->first();
        return response()->json($section);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description_1' => 'required|string',
            'description_2' => 'required|string',
            'background_color' => 'nullable|string|max:7',
            'title_color' => 'nullable|string|max:7',
            'title_font_size' => 'nullable|string|max:20',
            'title_font_weight' => 'nullable|string|max:10',
            'text_color' => 'nullable|string|max:7',
            'text_font_size' => 'nullable|string|max:20',
            'text_font_weight' => 'nullable|string|max:10',
            'gradient_start' => 'nullable|string|max:7',
            'gradient_end' => 'nullable|string|max:7',
            'gradient_direction' => 'nullable|string|max:20',
            'right_side_type' => 'required|in:image,gradient',
            'image_icon' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stats' => 'array',
            'stats.*.value' => 'required|string|max:50',
            'stats.*.label' => 'required|string|max:100',
            'stats.*.number_color' => 'nullable|string|max:7',
            'stats.*.label_color' => 'nullable|string|max:7',
            'stats.*.number_font_size' => 'nullable|string|max:20',
            'stats.*.label_font_size' => 'nullable|string|max:20',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($validatedData['title']) . '.' . $image->getClientOriginalExtension();

            // Ensure directory exists
            if (!file_exists(public_path('uploads/images'))) {
                mkdir(public_path('uploads/images'), 0755, true);
            }

            $image->move(public_path('uploads/images'), $imageName);
            $validatedData['image_path'] = $imageName;
        }

        // Set all other sections as inactive if this one is active
        if (!isset($validatedData['is_active'])) {
            $validatedData['is_active'] = true;
        }

        if ($validatedData['is_active']) {
            AboutSection::where('is_active', true)->update(['is_active' => false]);
        }

        $section = AboutSection::create($validatedData);

        // Create stats
        if (isset($validatedData['stats'])) {
            foreach ($validatedData['stats'] as $index => $stat) {
                $stat['about_section_id'] = $section->id;
                $stat['sort_order'] = $index;
                AboutStat::create($stat);
            }
        }

        return response()->json([
            'message' => 'About section created successfully',
            'data' => $section->load('stats')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $section = AboutSection::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'string|max:255',
            'description_1' => 'string',
            'description_2' => 'string',
            'background_color' => 'nullable|string|max:7',
            'title_color' => 'nullable|string|max:7',
            'title_font_size' => 'nullable|string|max:20',
            'title_font_weight' => 'nullable|string|max:10',
            'text_color' => 'nullable|string|max:7',
            'text_font_size' => 'nullable|string|max:20',
            'text_font_weight' => 'nullable|string|max:10',
            'gradient_start' => 'nullable|string|max:7',
            'gradient_end' => 'nullable|string|max:7',
            'gradient_direction' => 'nullable|string|max:20',
            'right_side_type' => 'in:image,gradient',
            'image_icon' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stats' => 'array',
            'stats.*.value' => 'string|max:50',
            'stats.*.label' => 'string|max:100',
            'stats.*.number_color' => 'nullable|string|max:7',
            'stats.*.label_color' => 'nullable|string|max:7',
            'stats.*.number_font_size' => 'nullable|string|max:20',
            'stats.*.label_font_size' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($section->image_path && file_exists(public_path('uploads/images/' . $section->image_path))) {
                unlink(public_path('uploads/images/' . $section->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($validatedData['title']) . '.' . $image->getClientOriginalExtension();

            // Ensure directory exists
            if (!file_exists(public_path('uploads/images'))) {
                mkdir(public_path('uploads/images'), 0755, true);
            }

            $image->move(public_path('uploads/images'), $imageName);
            $validatedData['image_path'] = $imageName;
        }

        // Set all other sections as inactive if this one is active
        if (isset($validatedData['is_active']) && $validatedData['is_active']) {
            AboutSection::where('is_active', true)->where('id', '!=', $id)->update(['is_active' => false]);
        }

        $section->update($validatedData);

        // Update stats
        if (isset($validatedData['stats'])) {
            // Delete existing stats
            $section->stats()->delete();

            // Create new stats
            foreach ($validatedData['stats'] as $index => $stat) {
                $stat['about_section_id'] = $section->id;
                $stat['sort_order'] = $index;
                AboutStat::create($stat);
            }
        }

        return response()->json([
            'message' => 'About section updated successfully',
            'data' => $section->fresh()->load('stats')
        ]);
    }

    public function destroy($id)
    {
        $section = AboutSection::findOrFail($id);

        // Delete associated image
        if ($section->image_path && file_exists(public_path('uploads/images/' . $section->image_path))) {
            unlink(public_path('uploads/images/' . $section->image_path));
        }

        $section->delete();

        return response()->json([
            'message' => 'About section deleted successfully'
        ]);
    }

    public function activate($id)
    {
        // Set all sections as inactive
        AboutSection::where('is_active', true)->update(['is_active' => false]);

        // Activate the selected section
        $section = AboutSection::findOrFail($id);
        $section->update(['is_active' => true]);

        return response()->json([
            'message' => 'About section activated successfully',
            'data' => $section
        ]);
    }
}
