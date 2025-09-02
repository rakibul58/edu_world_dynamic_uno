<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    // Public API - get only active programs
    public function indexPublic()
    {
        return response()->json(Program::where('is_active', true)->orderBy('order')->get());
    }

    // Admin API - get all programs (including inactive)
    public function indexAdmin()
    {
        return response()->json(Program::orderBy('order')->get());
    }

    public function store(Request $request)
    {
        $program = Program::create($request->all());
        return response()->json($program, 201);
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);
        $program->update($request->all());
        return response()->json($program);
    }

    public function destroy($id)
    {
        Program::destroy($id);
        return response()->json(null, 204);
    }
}
