<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
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
     * @param CategoryRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return $this->response('Category was successfully created!', 'success', Response::HTTP_CREATED);
    }

    /**
     * @param CategoryRequest $request
     * @param Category        $category
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return $this->response('Category was successfully updated!');
    }

    /**
     * @param CategoryRequest $request
     * @param Category        $category
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Exception
     */
    public function destroy(CategoryRequest $request, Category $category)
    {
        $request->validated();
        $category->delete();

        return $this->response('Category was successfully deleted!');
    }
}
