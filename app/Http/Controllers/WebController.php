<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function ride()
    {
        return view('ride');
    }

    public function expense()
    {
        $categories = ['Fuel', 'Maintenance', 'MCD Toll', 'Paid Toll', 'Challan', 'EMI', 'Cleaning', 'Other'];
        return view('expense', compact('categories'));
    }

    public function tolls()
    {
        return view('tolls');
    }

    public function history()
    {
        return view('history');
    }

    public function settings()
    {
        return view('settings');
    }
}