<?php

namespace App\Http\Controllers;

class RecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $records = auth()->user()->records;

        return view('records.index', compact('records'));
    }
}
