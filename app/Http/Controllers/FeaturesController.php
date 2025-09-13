<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FeaturesSection;
use App\Models\FeaturesItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FeaturesController extends Controller
{
    // Get all features items
    public function index()
    {
        $items = FeaturesItem::orderBy('order', 'asc')->get();
        return response()->json($items);
    }

    // Get section settings
    public function getSettings()
    {
        $settings = FeaturesSection::first();
        if (!$settings) {
            $settings = FeaturesSection::create([]);
        }
        return response()->json($settings);
    }

    // Store new feature item
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get next order number
        $maxOrder = FeaturesItem::max('order') ?? -1;

        $data = $request->all();
        $data['order'] = $maxOrder + 1;

        $item = FeaturesItem::create($data);

        return response()->json([
            'message' => 'Feature item created successfully',
            'data' => $item
        ], 201);
    }

    // Update feature item
    public function update(Request $request, $id)
    {
        $item = FeaturesItem::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item->update($request->all());

        return response()->json([
            'message' => 'Feature item updated successfully',
            'data' => $item
        ]);
    }

    // Delete feature item
    public function destroy($id)
    {
        $item = FeaturesItem::findOrFail($id);
        $item->delete();

        // Reorder remaining items
        $this->reorderItems();

        return response()->json([
            'message' => 'Feature item deleted successfully'
        ]);
    }

    // Toggle item status
    public function toggleStatus($id)
    {
        $item = FeaturesItem::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        return response()->json([
            'message' => 'Status updated successfully',
            'is_active' => $item->is_active
        ]);
    }

    // Update items order
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:features_items,id',
            'items.*.order' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                FeaturesItem::where('id', $item['id'])
                    ->update(['order' => $item['order']]);
            }
        });

        return response()->json(['message' => 'Order updated successfully']);
    }

    public function updateSettings(Request $request)
    {
        if ($request->has('is_active')) {
            $request->merge([
                'is_active' => filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN)
            ]);
        } else {
            $request->merge(['is_active' => false]);
        }
        $validator = Validator::make($request->all(), [
            'section_title' => 'required|string|max:255',
            'section_description' => 'required|string',
            'section_title_color' => 'nullable|string|max:7',
            'section_background_color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $settings = FeaturesSection::first();
        if (!$settings) {
            $settings = FeaturesSection::create($request->all());
        } else {
            $settings->update($request->all());
        }

        // ✅ Handle background image upload (to public/uploads/image)
        if ($request->hasFile('section_background_image')) {
            // Delete old image if exists
            if ($settings->section_background_image && file_exists(public_path($settings->section_background_image))) {
                unlink(public_path($settings->section_background_image));
            }

            $filename = time() . '_' . $request->file('section_background_image')->getClientOriginalName();
            $request->file('section_background_image')->move(public_path('uploads/image'), $filename);

            $settings->update([
                'section_background_image' => 'uploads/image/' . $filename
            ]);
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => $settings
        ]);
    }


    // Get public features data (for frontend display)
    public function getPublicData()
    {
        $section = FeaturesSection::where('is_active', true)->first();
        if (!$section) {
            return response()->json(['message' => 'Section not active'], 404);
        }

        $features = FeaturesItem::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        return response()->json([
            'section' => $section,
            'features' => $features
        ]);
    }

    private function reorderItems()
    {
        $items = FeaturesItem::orderBy('order', 'asc')->get();
        foreach ($items as $index => $item) {
            $item->update(['order' => $index]);
        }
    }
}
