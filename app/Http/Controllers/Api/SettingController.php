<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'cab_number' => [
                'required',
                'string',
                // Regex for standard Indian format (e.g., UP32AB1234) OR BH series (e.g., 21BH1234A or 21BH1234AA)
                'regex:/^([A-Z]{2}[0-9]{1,2}[A-Z]{1,2}[0-9]{4})|([0-9]{2}BH[0-9]{4}[A-Z]{1,2})$/i'
            ],
            'pin' => 'required|string|digits:4'
        ], [
            'cab_number.regex' => 'Please enter a valid Indian vehicle number (e.g., DL01AB1234 or 21BH1234A) without spaces.',
            'pin.digits' => 'The PIN must be exactly 4 digits.'
        ]);

        // Format to uppercase before saving
        $validated['cab_number'] = strtoupper(str_replace(' ', '', $validated['cab_number']));

        Auth::user()->update($validated);

        return response()->json([
            'message' => 'Settings updated successfully',
            'user' => Auth::user()
        ]);
    }
}