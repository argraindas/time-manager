<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    /**
     * CategoriesController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return Category[]
     */
    public function index()
    {
        return auth()->user()->categories()->paginate(2);
    }

    /**
     * @return Category|\Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $validData = request()->validate([
            'name' => [
                'required',
                'min:3',
                'max:255',
                Rule::unique('categories')->where('user_id', auth()->id()),
            ]
        ]);

        $validData['user_id'] = auth()->id();

        return Category::create($validData);
    }

    /**
     * @param Category $category
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Category $category)
    {
        $this->authorize('update', $category);

        $validData = request()->validate([
            'name' => [
                'required',
                'min:3',
                'max:255',
                Rule::unique('categories')->where('user_id', auth()->id())->ignore($category->id),
            ]
        ]);

        $category->update($validData);
    }

    /**
     * @param Category $category
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Category $category)
    {
        $this->authorize('update', $category);

        $success = (false !== $category->delete());

        if (request()->expectsJson()) {
            return response([
                'status' => ($success ? 'success' : 'error'),
                'message' => ($success ? 'Category deleted!' : 'Error occurred!'),
            ]);
        }

        return back();
    }
}
