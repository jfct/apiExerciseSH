<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutMiddleware;
use Laravel\Lumen\Testing\WithoutEvents;

use Illuminate\Support\Facades\Artisan;

use App\Models\Users;
use App\Models\UsersType;

class TaskListAuthTest extends TestCase {
    use DatabaseTransactions;
    use WithoutEvents;

    /** @test */
    public function it_returns_403_when_a_user_of_type_technician_tries_to_list_all_tasks() {
        $user = Users::where('name', 'test_technician')->first();
        
        $response = $this->actingAs($user)->json('GET', '/api/tasks/');
        $response->assertResponseStatus(403);
    }

    /** @test */
    public function it_returns_200_when_a_user_of_type_manager_tries_to_list_all_tasks() {
        $user = Users::where('name', 'test_manager')->first();
        
        $response = $this->actingAs($user)->json('GET', '/api/tasks/');
        $response->assertResponseStatus(200);
    }

    /** @test */
    public function it_returns_200_when_a_user_of_type_technician_tries_to_list_all_tasks_by_him() {
        $user = Users::where('name', 'test_technician')->first();

        $response = $this->actingAs($user)->json('GET', '/api/tasks/user/' . $user->id);
        $response->assertResponseStatus(200);
    }

    /** @test */
    public function it_returns_200_when_a_user_of_type_manager_tries_to_list_all_tasks_by_another_user() {
        $user       = Users::where('name', 'test_manager')->first();
        $technician = Users::where('name', 'test_technician')->first(); 
        
        $response = $this->actingAs($user)->json('GET', '/api/tasks/user/' . $technician->id);
        $response->assertResponseStatus(200);
    }

    /** @test */
    public function it_returns_401_when_trying_to_list_all_tasks_without_auth() {
        $response = $this->json('GET', '/api/tasks/');
        $response->assertResponseStatus(401);
    }

    /** @test */
    public function listing_all_tasks_shows_the_correct_amount_of_tasks() {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'TestTaskSeeder']);

        $user       = Users::where('name', 'test_manager')->first();
        $response   = $this->actingAs($user)->json('GET', '/api/tasks/')->response;

        $response->assertJsonCount(3);
    }

    /** @test */
    public function listing_all_tasks_by_user_shows_the_correct_tasks() {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'TestTaskSeeder']);

        $user       = Users::where('name', 'test_manager')->first();
        $technician = Users::where('name', 'test_technician')->first(); 
        $response   = $this->actingAs($user)->json('GET', '/api/tasks/user/' . $technician->id);

        $response->seeJson([
            'userId' => $technician->id
        ]);
    }
}
