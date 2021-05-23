<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Models\Users;
use App\Models\Tasks;
use App\Models\UsersType;
use App\Events\TaskWasCreated;
use App\Listeners\TasksNotifier;


class TaskNotificationTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function expects_to_call_taskWasCreated_event() {
        $this->expectsEvents('App\Events\TaskWasCreated');

        // Test Manager User
        $user       = Users::where('name', 'test_technician')->first();
        $summary    = 'test_case_1';
        $date       = date('Y-m-d H:i:s');
        
        $request = [
            'date'      => $date,
            'summary'   => $summary,
        ];
        
        $this->actingAs($user)->json('POST', '/api/tasks/create', $request);
    }

    /** @test */
    public function expects_to_call_sendManagerNotification_job() {
        $this->expectsJobs('App\Jobs\SendManagerNotification');

        try {
            $task = Tasks::create(array(
                'date'      => date('Y-m-d H:i:s'),
                'summary'   => 'test_case_1',
                'userId'    => 1,
                'createdAt' => date('Y-m-d H:i:s')
            ));
    
            $taskWasCreated = new TaskWasCreated($task);
            TasksNotifier::onTaskWasCreated($taskWasCreated);
        } catch(\Exceptio $e) {
            Log::error($e);
            $this->assertTrue(false);
        }
    }



}
