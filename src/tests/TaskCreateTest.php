<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutMiddleware;
use Laravel\Lumen\Testing\WithoutEvents;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

use App\Models\Users;
use App\Models\UsersType;

class TaskCreateTest extends TestCase {
    use DatabaseTransactions;
    use WithoutEvents;
    use WithoutMiddleware;

    /** @test */
    public function it_returns_the_task_on_successfully_creating_a_new_one() {
        $date       = date('Y-m-d H:i:s');
        $summary    = 'test_case_1';
        $user       = Users::where('name', 'test_technician')->first();
        
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
    public function it_returns_422_when_no_date_is_sent() {
        $summary    = 'test_case_1';
        
        $request = [
            'summary'   => $summary,
        ];
        
        $response = $this->json('POST', '/api/tasks/create', $request);

        $response->assertResponseStatus(422);
    }

    /** @test */
    public function it_returns_422_when_no_summary_is_sent() {
        $date       = date('Y-m-d H:i:s');
        
        $request = [
            'date'   => $date,
        ];
        
        $response = $this->json('POST', '/api/tasks/create', $request);

        $response->assertResponseStatus(422);
    }


    /** @test */
    public function it_returns_422_when_summary_is_higher_than_2500() {
        $date       = date('Y-m-d H:i:s');
        
        $request = [
            'date'      => $date,
            'summary'   => Str::random(2501)
        ];
        
        $response = $this->json('POST', '/api/tasks/create', $request);

        $response->assertResponseStatus(422);
    }

    /** @test */
    public function it_returns_422_when_date_field_is_not_a_date() {
        $date       = 'testing';
        
        $request = [
            'date'      => $date,
            'summary'   => Str::random(212)
        ];
        
        $response = $this->json('POST', '/api/tasks/create', $request);

        $response->assertResponseStatus(422);
    }

}
