<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Campaign;
use App\Services\EntryService;
use App\User;

class EntryControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        \App::bind('App\Repositories\EntryRepositoryInterface', 'App\Repositories\Eloquent\EntryRepository');
        $this->service = \App::make(EntryService::class);
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
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);
        factory(Entry::class,3)->create(["lottery_code" => $lottery->code]);

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id . '/entries')
                         ->assertStatus(302);

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id . '/entries')
                         ->assertStatus(200);
    }

   public function testGate()
    {
        $project = env("PROJECT_NAME", config('app.name'));

        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code]);
        $user = factory(User::class)->create([
          "allow_campaign" => false
        ]);

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id . "/entries")
                         ->assertStatus(403);

        $user = factory(User::class)->create([
          "allow_campaign" => true,
          "role" => 0
        ]);


        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("DELETE",'/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id . "/entries/" . $entry->id)
                         ->assertStatus(201);
    }


    public function testShow()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);
        factory(Entry::class,3)->create(["lottery_code" => $lottery->code]);

        $entries = $this->service->getPageInLottery(0,$lottery);
        $entry = $entries->first();

        $user = factory(User::class)->create();
        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id . '/entries/'. $entry->id)
                         ->assertStatus(200)
                         ->assertJson($entry->toArray());
    }
    public function testChart()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);
        factory(Entry::class,3)->create(["lottery_code" => $lottery->code]);

        $user = factory(User::class)->create();
        $ret = $this->service->getDataSetInLottery($lottery);
        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id . '/entries/chart')
                         ->assertStatus(200)
                         ->assertJson($ret);

    }

    /**
     *
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     *
     * */
    public function testShowWrongentry() {

        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);
        factory(Entry::class,3)->create(["lottery_code" => $lottery->code]);

        $wrong_lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);
        $entries = $this->service->getPageInLottery(0,$lottery);
        $entry = $entries->first();

        $user = factory(User::class)->create();
        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/campaigns/' . $campaign->id . '/lotteries/' . $wrong_lottery->id . '/entries/'. $entry->id)
                         ->assertStatus(403)
                         ->assertJson($entry->toArray());
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
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code]);

        $user = factory(User::class)->create();

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("DELETE",'/api/campaigns/' . $campaign->id . '/lotteries/' . $lottery->id . '/entries/' . $entry->id)
                         ->assertStatus(201);

        $ret = $this->service->getById($entry->id);

    }


    public function testUpdate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code]);
        $user = factory(User::class)->create();

        $input = $entry->toArray();
        $input["state"] =  config("contents.entry.state.win_posting_expired");

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("PATCH",'/api/campaigns/'.$campaign->id.'/lotteries/' . $lottery->id . '/entries/' . $entry->id,$input)
                         ->assertStatus(201);

        $find = $this->service->getById($entry->id);
        $this->assertNotEquals($find->state,$entry->state);
        $this->assertEquals($find->state,$input["state"]);

    }


}
