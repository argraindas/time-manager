<?php

namespace App\Http\Controllers\Api;

use App\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\TaskRequest;

class TasksController extends Controller
{
    /**
     * @param TaskRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(TaskRequest $request)
    {
        Task::create($request->validated());

        return $this->response('Task was successfully created!', 'success', Response::HTTP_CREATED);
    }

    /**
     * @param TaskRequest $request
     * @param Task        $task
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return $this->response('Task was successfully updated!');
    }
    
    /**
     * @param TaskRequest $request
     * @param Task        $task
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Exception
     */
    public function destroy(TaskRequest $request, Task $task)
    {
        $request->validated();
        $task->delete();

        return $this->response('Task was successfully deleted!');
    }

}
