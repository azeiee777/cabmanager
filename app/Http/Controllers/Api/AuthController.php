<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpVerification;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Generates a secure, searchable hash (Blind Index) for the database
    private function getEmailHash($email) 
    {
        return hash_hmac('sha256', strtolower(trim($email)), config('app.key'));
    }

    /**
     * Step 1: Send OTP (Email Only)
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|email',
        ], [
            'identifier.required' => 'Email address is required.',
            'identifier.email' => 'Please provide a valid email address. Phone numbers are disabled.',
        ]);

        $email = strtolower(trim($request->identifier));
        $emailHash = $this->getEmailHash($email);
        
        // CHECK: If user already exists, prevent OTP and return "email already taken"
        if (User::where('identifier_hash', $emailHash)->exists()) {
            return response()->json(['error' => 'Email already taken. Please sign in instead.'], 422);
        }

        $code = rand(100000, 999999);

        OtpVerification::updateOrCreate(
            ['identifier_hash' => $emailHash],
            [
                'identifier' => $email, // Laravel model cast will automatically encrypt this!
                'code' => Hash::make($code),
                'expires_at' => Carbon::now()->addMinutes(10),
                'verified' => false,
            ]
        );

        try {
            Mail::to($email)->send(new OtpMail($code));
            return response()->json(['message' => 'OTP sent successfully to ' . $email]);
        } catch (\Exception $e) {
            Log::error('SMTP Error: ' . $e->getMessage());
            return response()->json(['error' => 'Mail server error. Please check your SMTP settings.'], 500);
        }
    }

    /**
     * Step 2: Finalize Registration
     */
    public function register(Request $request) 
    {
        $request->validate([
            'identifier' => 'required|email', 
            'password' => 'required|min:6',
            'otp' => 'required|digits:6',
            'cab_number' => 'nullable|string'
        ]);

        $email = strtolower(trim($request->identifier));
        $emailHash = $this->getEmailHash($email);

        // Double check during final registration step just in case
        if (User::where('identifier_hash', $emailHash)->exists()) {
            return response()->json(['error' => 'Email already taken. Please sign in instead.'], 422);
        }

        $otpRecord = OtpVerification::where('identifier_hash', $emailHash)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord || !Hash::check($request->otp, $otpRecord->code)) {
            return response()->json(['error' => 'Invalid or expired OTP.'], 422);
        }

        $user = User::create([
            'identifier' => $email, // Laravel encrypts this
            'identifier_hash' => $emailHash, // Searchable hash
            'password' => Hash::make($request->password),
            'cab_number' => strtoupper(str_replace(' ', '', $request->cab_number ?? 'DL 1Z 9999'))
        ]);

        $otpRecord->delete();

        Auth::login($user, true);
        return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    }

    /**
     * Standard Login
     */
    public function login(Request $request) 
    {
        $request->validate([
            'identifier' => 'required|email', 
            'password' => 'required'
        ]);

        $email = strtolower(trim($request->identifier));
        $emailHash = $this->getEmailHash($email);

        $user = User::where('identifier_hash', $emailHash)->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, true);
            return response()->json(['message' => 'Login successful', 'user' => $user]);
        }
        
        return response()->json(['error' => 'Invalid credentials.'], 401);
    }

    public function logout() 
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully']);
    }
}