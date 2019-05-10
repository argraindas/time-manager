<?php

namespace Tests\Unit;

use App\Card;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_creator()
    {
        $this->signIn();

        $task = factory(Task::class)->state('withCardAndUser')->create();

        $this->assertInstanceOf(User::class, $task->creator);
    }

    /** @test */
    public function it_belongs_to_card()
    {
        $this->signIn();

        $task = factory(Task::class)->state('withCardAndUser')->create();

        $this->assertInstanceOf(Card::class, $task->card);
    }

}
