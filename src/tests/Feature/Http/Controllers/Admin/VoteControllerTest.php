<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

use App\Repositories\Eloquent\Models\Vote;
use App\Repositories\Eloquent\Models\Vote\Count;
use App\Services\VoteService;
use App\User;

class VoteControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        \App::bind('App\Repositories\VoteRepositoryInterface', 'App\Repositories\Eloquent\VoteRepository');
        $this->service = \App::make(VoteService::class);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testGetList()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        factory(Vote::class,3)->create(["project" => $project]);

        $votes = $this->service->getPageInProject(0,$project);

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/api/votes')
                         ->assertStatus(302);

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/votes')
                         ->assertStatus(200);
    }

    public function testShow()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        factory(Vote::class,3)->create(["project" => $project]);

        $votes = $this->service->getPageInProject(0,$project);
        $vote = $votes->first();

        $user = factory(User::class)->create();
        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/votes/' . $vote->id)
                         ->assertStatus(200)
                         ->assertJson($vote->toArray());

    }

    public function testChart()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $vote = factory(Vote::class)->create(["project" => $project]);
        $counts = factory(Count::class, 10)->create(["vote_code" => $vote->code]);

        $user = factory(User::class)->create();
        $ret = $this->service->getDataSet($project, $vote);
        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/votes/' . $vote->id . '/chart')
                         ->assertStatus(200)
                         ->assertJson($ret);

    }


    public function testCreate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $vote = factory(Vote::class)->make(["project" => $project]);

        $user = factory(User::class)->create();

        $input = $vote->toArray();
        $input["name"] =  "CREATED_NAME" ;
        $input["code"] =  "CREATED_CODE" ;

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("POST",'/api/votes/',$input)
                         ->assertStatus(201);

        $find = $this->service->getById($response->getOriginalContent()["created_id"]);
        $this->assertEquals($find->code,$input["code"]);

    }

    /**
     *
     * @expectedException Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * */
    public function testDestroy()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $vote = factory(Vote::class)->create(["project" => $project]);

        $user = factory(User::class)->create();

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("DELETE",'/api/votes/' . $vote->id)
                         ->assertStatus(201);

        $this->service->getById($vote->id);

    }

    public function testUpdate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        factory(Vote::class,3)->create(["project" => $project]);

        $votes = $this->service->getPageInProject(0,$project);
        $vote = $votes->first();

        $user = factory(User::class)->create();

        $input = $vote->toArray();
        $input["name"] =  "UPDATED_NAME" ;
        $input["code"] =  "UPDATED_CODE" ;
        $input["project"] =  "UPDATED_PROJECT" ;

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("PATCH",'/api/votes/' . $vote->id,$input)
                         ->assertStatus(201);

        $find = $this->service->getById($vote->id);
        $this->assertEquals($find->code,$vote->code);
        $this->assertNotEquals($find->code,$input["code"]);

    }


}
