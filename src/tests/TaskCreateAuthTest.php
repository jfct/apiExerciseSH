<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutEvents;

use Illuminate\Support\Facades\Crypt;

use App\Models\Users;
use App\Models\UsersType;

class TaskCreateAuthTest extends TestCase {
    use DatabaseTransactions;
    use WithoutEvents;

    /** @test */
    public function it_returns_201_when_the_technician_tries_to_create_tasks() {
        // Test Manager User
        $user       = Users::where('name', 'test_technician')->first();
        $summary    = 'test_case_1';
        $date       = date('Y-m-d H:i:s');
        
        $request = [
            'date'      => $date,
            'summary'   => $summary,
        ];
        
        $response = $this->actingAs($user)->json('POST', '/api/tasks/create', $request);

        $response->assertResponseStatus(201);
        $response->seeJson([
            'date'      => $date,
            'userId'    => $user->id
        ]);
        $response->assertEquals($summary, Crypt::decrypt(json_decode($this->response->getContent())->summary));
        $response->shouldReturnJson();
    }

    /** @test */
    public function it_returns_403_when_the_manager_tries_to_create_tasks() {
        // Test Manager User
        $user                   = Users::where('name', 'test_manager')->first();
        $summary                = 'test_case_1';
        $date                   = date('Y-m-d H:i:s');
        
        $request = [
            'date'      => $date,
            'summary'   => $summary,
        ];
        
        $response = $this->actingAs($user)->json('POST', '/api/tasks/create', $request);
        $response->assertResponseStatus(403);
    }

    /** @test */
    public function it_returns_an_401_when_trying_to_create_without_auth() {
        $response = $this->json('POST', '/api/tasks/create', []);
        $response->assertResponseStatus(401);
    }

}
