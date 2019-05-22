<?php

namespace App\Http\Controllers\Api;

use App\Card;
use App\Http\Resources\TaskResource;
use App\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\TaskRequest;

class TasksController extends Controller
{
    /**
     * @param TaskRequest $request
     * @param Card        $card
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(TaskRequest $request, Card $card)
    {
        $task = $card->addTask($request->validated());

        return response([
            'status' => 'success',
            'message' => 'Task was added!',
            'item' => new TaskResource($task->fresh()),
        ], Response::HTTP_CREATED);
    }

    /**
     * @param TaskRequest $request
     * @param Card        $card
     * @param Task        $task
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(TaskRequest $request, Card $card, Task $task)
    {
        $task->update($request->validated());

        return $this->response('Task was updated!');
    }

    /**
     * @param TaskRequest $request
     * @param Card        $card
     * @param Task        $task
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(TaskRequest $request, Card $card, Task $task)
    {
        $request->validated();
        $card->removeTask($task);

        return $this->response('Task was deleted!');
    }

}
