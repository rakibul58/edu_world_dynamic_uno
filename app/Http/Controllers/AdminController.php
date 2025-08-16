<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\HeroSlide;
use App\Models\Program;
use App\Models\Feature;
use App\Models\Testimonial;
use App\Models\GalleryItem;
use App\Models\NewsEvent;

class AdminController extends Controller
{

    // Site Settings
    public function getSettings()
    {
        return response()->json(SiteSetting::all()->groupBy('group'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->settings as $key => $value) {
            SiteSetting::set($key, $value, $request->types[$key] ?? 'text', $request->groups[$key] ?? 'general');
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }

    // Hero Slides
    public function getHeroSlides()
    {
        return response()->json(HeroSlide::ordered()->get());
    }

    public function storeHeroSlide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'background_image' => 'nullable|string',
            'background_color' => 'required|string',
            'cta_primary_text' => 'required|string',
            'cta_primary_url' => 'required|string',
            'cta_secondary_text' => 'required|string',
            'cta_secondary_url' => 'required|string',
        ]);

        $slide = HeroSlide::create($validated);
        return response()->json($slide, 201);
    }

    public function updateHeroSlide(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'background_image' => 'nullable|string',
            'background_color' => 'required|string',
            'cta_primary_text' => 'required|string',
            'cta_primary_url' => 'required|string',
            'cta_secondary_text' => 'required|string',
            'cta_secondary_url' => 'required|string',
        ]);

        $heroSlide->update($validated);
        return response()->json($heroSlide);
    }

    public function deleteHeroSlide(HeroSlide $heroSlide)
    {
        $heroSlide->delete();
        return response()->json(['message' => 'Hero slide deleted successfully']);
    }

    // Programs
    public function getPrograms()
    {
        return response()->json(Program::ordered()->get());
    }

    public function storeProgram(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'background_color' => 'required|string',
            'button_text' => 'required|string',
            'button_url' => 'required|string',
        ]);

        $program = Program::create($validated);
        return response()->json($program, 201);
    }

    public function updateProgram(Request $request, Program $program)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'background_color' => 'required|string',
            'button_text' => 'required|string',
            'button_url' => 'required|string',
        ]);

        $program->update($validated);
        return response()->json($program);
    }

    public function deleteProgram(Program $program)
    {
        $program->delete();
        return response()->json(['message' => 'Program deleted successfully']);
    }

    // Features
    public function getFeatures()
    {
        return response()->json(Feature::ordered()->get());
    }

    public function storeFeature(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string',
            'icon_background_color' => 'required|string',
        ]);

        $feature = Feature::create($validated);
        return response()->json($feature, 201);
    }

    public function updateFeature(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string',
            'icon_background_color' => 'required|string',
        ]);

        $feature->update($validated);
        return response()->json($feature);
    }

    public function deleteFeature(Feature $feature)
    {
        $feature->delete();
        return response()->json(['message' => 'Feature deleted successfully']);
    }

    // Testimonials
    public function getTestimonials()
    {
        return response()->json(Testimonial::ordered()->get());
    }

    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'author_name' => 'required|string|max:255',
            'author_title' => 'required|string|max:255',
            'author_avatar' => 'nullable|string',
            'author_initials' => 'nullable|string|max:5',
        ]);

        $testimonial = Testimonial::create($validated);
        return response()->json($testimonial, 201);
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'author_name' => 'required|string|max:255',
            'author_title' => 'required|string|max:255',
            'author_avatar' => 'nullable|string',
            'author_initials' => 'nullable|string|max:5',
        ]);

        $testimonial->update($validated);
        return response()->json($testimonial);
    }

    public function deleteTestimonial(Testimonial $testimonial)
    {
        $testimonial->delete();
        return response()->json(['message' => 'Testimonial deleted successfully']);
    }

    // Gallery Items
    public function getGalleryItems()
    {
        return response()->json(GalleryItem::ordered()->get());
    }

    public function storeGalleryItem(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string',
            'background_gradient_start' => 'required|string',
            'background_gradient_end' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $galleryItem = GalleryItem::create($validated);
        return response()->json($galleryItem, 201);
    }

    public function updateGalleryItem(Request $request, GalleryItem $galleryItem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string',
            'background_gradient_start' => 'required|string',
            'background_gradient_end' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $galleryItem->update($validated);
        return response()->json($galleryItem);
    }

    public function deleteGalleryItem(GalleryItem $galleryItem)
    {
        $galleryItem->delete();
        return response()->json(['message' => 'Gallery item deleted successfully']);
    }

    // News & Events
    public function getNewsEvents()
    {
        return response()->json(NewsEvent::ordered()->get());
    }

    public function storeNewsEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'nullable|string',
            'icon' => 'required|string',
            'background_gradient_start' => 'required|string',
            'background_gradient_end' => 'required|string',
            'featured_image' => 'nullable|string',
            'event_date' => 'nullable|date',
            'type' => 'required|in:news,event',
        ]);

        $newsEvent = NewsEvent::create($validated);
        return response()->json($newsEvent, 201);
    }

    public function updateNewsEvent(Request $request, NewsEvent $newsEvent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'nullable|string',
            'icon' => 'required|string',
            'background_gradient_start' => 'required|string',
            'background_gradient_end' => 'required|string',
            'featured_image' => 'nullable|string',
            'event_date' => 'nullable|date',
            'type' => 'required|in:news,event',
        ]);

        $newsEvent->update($validated);
        return response()->json($newsEvent);
    }

    public function deleteNewsEvent(NewsEvent $newsEvent)
    {
        $newsEvent->delete();
        return response()->json(['message' => 'News/Event deleted successfully']);
    }
    
}
