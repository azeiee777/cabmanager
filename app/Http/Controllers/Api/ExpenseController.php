<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        return response()->json(['expenses' => Auth::user()->expenses()->orderBy('date', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);
        $expense = Auth::user()->expenses()->create($validated);
        return response()->json(['message' => 'Expense saved successfully', 'expense' => $expense], 201);
    }

    // NEW: Fetch single expense for Edit Form
    public function show($id)
    {
        $expense = Auth::user()->expenses()->findOrFail($id);
        return response()->json(['expense' => $expense]);
    }

    // NEW: Update existing expense
    public function update(Request $request, $id)
    {
        $validated = $this->validateExpense($request);
        $expense = Auth::user()->expenses()->findOrFail($id);
        $expense->update($validated);
        return response()->json(['message' => 'Expense updated successfully', 'expense' => $expense]);
    }

    // NEW: Delete expense
    public function destroy($id)
    {
        Auth::user()->expenses()->findOrFail($id)->delete();
        return response()->json(['message' => 'Expense deleted successfully']);
    }

    // Helper validation method
    private function validateExpense(Request $request)
    {
        return $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'description' => 'nullable|string'
        ]);
    }
}