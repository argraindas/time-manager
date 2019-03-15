<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
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

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $validData = request()->validate([
            'name' => 'required|min:3|max:255'
        ]);

        $validData['user_id'] = auth()->id();

        return Category::create($validData);
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  \App\Category  $category
//     * @return \Illuminate\Http\Response
//     */
//    public function edit(Category $category)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  \App\Category  $category
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, Category $category)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  \App\Category  $category
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy(Category $category)
//    {
//        //
//    }
}
