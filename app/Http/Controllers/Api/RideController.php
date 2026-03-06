<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{
    public function index()
    {
        return response()->json(['rides' => Auth::user()->rides()->orderBy('date', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRide($request);
        $ride = Auth::user()->rides()->create($validated);
        return response()->json(['message' => 'Ride saved successfully', 'ride' => $ride], 201);
    }

    // NEW: Fetch single ride for Edit Form
    public function show($id)
    {
        $ride = Auth::user()->rides()->findOrFail($id);
        return response()->json(['ride' => $ride]);
    }

    // NEW: Update existing ride
    public function update(Request $request, $id)
    {
        $validated = $this->validateRide($request);
        $ride = Auth::user()->rides()->findOrFail($id);
        $ride->update($validated);
        return response()->json(['message' => 'Ride updated successfully', 'ride' => $ride]);
    }

    // NEW: Delete ride
    public function destroy($id)
    {
        Auth::user()->rides()->findOrFail($id)->delete();
        return response()->json(['message' => 'Ride deleted successfully']);
    }

    // Helper validation method to keep code clean
    private function validateRide(Request $request)
    {
        return $request->validate([
            'date' => 'required|date',
            'fare' => 'required|numeric',
            'km' => 'required|numeric',
            'deadhead_km' => 'nullable|numeric',
            'origin' => 'nullable|string',
            'destination' => 'nullable|string',
            'mcd_toll' => 'nullable|numeric',
            'paid_toll' => 'nullable|numeric'
        ]);
    }
}