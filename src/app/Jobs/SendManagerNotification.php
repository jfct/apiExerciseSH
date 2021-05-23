<?php

namespace App\Jobs;

use Log;
use App\Jobs\Job;
use App\Jobs\Queue;
use App\Models\Tasks;
use App\Models\Users;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class SendManagerNotification extends Job implements ShouldQueue {
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $task;

    /**
     * Create a new job instance.
     *
     * @param  Tasks  $task
     * @return void
     */
    public function __construct(Tasks $task) {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        // TODO: Validations

        // Get the user associated with the task
        try {
            $user       = Tasks::find($this->task['id'])->user;
            // TODO: find other way to send the message
            Log::alert('################################################################################################');
            Log::alert('The tech "' . $user->name . '" on date "'. $this->task->date .'"');
            Log::alert('Performed the task "' . Crypt::decrypt($this->task->summary) . '"');
            Log::alert('################################################################################################');
        } catch (\Excpetion $e) {
            Log::error($e);
        }
    }
}
