<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\GallerySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::ordered()->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'background_type' => 'required|in:gradient,image,color',
            'gradient_start' => 'nullable|string',
            'gradient_end' => 'nullable|string',
            'gradient_direction' => 'nullable|string',
            'background_color' => 'nullable|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title_color' => 'nullable|string',
            'title_font_size' => 'nullable|string',
            'title_font_weight' => 'nullable|string',
            'description_color' => 'nullable|string',
            'description_font_size' => 'nullable|string',
            'item_height' => 'nullable|string',
            'border_radius' => 'nullable|string',
            'icon_size' => 'nullable|string',
            'hover_transform' => 'nullable|string',
            'hover_shadow' => 'nullable|string',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/image'), $filename);
            $data['background_image'] = $filename;
        }

        // Set order
        $maxOrder = GalleryItem::max('order') ?? -1;
        $data['order'] = $maxOrder + 1;

        $item = GalleryItem::create($data);

        return response()->json([
            'message' => 'Gallery item created successfully',
            'data' => $item
        ], 201);
    }

    public function show($id)
    {
        $item = GalleryItem::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = GalleryItem::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'background_type' => 'required|in:gradient,image,color',
            'gradient_start' => 'nullable|string',
            'gradient_end' => 'nullable|string',
            'gradient_direction' => 'nullable|string',
            'background_color' => 'nullable|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title_color' => 'nullable|string',
            'title_font_size' => 'nullable|string',
            'title_font_weight' => 'nullable|string',
            'description_color' => 'nullable|string',
            'description_font_size' => 'nullable|string',
            'item_height' => 'nullable|string',
            'border_radius' => 'nullable|string',
            'icon_size' => 'nullable|string',
            'hover_transform' => 'nullable|string',
            'hover_shadow' => 'nullable|string',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($item->background_image && file_exists(public_path('uploads/image/' . $item->background_image))) {
                unlink(public_path('uploads/image/' . $item->background_image));
            }

            $file = $request->file('background_image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/image'), $filename);
            $data['background_image'] = $filename;
        }

        $item->update($data);

        return response()->json([
            'message' => 'Gallery item updated successfully',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = GalleryItem::findOrFail($id);

        // Delete associated image
        if ($item->background_image && file_exists(public_path('uploads/image/' . $item->background_image))) {
            unlink(public_path('uploads/image/' . $item->background_image));
        }

        $item->delete();

        return response()->json(['message' => 'Gallery item deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $item = GalleryItem::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $item
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:gallery_items,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $itemData) {
            GalleryItem::where('id', $itemData['id'])
                ->update(['order' => $itemData['order']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    public function getSettings()
    {
        $settings = GallerySetting::first();

        if (!$settings) {
            // Create default settings
            $settings = GallerySetting::create([
                'section_title' => 'Explore Our Beautiful Campus',
                'section_description' => 'Take a virtual tour of our state-of-the-art facilities designed to inspire learning and spiritual growth.',
            ]);
        }

        return response()->json($settings);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'section_title' => 'required|string|max:255',
            'section_description' => 'nullable|string',
            'section_title_color' => 'nullable|string',
            'section_title_font_size' => 'nullable|string',
            'section_title_font_weight' => 'nullable|string',
            'section_description_color' => 'nullable|string',
            'section_description_font_size' => 'nullable|string',
            'section_background_color' => 'nullable|string',
            'section_padding' => 'nullable|string',
            'header_text_align' => 'required|in:left,center,right',
            'grid_columns' => 'nullable|string',
            'grid_gap' => 'nullable|string',
            'container_max_width' => 'nullable|string',
            'container_padding' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $settings = GallerySetting::first();

        if ($settings) {
            $settings->update($request->all());
        } else {
            $settings = GallerySetting::create($request->all());
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => $settings
        ]);
    }

    public function pubicIndex()
    {
        $items = GalleryItem::active()->ordered()->get();
        return response()->json($items);
    }

    public function settings()
    {
        $settings = GallerySetting::where('is_active', true)->first();

        if (!$settings) {
            // Return default settings
            $settings = [
                'section_title' => 'Explore Our Beautiful Campus',
                'section_description' => 'Take a virtual tour of our state-of-the-art facilities designed to inspire learning and spiritual growth.',
                'section_title_color' => '#102e4a',
                'section_title_font_size' => '2.5rem',
                'section_title_font_weight' => '700',
                'section_description_color' => '#111111',
                'section_description_font_size' => '1.1rem',
                'section_background_color' => '#f8fbfe',
                'section_padding' => '80px 0',
                'header_text_align' => 'center',
                'grid_columns' => 'repeat(auto-fit, minmax(350px, 1fr))',
                'grid_gap' => '20px',
                'container_max_width' => '1240px',
                'container_padding' => '0 20px',
                'is_active' => true,
            ];
        }

        return response()->json($settings);
    }
}
