<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $today = Carbon::today()->format('Y-m-d');
            $startOfMonth = Carbon::today()->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::today()->endOfMonth()->format('Y-m-d');

            // --- Filtered Data (For Main Card & Recent Activity) ---
            $ridesQuery = $user->rides()->orderBy('date', 'desc');
            $expensesQuery = $user->expenses()->orderBy('date', 'desc');

            if ($request->filled('from_date')) {
                $ridesQuery->where('date', '>=', $request->from_date);
                $expensesQuery->where('date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $ridesQuery->where('date', '<=', $request->to_date);
                $expensesQuery->where('date', '<=', $request->to_date);
            }

            $filteredRides = $ridesQuery->get();
            $filteredExpenses = $expensesQuery->get();

            // Calculate Filtered Stats safely ensuring floats
            $filteredNet = floatval($filteredRides->sum('fare')) - floatval($filteredExpenses->sum('amount'));
            $filteredKm = floatval($filteredRides->sum('km')) + floatval($filteredRides->sum('deadhead_km'));

            // --- Static Database Queries for the Grid ---
            // Using direct sum queries is faster and avoids collection iteration issues
            $todayRideFare = floatval($user->rides()->where('date', $today)->sum('fare'));
            $todayExpenseAmount = floatval($user->expenses()->where('date', $today)->sum('amount'));
            $todayKm = floatval($user->rides()->where('date', $today)->sum('km')) + floatval($user->rides()->where('date', $today)->sum('deadhead_km'));

            $monthlyRideFare = floatval($user->rides()->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('fare'));
            $monthlyExpenseAmount = floatval($user->expenses()->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount'));
            $monthlyKm = floatval($user->rides()->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('km')) + floatval($user->rides()->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('deadhead_km'));

            $stats = [
                'filteredNet' => $filteredNet,
                'filteredKm' => $filteredKm,
                'filteredRidesCount' => $filteredRides->count(),

                'todayNet' => $todayRideFare - $todayExpenseAmount,
                'todayKm' => $todayKm,

                'monthlyNet' => $monthlyRideFare - $monthlyExpenseAmount,
                'monthlyKm' => $monthlyKm,
            ];

            return response()->json([
                'stats' => $stats,
                // ->values()->all() forces Laravel to send a pure JSON Array, 
                // preventing JavaScript .forEach() from crashing on the frontend!
                'recent_rides' => $filteredRides->take(3)->values()->all(),
                'recent_expenses' => $filteredExpenses->take(2)->values()->all()
            ]);

        } catch (\Exception $e) {
            // Return actual error context if something crashes
            return response()->json(['error' => 'Calculation error: ' . $e->getMessage()], 500);
        }
    }
}