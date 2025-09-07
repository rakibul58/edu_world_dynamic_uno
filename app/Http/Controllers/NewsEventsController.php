<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewsEvent;
use App\Models\NewsEventsSectionSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NewsEventsController extends Controller
{
    // Get all news events
    public function index()
    {
        try {
            $newsEvents = NewsEvent::orderBy('order', 'asc')->get();
            return response()->json($newsEvents);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch news events',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Store new news event
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'card_background_type' => 'required|in:gradient,image',
            'icon' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gradient_start' => 'nullable|string|max:20',
            'gradient_end' => 'nullable|string|max:20',
            'gradient_direction' => 'nullable|string|max:10',
            'title_color' => 'nullable|string|max:20',
            'title_font_size' => 'nullable|string|max:10',
            'title_font_weight' => 'nullable|string|max:3',
            'description_color' => 'nullable|string|max:20',
            'description_font_size' => 'nullable|string|max:10',
            'description_font_weight' => 'nullable|string|max:3',
            'date_color' => 'nullable|string|max:20',
            'date_font_size' => 'nullable|string|max:10',
            'date_font_weight' => 'nullable|string|max:3',
            'link_color' => 'nullable|string|max:20',
            'link_hover_color' => 'nullable|string|max:20',
            'link_font_weight' => 'nullable|string|max:3',
            'card_border_radius' => 'nullable|string|max:10',
            'card_shadow' => 'nullable|string|max:100',
            'card_hover_shadow' => 'nullable|string|max:100',
            'card_hover_transform' => 'nullable|string|max:50',
            'card_background_color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $newsEvent = new NewsEvent($request->except(['image']));

            // Handle image upload
            if ($request->hasFile('image')) {
                $imageUrl = $newsEvent->handleImageUpload($request->file('image'));
                $newsEvent->image_url = $imageUrl;
            }

            $newsEvent->save();

            DB::commit();

            return response()->json([
                'message' => 'News event created successfully',
                'data' => $newsEvent->fresh()
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create news event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show specific news event
    public function show($id)
    {
        try {
            $newsEvent = NewsEvent::findOrFail($id);
            return response()->json($newsEvent);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'News event not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    // Update news event
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'date' => 'date',
            'description' => 'string',
            'link' => 'nullable|url',
            'card_background_type' => 'in:gradient,image',
            'icon' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gradient_start' => 'nullable|string|max:20',
            'gradient_end' => 'nullable|string|max:20',
            'gradient_direction' => 'nullable|string|max:10',
            'title_color' => 'nullable|string|max:20',
            'title_font_size' => 'nullable|string|max:10',
            'title_font_weight' => 'nullable|string|max:3',
            'description_color' => 'nullable|string|max:20',
            'description_font_size' => 'nullable|string|max:10',
            'description_font_weight' => 'nullable|string|max:3',
            'date_color' => 'nullable|string|max:20',
            'date_font_size' => 'nullable|string|max:10',
            'date_font_weight' => 'nullable|string|max:3',
            'link_color' => 'nullable|string|max:20',
            'link_hover_color' => 'nullable|string|max:20',
            'link_font_weight' => 'nullable|string|max:3',
            'card_border_radius' => 'nullable|string|max:10',
            'card_shadow' => 'nullable|string|max:100',
            'card_hover_shadow' => 'nullable|string|max:100',
            'card_hover_transform' => 'nullable|string|max:50',
            'card_background_color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $newsEvent = NewsEvent::findOrFail($id);
            $newsEvent->fill($request->except(['image']));

            // Handle image upload
            if ($request->hasFile('image')) {
                $imageUrl = $newsEvent->handleImageUpload($request->file('image'));
                $newsEvent->image_url = $imageUrl;
            } elseif ($request->card_background_type === 'gradient') {
                // Clear image if switching to gradient
                if ($newsEvent->image_url && file_exists(public_path($newsEvent->image_url))) {
                    unlink(public_path($newsEvent->image_url));
                }
                $newsEvent->image_url = null;
            }

            $newsEvent->save();

            DB::commit();

            return response()->json([
                'message' => 'News event updated successfully',
                'data' => $newsEvent->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update news event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete news event
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $newsEvent = NewsEvent::findOrFail($id);
            $newsEvent->delete();

            DB::commit();

            return response()->json([
                'message' => 'News event deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete news event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Toggle active status
    public function toggleStatus($id)
    {
        try {
            $newsEvent = NewsEvent::findOrFail($id);
            $newsEvent->is_active = !$newsEvent->is_active;
            $newsEvent->save();

            return response()->json([
                'message' => 'Status updated successfully',
                'data' => $newsEvent
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update order
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:news_events,id',
            'items.*.order' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                NewsEvent::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Order updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get section settings
    public function getSectionSettings()
    {
        try {
            $settings = NewsEventsSectionSettings::getOrCreateSettings();
            return response()->json($settings);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch section settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update section settings
    public function updateSectionSettings(Request $request)
    {
        if($request->has('enable_animations')){
            $request->merge(['enable_animations' => filter_var($request->input('enable_animations'), FILTER_VALIDATE_BOOLEAN)]);
        } else {
            $request->merge(['enable_animations' => false]);
        }
        $validator = Validator::make($request->all(), [
            'section_title' => 'required|string|max:255',
            'section_description' => 'required|string',
            'section_title_color' => 'nullable|string|max:20',
            'section_title_font_size' => 'nullable|string|max:10',
            'section_title_font_weight' => 'nullable|string|max:3',
            'section_description_color' => 'nullable|string|max:20',
            'section_description_font_size' => 'nullable|string|max:10',
            'section_description_font_weight' => 'nullable|string|max:3',
            'section_background_color' => 'nullable|string|max:20',
            'section_background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'section_padding' => 'nullable|string|max:20',
            'grid_columns' => 'nullable|string|max:100',
            'grid_gap' => 'nullable|string|max:10',
            'container_max_width' => 'nullable|string|max:10',
            'container_padding' => 'nullable|string|max:20',
            'header_text_align' => 'nullable|in:left,center,right',
            'header_margin_bottom' => 'nullable|string|max:10',
            'enable_animations' => 'boolean',
            'animation_duration' => 'nullable|string|max:10',
            'animation_delay_increment' => 'nullable|string|max:10',
            'mobile_breakpoint' => 'nullable|string|max:10',
            'mobile_grid_columns' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $settings = NewsEventsSectionSettings::getOrCreateSettings();
            $settings->fill($request->except(['section_background_image']));

            // Handle background image upload
            if ($request->hasFile('section_background_image')) {
                $imageUrl = $settings->handleBackgroundImageUpload($request->file('section_background_image'));
                $settings->section_background_image = $imageUrl;
            }

            $settings->save();

            DB::commit();

            return response()->json([
                'message' => 'Section settings updated successfully',
                'data' => $settings->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update section settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Frontend API - Get public news events
    public function getPublicNewsEvents()
    {
        try {
            $newsEvents = NewsEvent::active()->ordered()->get();
            $settings = NewsEventsSectionSettings::getOrCreateSettings();

            return response()->json([
                'settings' => $settings,
                'newsEvents' => $newsEvents
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch news events',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}