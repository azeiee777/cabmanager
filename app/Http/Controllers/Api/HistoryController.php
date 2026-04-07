<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index(Request $request) 
    {
        $user = Auth::user();
        $perPage = 15;
        $page = (int) $request->input('page', 1);

        $ridesQuery = $this->applyDateFilters(
            $user->rides()->selectRaw("
                id,
                date,
                created_at,
                'ride' as record_type,
                fare,
                km,
                deadhead_km,
                origin,
                destination,
                mcd_toll,
                paid_toll,
                NULL as amount,
                NULL as type,
                NULL as description
            "),
            $request
        );

        $expensesQuery = $this->applyDateFilters(
            $user->expenses()->selectRaw("
                id,
                date,
                created_at,
                'expense' as record_type,
                NULL as fare,
                NULL as km,
                NULL as deadhead_km,
                NULL as origin,
                NULL as destination,
                NULL as mcd_toll,
                NULL as paid_toll,
                amount,
                type,
                description
            "),
            $request
        );

        $paginator = DB::query()
            ->fromSub($ridesQuery->unionAll($expensesQuery), 'history')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'total' => $paginator->total(),
        ]);
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
}
