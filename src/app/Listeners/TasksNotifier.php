<?php

namespace App\Listeners;

use Log;
use App\Events\TaskWasCreated;
use App\Jobs\SendManagerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TasksNotifier {
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Sends a notification to a user
     * 
     * @param TaskWasCreated $task
     */
    public static function onTaskWasCreated(TaskWasCreated $createdTask) {
        dispatch(new SendManagerNotification($createdTask->task));
    }


    /**
     * Register the listeners for the subscriver
     * 
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events) {
        $events->listen(
            'App\Events\TaskWasCreated', 
            'App\Listeners\TasksNotifier@onTaskWasCreated'
        );
    }
}
