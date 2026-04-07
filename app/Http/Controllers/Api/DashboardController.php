<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $today = Carbon::today()->format('Y-m-d');
            $startOfMonth = Carbon::today()->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::today()->endOfMonth()->format('Y-m-d');

            $filteredRidesQuery = $this->applyDateFilters($user->rides(), $request);
            $filteredExpensesQuery = $this->applyDateFilters($user->expenses(), $request);

            $filteredNet = (float) (clone $filteredRidesQuery)->sum('fare') - (float) (clone $filteredExpensesQuery)->sum('amount');
            $filteredKm = $this->sumRideDistance(clone $filteredRidesQuery);

            $todayRidesQuery = $user->rides()->where('date', $today);
            $todayExpensesQuery = $user->expenses()->where('date', $today);
            $monthlyRidesQuery = $user->rides()->whereBetween('date', [$startOfMonth, $endOfMonth]);
            $monthlyExpensesQuery = $user->expenses()->whereBetween('date', [$startOfMonth, $endOfMonth]);

            $todayRideFare = (float) (clone $todayRidesQuery)->sum('fare');
            $todayExpenseAmount = (float) (clone $todayExpensesQuery)->sum('amount');
            $todayKm = $this->sumRideDistance(clone $todayRidesQuery);

            $monthlyRideFare = (float) (clone $monthlyRidesQuery)->sum('fare');
            $monthlyExpenseAmount = (float) (clone $monthlyExpensesQuery)->sum('amount');
            $monthlyKm = $this->sumRideDistance(clone $monthlyRidesQuery);

            $stats = [
                'filteredNet' => $filteredNet,
                'filteredKm' => $filteredKm,
                'filteredRidesCount' => (clone $filteredRidesQuery)->count(),

                'todayNet' => $todayRideFare - $todayExpenseAmount,
                'todayKm' => $todayKm,

                'monthlyNet' => $monthlyRideFare - $monthlyExpenseAmount,
                'monthlyKm' => $monthlyKm,
            ];

            return response()->json([
                'stats' => $stats,
                'recent_rides' => (clone $filteredRidesQuery)
                    ->select(['id', 'date', 'fare', 'km', 'deadhead_km', 'origin', 'destination', 'mcd_toll', 'paid_toll', 'created_at'])
                    ->orderByDesc('date')
                    ->orderByDesc('created_at')
                    ->limit(3)
                    ->get()
                    ->values()
                    ->all(),
                'recent_expenses' => (clone $filteredExpensesQuery)
                    ->select(['id', 'date', 'amount', 'type', 'description', 'created_at'])
                    ->orderByDesc('date')
                    ->orderByDesc('created_at')
                    ->limit(2)
                    ->get()
                    ->values()
                    ->all(),
            ]);

        } catch (\Exception $e) {
            // Return actual error context if something crashes
            return response()->json(['error' => 'Calculation error: ' . $e->getMessage()], 500);
        }
    }

    private function applyDateFilters(Builder|HasMany $query, Request $request): Builder|HasMany
    {
        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->input('to_date'));
        }

        return $query;
    }

    private function sumRideDistance(Builder|HasMany $query): float
    {
        return (float) ($query
            ->selectRaw('COALESCE(SUM(COALESCE(km, 0) + COALESCE(deadhead_km, 0)), 0) as total_distance')
            ->value('total_distance') ?? 0);
    }
}
