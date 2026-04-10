<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Nếu là Admin
        if ($user->role === 'admin') {
            return view('admin.dashboard');
        }

        // Nếu là Enterprise
        if ($user->role === 'enterprise') {
            if ($user->status === 'pending') {
                return view('enterprise.pending');
            }
            return view('enterprise.dashboard');
        }

        // Default redirect về home nếu không xác định
        return redirect()->route('home');
    }
}