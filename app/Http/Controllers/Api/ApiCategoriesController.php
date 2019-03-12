<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiCategoriesController extends Controller
{
    /**
     * CategoriesController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        return auth()->user()->categories()->paginate(2);
    }

}
