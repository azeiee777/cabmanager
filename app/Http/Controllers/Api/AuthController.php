<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(['identifier' => 'required', 'password' => 'required']);

        $user = User::where('identifier', $request->identifier)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, true);
            return response()->json(['message' => 'Login successful', 'user' => $user]);
        }

        return response()->json(['error' => 'Invalid credentials. Please check your email/mobile and password.'], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'identifier' => [
                'required',
                'unique:users',
                // Custom rule to ensure it's either an email OR a 10-digit number
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^[6-9]\d{9}$/', $value)) {
                        $fail('The identifier must be a valid email address or a 10-digit Indian mobile number.');
                    }
                },
            ],
            'password' => 'required|min:6',
            'cab_number' => [
                'nullable',
                'string',
                'regex:/^([A-Z]{2}[0-9]{1,2}[A-Z]{1,2}[0-9]{4})|([0-9]{2}BH[0-9]{4}[A-Z]{1,2})$/i'
            ]
        ], [
            'identifier.unique' => 'This email or mobile number is already registered.',
            'password.min' => 'Password must be at least 6 characters long.',
            'cab_number.regex' => 'Please enter a valid Indian vehicle number (e.g., DL01AB1234 or 21BH1234A) without spaces.'
        ]);

        // Format cab number
        $cabNumber = $request->cab_number ? strtoupper(str_replace(' ', '', $request->cab_number)) : 'DL 1Z 9999';

        $user = User::create([
            'identifier' => $request->identifier,
            'password' => Hash::make($request->password),
            'cab_number' => $cabNumber
        ]);

        Auth::login($user, true);
        return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully']);
    }
}