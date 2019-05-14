<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Task;

class TaskStatusController extends Controller
{

    /**
     * @param Task $task
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Task $task)
    {
        $this->authorize('taskUpdate', [$task->card, $task]);

        $validData = request()->validate([
            'status' => 'required'
        ]);

        $message = '';
        switch ($validData['status']) {
            case Task::STATUS_DONE: {
                $task->done();
                $message = 'Task was done!';
                break;
            }
            case Task::STATUS_IN_PROGRESS: {
                $task->inProgress();
                $message = 'Task is in progress!';
                break;
            }
            case Task::STATUS_REJECTED: {
                $task->rejected();
                $message = 'Task is rejected!';
                break;
            }
            case Task::STATUS_NEW: {
                $task->new();
                $message = 'Task is new!';
                break;
            }
        }

        return $this->response($message);
    }
}
