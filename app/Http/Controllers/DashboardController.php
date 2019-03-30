<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Dashboard main page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        return view('index');
    }
}
