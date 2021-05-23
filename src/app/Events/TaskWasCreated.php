<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Tasks;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class TaskWasCreated extends Event /*implements ShouldBroadcast*/ {
    use SerializesModels;

    public $task;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tasks $task) {
        $this->task = $task;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * Could be used to broadcast to a Redis channel so others apps can receive it
     * 
     * @return array
     */
    // public function broadcastOn()
    // {
    //     Log::debug('==== Broadcasted on the "notifications" channel ====');
    //     return ['notifications'];
    // }
}
