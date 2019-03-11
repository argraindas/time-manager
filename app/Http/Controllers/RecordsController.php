<?php

namespace App\Http\Controllers;

use App\Category;
use App\Record;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Record::where('user_id', auth()->user()->id)->latest()->get();

        return view('records.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->user()->id)->get();

        return view('records.create', compact('categories'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        // Todo: better validation for category_id | must exists | belongs to user

        $validData = request()->validate([
            'time_start' => 'required|date',
            'time_end' => 'nullable|date',
            'description' => 'required|min:3|max:255',
            'category_id' => 'required',
        ]);
        $validData['user_id'] = auth()->id();

        Record::create($validData);

        return redirect(route('records'))->with('flash', 'Record was successfully added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Record $logger
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Record $logger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Record $logger
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Record $logger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Record              $logger
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $logger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Record  $logger
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $logger)
    {
        //
    }
}
