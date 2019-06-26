<?php

namespace App\Events;

use App\Card;
use App\Http\Resources\TaskResource;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var TaskResource */
    public $task;

    /** @var Card */
    protected $card;

    /**
     * Create a new event instance.
     *
     * @param TaskResource $task
     * @param Card         $card
     */
    public function __construct(TaskResource $task, Card $card)
    {
        $this->task = $task;
        $this->card = $card;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('tasks.'. $this->card->uuid);
    }
}
