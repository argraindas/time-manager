<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Response;

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
     * @param CreateCategoryRequest $request
     * @param Category              $category
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(CreateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return $this->response('Category was successfully updated!');
    }

    /**
     * @param CreateCategoryRequest $request
     * @param Category              $category
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Exception
     */
    public function destroy(CreateCategoryRequest $request, Category $category)
    {
        $request->validated();
        $category->delete();

        return $this->response('Category was successfully deleted!');
    }
}
