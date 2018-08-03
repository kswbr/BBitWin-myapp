<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

use App\Services\CampaignService;
use App\Services\LotteryService;
use App\Services\EntryService;
use App\Services\PlayerService;
use App\Services\ProjectService;

use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Entry;
use Illuminate\Foundation\Testing\WithoutMiddleware;
// use Illuminate\Support\Facades\Auth;
use App\User;

class InstantWinControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        // $this->entryService = \Mockery::mock(EntryService::class);
        // $this->lotteryService = \Mockery::mock(LotteryService::class);
        // $this->campaignService = \Mockery::mock(CampaignService::class);
        // $this->playerService = \Mockery::mock(PlayerService::class);
        // $this->projectService = \Mockery::mock(ProjectService::class);
        // \App::bind("App\Services\CampaignService",function($app){
        //   return $this->campaignService;
        // });

        $this->entryService = \App::make(EntryService::class);
        $this->lotteryService = \App::make(LotteryService::class);
        $this->campaignService = \App::make(CampaignService::class);
        $this->playerService = \App::make(PlayerService::class);
        $this->projectService = \App::make(ProjectService::class);

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

    public function testRunPtn1()
    {

        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);

        $obj = new \stdClass();
        $obj->accessToken = "dummy token !";
        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->shouldReceive("createToken")->andReturn($obj);
        $mock->player = $player;

        // $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("OK");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("OK");
        // $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("OK");

        Passport::actingAs( $user, ['instant-win']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->get('/api/instantwin/run');
        // var_dump($response->content());
        $response->assertStatus(200);

    }

}
