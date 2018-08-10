<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Lottery;
use App\Services\LotteryService;
use App\User;

class LotteryControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        \App::bind('App\Repositories\LotteryRepositoryInterface', 'App\Repositories\Eloquent\LotteryRepository');
        $this->service = \App::make(LotteryService::class);
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
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        factory(Lottery::class,3)->create(["campaign_code" => $campaign->code]);

        $lotteries = $this->service->getPageInCampaign(0,$campaign);

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries')
                         ->assertStatus(302);

        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries')
                         ->assertStatus(200);
    }

    public function testShow()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        factory(Lottery::class,3)->create(["campaign_code" => $campaign->code, "rate" => 100]);

        $wrong_campaign = factory(Campaign::class)->create(["project" => $project]);
        $lotteries = $this->service->getPageInCampaign(0,$campaign);
        $lottery = $lotteries->first();

        $user = factory(User::class)->create();
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id)
                         ->assertStatus(200)
                         ->assertJson($lottery->toArray());
    }

    /**
     *
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     *
     * */
    public function testShowWrongCampaign() {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        factory(Lottery::class,3)->create(["campaign_code" => $campaign->code]);

        $wrong_campaign = factory(Campaign::class)->create(["project" => $project]);
        $lotteries = $this->service->getPageInCampaign(0,$campaign);
        $lottery = $lotteries->first();

        $user = factory(User::class)->create();
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $wrong_campaign->id .  '/lotteries/' . $lottery->id)
                         ->assertStatus(403)
                         ->assertJson($lottery->toArray());
    }


    public function testCreate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->make(["campaign_code" => $campaign->code]);

        $user = factory(User::class)->create();

        $input = $lottery->toArray();
        $input["name"] =  "CREATED_NAME" ;
        $input["code"] =  "CREATED_CODE" ;

        $response = $this->actingAs($user,"api")
                         ->json("POST",'/api/campaigns/' . $campaign->id . '/lotteries',$input)
                         ->assertStatus(201);

        $find = $this->service->getById($response->getOriginalContent()["created_id"]);
        $this->assertEquals($find->code,$input["code"]);

        //重複チェック
        $lottery = factory(Lottery::class)->make(["campaign_code" => $campaign->code]);
        $input = $lottery->toArray();
        $input["name"] =  "CREATED_NAME" ;
        $input["code"] =  "CREATED_CODE" ;

        $response = $this->actingAs($user,"api")
                         ->json("POST",'/api/campaigns/' . $campaign->id . '/lotteries',$input)
                         ->assertStatus(422);

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
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user,"api")
                         ->json("DELETE",'/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id)
                         ->assertStatus(201);

        $ret = $this->service->getById($lottery->id);

    }


    public function testUpdate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        factory(Lottery::class,3)->create(["campaign_code" => $campaign->code]);
        $lotteries = $this->service->getPageInCampaign(0,$campaign);
        $lottery = $lotteries->first();

        $user = factory(User::class)->create();

        $input = $lottery->toArray();
        $input["name"] =  "UPDATED_NAME" ;
        $input["code"] =  "UPDATED_CODE" ;

        $response = $this->actingAs($user,"api")
                         ->json("PATCH",'/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id,$input)
                         ->assertStatus(201);

        $find = $this->service->getById($lottery->id);
        $this->assertEquals($find->code,$lottery->code);
        $this->assertNotEquals($find->code,$input["code"]);
        $this->assertEquals($find->name,$input["name"]);

    }


}
