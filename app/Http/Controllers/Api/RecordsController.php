<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RecordRequest;
use App\Http\Resources\RecordResource;
use App\Record;
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
     * @param RecordRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(RecordRequest $request)
    {
        Record::create($request->validated());

        return $this->response('Record was successfully added!', 'success', Response::HTTP_CREATED);
    }

    /**
     * @param RecordRequest $request
     * @param Record        $record
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(RecordRequest $request, Record $record)
    {
        $record->update($request->validated());

        return $this->response('Record was successfully updated!');
    }

    /**
     * @param RecordRequest $request
     * @param Record        $record
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Exception
     */
    public function destroy(RecordRequest $request, Record $record)
    {
        $request->validated();
        $record->delete();

        return $this->response('Record was successfully deleted!');
    }
}
