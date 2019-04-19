<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = auth()->user();

        $categories = request()->query('page') ?
            $user->categories()->paginate(config('general.pagination.perPage')) :
            $user->categories;

        return CategoryResource::collection($categories);
    }

    /**
     * @param CreateCategoryRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(CreateCategoryRequest $request)
    {
        Category::create($request->validated());

        return $this->response('Category was successfully created!', 'success', Response::HTTP_CREATED);
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

        return $this->response('Category was successfully updated!');
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

        return $this->response('Category was successfully deleted!');
    }
}
