<?php

namespace App\Http\Controllers;

use App\Record;
use App\Rules\ValidCategory;
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = auth()->user()->categories;

        return view('records.create', compact('categories'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $validData = request()->validate([
            'time_start' => 'required|date',
            'time_end' => 'nullable|date',
            'description' => 'required|min:3|max:255',
            'category_id' => ['required', new ValidCategory],
        ]);
        $validData['user_id'] = auth()->id();

        Record::create($validData);

        return redirect(route('records'))->with('flash', 'Record was successfully added!');
    }

    /**
     * Display the record
     *
     * @param  \App\Record $record
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Record $record)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Record $record
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Record $record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Record              $record
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $record)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Record  $record
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $record)
    {
        //
    }
}
