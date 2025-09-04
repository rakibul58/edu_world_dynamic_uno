<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdmissionProcess;
use Illuminate\Http\Request;

class AdmissionProcessController extends Controller
{
    public function index()
    {
        $processes = AdmissionProcess::with('steps')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($processes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'background_color' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50',
            'padding_top' => 'nullable|string|max:20',
            'padding_bottom' => 'nullable|string|max:20',
            'text_align' => 'nullable|in:left,center,right',
            'title_color' => 'nullable|string|max:50',
            'title_font_size' => 'nullable|string|max:20',
            'title_font_weight' => 'nullable|string|max:10',
            'description_color' => 'nullable|string|max:50',
            'description_font_size' => 'nullable|string|max:20',
            'description_opacity' => 'nullable|string|max:10',
            'step_number_bg_color' => 'nullable|string|max:50',
            'step_number_text_color' => 'nullable|string|max:50',
            'step_title_color' => 'nullable|string|max:50',
            'step_title_font_size' => 'nullable|string|max:20',
            'step_description_color' => 'nullable|string|max:50',
            'step_description_opacity' => 'nullable|string|max:10',
            'grid_min_width' => 'nullable|string|max:20',
            'step_gap' => 'nullable|string|max:20',
            'steps' => 'required|array|min:1',
            'steps.*.title' => 'required|string|max:255',
            'steps.*.description' => 'required|string',
            'steps.*.step_number' => 'required|integer|min:1',
        ]);

        $process = AdmissionProcess::create($validated);

        foreach ($validated['steps'] as $index => $stepData) {
            $process->steps()->create([
                'title' => $stepData['title'],
                'description' => $stepData['description'],
                'step_number' => $stepData['step_number'],
                'sort_order' => $index,
            ]);
        }

        return response()->json($process->load('steps'), 201);
    }

    public function show(AdmissionProcess $admissionProcess)
    {
        return response()->json($admissionProcess->load('steps'));
    }

    public function update(Request $request, AdmissionProcess $admissionProcess)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'background_color' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50',
            'padding_top' => 'nullable|string|max:20',
            'padding_bottom' => 'nullable|string|max:20',
            'text_align' => 'nullable|in:left,center,right',
            'title_color' => 'nullable|string|max:50',
            'title_font_size' => 'nullable|string|max:20',
            'title_font_weight' => 'nullable|string|max:10',
            'description_color' => 'nullable|string|max:50',
            'description_font_size' => 'nullable|string|max:20',
            'description_opacity' => 'nullable|string|max:10',
            'step_number_bg_color' => 'nullable|string|max:50',
            'step_number_text_color' => 'nullable|string|max:50',
            'step_title_color' => 'nullable|string|max:50',
            'step_title_font_size' => 'nullable|string|max:20',
            'step_description_color' => 'nullable|string|max:50',
            'step_description_opacity' => 'nullable|string|max:10',
            'grid_min_width' => 'nullable|string|max:20',
            'step_gap' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
            'steps' => 'required|array|min:1',
            'steps.*.title' => 'required|string|max:255',
            'steps.*.description' => 'required|string',
            'steps.*.step_number' => 'required|integer|min:1',
        ]);

        $admissionProcess->update($validated);

        // Update steps - delete existing and create new ones
        $admissionProcess->steps()->delete();

        foreach ($validated['steps'] as $index => $stepData) {
            $admissionProcess->steps()->create([
                'title' => $stepData['title'],
                'description' => $stepData['description'],
                'step_number' => $stepData['step_number'],
                'sort_order' => $index,
            ]);
        }

        return response()->json($admissionProcess->load('steps'));
    }

    public function destroy(AdmissionProcess $admissionProcess)
    {
        $admissionProcess->delete();

        return response()->json(['message' => 'Admission process deleted successfully']);
    }

    public function activate(AdmissionProcess $admissionProcess)
    {
        $admissionProcess->activate();

        return response()->json([
            'message' => 'Admission process activated successfully',
            'process' => $admissionProcess->load('steps')
        ]);
    }

    public function getActive()
    {
        $activeProcess = AdmissionProcess::getActive();

        if (!$activeProcess) {
            return response()->json(['message' => 'No active admission process found'], 404);
        }

        return response()->json($activeProcess);
    }
}
