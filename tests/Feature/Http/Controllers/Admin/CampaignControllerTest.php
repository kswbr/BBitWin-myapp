<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

use App\Repositories\Eloquent\Models\Campaign;
use App\Services\CampaignService;
use App\User;

class CampaignControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        \App::bind('App\Repositories\CampaignRepositoryInterface', 'App\Repositories\Eloquent\CampaignRepository');
        $this->service = \App::make(CampaignService::class);
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
        factory(Campaign::class,3)->create(["project" => $project]);

        $campaigns = $this->service->getPageInProject(0,$project);

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/api/campaigns')
                         ->assertStatus(302);

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns')
                         ->assertStatus(200);
    }

    public function testShow()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        factory(Campaign::class,3)->create(["project" => $project]);

        $campaigns = $this->service->getPageInProject(0,$project);
        $campaign = $campaigns->first();

        $user = factory(User::class)->create();
        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $campaign->id)
                         ->assertStatus(200)
                         ->assertJson($campaign->toArray());

    }

    public function testCreate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->make(["project" => $project]);

        $user = factory(User::class)->create();

        $input = $campaign->toArray();
        $input["name"] =  "CREATED_NAME" ;
        $input["code"] =  "CREATED_CODE" ;
        $input["limited_days"] =  2 ;

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("POST",'/api/campaigns/',$input)
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
        $campaign = factory(Campaign::class)->create(["project" => $project]);

        $user = factory(User::class)->create();

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("DELETE",'/api/campaigns/' . $campaign->id)
                         ->assertStatus(201);

        $this->service->getById($campaign->id);

    }

    public function testUpdate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        factory(Campaign::class,3)->create(["project" => $project]);

        $campaigns = $this->service->getPageInProject(0,$project);
        $campaign = $campaigns->first();

        $user = factory(User::class)->create();

        $input = $campaign->toArray();
        $input["name"] =  "UPDATED_NAME" ;
        $input["code"] =  "UPDATED_CODE" ;
        $input["project"] =  "UPDATED_PROJECT" ;
        $input["limited_days"] =  2 ;

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("PATCH",'/api/campaigns/' . $campaign->id,$input)
                         ->assertStatus(201);

        $find = $this->service->getById($campaign->id);
        $this->assertEquals($find->code,$campaign->code);
        $this->assertNotEquals($find->code,$input["code"]);

    }


}
