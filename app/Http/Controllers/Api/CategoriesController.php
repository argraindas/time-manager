<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    /**
     * @return Category[]
     */
    public function index()
    {
        return auth()->user()->categories()->paginate(config('general.pagination.perPage'));
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

        Category::create($validData);

        return $this->response('Category created!', 'success', Response::HTTP_CREATED);
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

        return $this->response('Category updated!');
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

        $category->delete();

        return $this->response('Category deleted!');
    }
}
