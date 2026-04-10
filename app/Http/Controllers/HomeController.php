<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Hiển thị trang Landing Page
     */
    public function index(): View
    {
        return view('welcome');
    }
}