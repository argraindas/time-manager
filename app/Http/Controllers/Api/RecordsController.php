<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RecordResource;
use App\Record;
use App\Rules\ValidCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return RecordResource::collection(
            auth()->user()->records()->paginate(config('general.pagination.perPage'))
        );
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store()
    {
        $validData = request()->validate([
            'time_start' => 'required|date',
            'time_end' => 'nullable|date',
            'description' => 'required|min:3|max:255',
            'category_id' => ['required', new ValidCategory()],
        ]);
        $validData['user_id'] = auth()->id();

        Record::create($validData);

        return $this->response('Record was successfully added!', 'success', Response::HTTP_CREATED);
    }

    /**
     * @param Record $record
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Record $record)
    {
        $this->authorize('update', $record);

        $record->delete();

        return $this->response('Record was successfully deleted!');
    }

    /**
     * @param Record $record
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Record $record)
    {
        $this->authorize('update', $record);

        $validData = request()->validate([
            'time_start' => 'required|date',
            'time_end' => 'nullable|date',
            'description' => 'required|min:3|max:255',
            'category_id' => ['required', new ValidCategory()],
        ]);

        $record->update($validData);

        return $this->response('Record was successfully updated!');
    }
}
