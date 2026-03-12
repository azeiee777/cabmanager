<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\RideController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\TollController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\HistoryController;

// ==========================================
// WEB ROUTES (Returns Blade Views)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/', [WebController::class, 'login'])->name('login.view');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard.view');
    Route::get('/ride', [WebController::class, 'ride'])->name('ride.view');
    Route::get('/expense', [WebController::class, 'expense'])->name('expense.view');
    Route::get('/tolls', [WebController::class, 'tolls'])->name('tolls.view');
    Route::get('/history', [WebController::class, 'history'])->name('history.view');
    Route::get('/settings', [WebController::class, 'settings'])->name('settings.view');
});

// ==========================================
// API ROUTES (Handles DB & Authentication)
// ==========================================
Route::prefix('api')->name('api.')->group(function () {
    
    // Auth Routes
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.data');
        
        // Ride Endpoints
        Route::get('/rides', [RideController::class, 'index'])->name('rides.index');
        Route::post('/rides', [RideController::class, 'store'])->name('rides.store');
        Route::get('/rides/{id}', [RideController::class, 'show'])->name('rides.show');
        Route::put('/rides/{id}', [RideController::class, 'update'])->name('rides.update');
        Route::delete('/rides/{id}', [RideController::class, 'destroy'])->name('rides.destroy');
        
        // Expense Endpoints
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('/expenses/{id}', [ExpenseController::class, 'show'])->name('expenses.show');
        Route::put('/expenses/{id}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
        
        Route::get('/tolls', [TollController::class, 'index'])->name('tolls.data');
        Route::get('/history', [HistoryController::class, 'index'])->name('history.data');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.data');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});