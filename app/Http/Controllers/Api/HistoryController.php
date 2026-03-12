<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request) 
    {
        $user = Auth::user();
        $perPage = 15;
        $page = (int) $request->input('page', 1);

        $ridesQuery = $user->rides();
        $expensesQuery = $user->expenses();

        if ($request->filled('from_date')) {
            $ridesQuery->where('date', '>=', $request->from_date);
            $expensesQuery->where('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $ridesQuery->where('date', '<=', $request->to_date);
            $expensesQuery->where('date', '<=', $request->to_date);
        }

        // Fetch and label records
        $rides = $ridesQuery->get()->map(function($item) {
            $item->record_type = 'ride';
            return $item;
        });

        $expenses = $expensesQuery->get()->map(function($item) {
            $item->record_type = 'expense';
            return $item;
        });

        // Combine and sort by date descending, then created_at descending
        $combined = $rides->concat($expenses)->sortBy([
            ['date', 'desc'],
            ['created_at', 'desc']
        ])->values();
        
        // Manual Pagination for the collection
        $total = $combined->count();
        $items = $combined->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'data' => $items,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'total' => $total
        ]);
    }
}