<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TollController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $ridesQuery = $user->rides();
        $expensesQuery = $user->expenses();

        if ($request->has('from_date') && $request->from_date) {
            $ridesQuery->where('date', '>=', $request->from_date);
            $expensesQuery->where('date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $ridesQuery->where('date', '<=', $request->to_date);
            $expensesQuery->where('date', '<=', $request->to_date);
        }

        $rides = $ridesQuery->get();
        $expenses = $expensesQuery->get();

        $collectedMcd = $rides->sum('mcd_toll');
        $collectedPaid = $rides->sum('paid_toll');
        $spentMcd = $expenses->where('type', 'MCD Toll')->sum('amount');
        $spentPaid = $expenses->where('type', 'Paid Toll')->sum('amount');

        $tollStats = [
            'collectedMcd' => $collectedMcd,
            'spentMcd' => $spentMcd,
            'netMcd' => $collectedMcd - $spentMcd,
            'collectedPaid' => $collectedPaid,
            'spentPaid' => $spentPaid,
            'netPaid' => $collectedPaid - $spentPaid,
            'totalNet' => ($collectedMcd + $collectedPaid) - ($spentMcd + $spentPaid)
        ];

        return response()->json(['tollStats' => $tollStats]);
    }
}