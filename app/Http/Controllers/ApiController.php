<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    // --- AUTHENTICATION ---
    public function login(Request $request)
    {
        $user = User::where('identifier', $request->identifier)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, true);
            return response()->json(['user' => $user]);
        }
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function register(Request $request)
    {
        $request->validate(['identifier' => 'required|unique:users', 'password' => 'required|min:6']);
        $user = User::create([
            'identifier' => $request->identifier,
            'password' => Hash::make($request->password),
            'cab_number' => $request->cab_number ?? 'DL 1Z 9999'
        ]);
        Auth::login($user, true);
        return response()->json(['user' => $user]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['status' => 'success']);
    }

    // --- DATA ENDPOINTS ---
    public function getData()
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'rides' => $user->rides()->orderBy('date', 'desc')->get(),
            'expenses' => $user->expenses()->orderBy('date', 'desc')->get()
        ]);
    }

    public function storeRide(Request $request)
    {
        $ride = Auth::user()->rides()->create($request->all());
        return response()->json($ride);
    }

    public function destroyRide($id)
    {
        Auth::user()->rides()->where('id', $id)->delete();
        return response()->json(['status' => 'deleted']);
    }

    public function storeExpense(Request $request)
    {
        $expense = Auth::user()->expenses()->create($request->all());
        return response()->json($expense);
    }

    public function destroyExpense($id)
    {
        Auth::user()->expenses()->where('id', $id)->delete();
        return response()->json(['status' => 'deleted']);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only(['cab_number', 'pin']));
        return response()->json($user);
    }
}
