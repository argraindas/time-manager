<?php

namespace App\Listener;

use App\Events\CategoryWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCategoryCreatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CategoryWasCreated  $event
     * @return void
     */
    public function handle(CategoryWasCreated $event)
    {
        //
    }
}
