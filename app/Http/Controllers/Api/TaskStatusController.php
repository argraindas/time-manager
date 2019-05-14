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
                $message = 'Marked as done!';
                break;
            }
            case Task::STATUS_IN_PROGRESS: {
                $task->inProgress();
                $message = 'Marked as in progress!';
                break;
            }
            case Task::STATUS_REJECTED: {
                $task->rejected();
                $message = 'Marked as rejected!';
                break;
            }
            case Task::STATUS_NEW: {
                $task->new();
                $message = 'Marked as uncompleted!';
                break;
            }
        }

        return $this->response($message);
    }
}
