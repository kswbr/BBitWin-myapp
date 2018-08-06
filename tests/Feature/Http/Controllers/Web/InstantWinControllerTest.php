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

    // 初回で当選した場合
    public function testRunPtnWinner()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);

        $obj = new \stdClass();
        $obj->accessToken = "dummy token !";
        $mock = \Mockery::mock(\App\User::class)->makePartial();
        // $mock->shouldReceive("createToken")->andReturn($obj);
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("OK");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("OK");
        $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("WINNER");

        Passport::actingAs( $user, ['instant-win']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        \Crypt::shouldReceive("encrypt")->andReturn("WINNERCODE");
        $response = $response->get('/api/instantwin/run');
        $response->assertStatus(200);
        $entry = Entry::first() ;
        $response->assertJson([
          "result" => true,
          "finish" => true,
          "token" => "WINNER",
          "winning_entry_code" => "WINNERCODE"
        ]);
    }

    // 初回で落選してリトライの権利を得た場合
    public function testRunPtnGetRetryChance()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 0]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("OK");
        $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        // $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("OK");

        Passport::actingAs( $user, ['instant-win']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->get('/api/instantwin/run');
        $response->assertStatus(200);
        $entry = Entry::first() ;
        $response->assertJson([
          "result" => false,
          "finish" => false,
          "token" => "RETRY",
          "winning_entry_code" => null
        ]);
    }

    // 前回当選して未応募の場合必ず当選
    public function testRunPtnPrevWinner()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 0]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 2]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("OK");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("WINNER");

        Passport::actingAs( $user, ['instant-win']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        \Crypt::shouldReceive("encrypt")->andReturn("WINNERCODE");
        $response = $response->get('/api/instantwin/run');
        $response->assertStatus(200);
        $response->assertJson([
          "result" => true,
          "finish" => true,
          "token" => "WINNER",
          "winning_entry_code" => "WINNERCODE"
        ]);
    }

    // 前回当選(管理画面にて特別当選扱い)して未応募の場合必ず当選
    public function testRunPtnPrevWinnerPtn2()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 0]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 5]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("OK");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("WINNER");

        Passport::actingAs( $user, ['instant-win']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        \Crypt::shouldReceive("encrypt")->andReturn("WINNERCODE");
        $response = $response->get('/api/instantwin/run');
        $response->assertStatus(200);
        $response->assertJson([
          "result" => true,
          "finish" => true,
          "token" => "WINNER",
          "winning_entry_code" => "WINNERCODE"
        ]);
    }

    // 前回当選してリトライ後当選
    public function testRunPtnRetryAndWin()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 1]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("LOSE");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("WINNER");

        Passport::actingAs( $user, ['instant-win','retry']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        \Crypt::shouldReceive("encrypt")->andReturn("WINNERCODE");
        $response = $response->get('/api/instantwin/run/retry');
        $response->assertStatus(200);
        $entry = Entry::orderBy("id","desc")->first() ;
        $response->assertJson([
          "result" => true,
          "finish" => true,
          "token" => "WINNER",
          "winning_entry_code" => "WINNERCODE"
        ]);
    }

    // 前回当選してリトライ後落選
    public function testRunPtnRetryAndLose()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 0]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 4]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("LOSE");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        // $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("WINNER");

        Passport::actingAs( $user, ['instant-win','retry']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->get('/api/instantwin/run/retry');
        $response->assertStatus(200);
        $entry = Entry::orderBy("id","desc")->first() ;
        $response->assertJson([
          "result" => false,
          "finish" => true,
          "token" => "LOSE",
          "winning_entry_code" => null
        ]);
    }

    // 前回当選してリトライしていない場合落選
    public function testRunPtnLoseTwice()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 0]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 4]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("LOSE");
        $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        // $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("WINNER");

        Passport::actingAs( $user, ['instant-win','retry']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->get('/api/instantwin/run');
        $response->assertStatus(200);
        $response->assertJson([
          "result" => false,
          "finish" => false,
          "token" => "RETRY",
          "winning_entry_code" => null
        ]);
    }


    // 前回当選して応募完了した人は必ず落選
    public function testRunPtnWinPostingCompleted()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 3]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("LOSE");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        // $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("WINNER");

        Passport::actingAs( $user, ['instant-win']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->get('/api/instantwin/run');
        $response->assertStatus(200);
        $entry = Entry::orderBy("id","desc")->first() ;
        $response->assertJson([
          "result" => false,
          "finish" => false,
          "token" => "LOSE",
          "winning_entry_code" => null
        ]);
    }

    // 前回当選して応募完了した人は必ず落選(リトライ)
    public function testRunPtnWinPostingCompletedWhenRetry()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 3]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("LOSE");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        // $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("WINNER");

        Passport::actingAs( $user, ['instant-win','retry']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->get('/api/instantwin/run/retry');
        $response->assertStatus(200);
        $entry = Entry::orderBy("id","desc")->first() ;
        $response->assertJson([
          "result" => false,
          "finish" => true,
          "token" => "LOSE",
          "winning_entry_code" => null
        ]);
    }

    // 商品残数0の場合必ず落選
    public function testRunPtnZeroRemaining()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $player_b = factory(Player::class)->create();
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100, "limit" => 1]);

        //他のプレイヤーが当選した
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player_b->id, "player_type" => $player_b->type, "state" => 2]);
        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("LOSE");
        $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        // $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("OK");

        Passport::actingAs( $user, ['instant-win']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->get('/api/instantwin/run');
        $response->assertStatus(200);
        $entry = Entry::orderBy("id","desc")->first() ;
        $response->assertJson([
          "result" => false,
          "finish" => false,
          "token" => "RETRY",
          "winning_entry_code" => null
        ]);
    }

    // 商品残数0の場合必ず落選 (リトライ)
    public function testRunPtnZeroRemainingRetry()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $player_b = factory(Player::class)->create();
        $project = $this->projectService->getCode();
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100, "limit" => 1]);

        //他のプレイヤーが当選した
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player_b->id, "player_type" => $player_b->type, "state" => 2]);
        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        $mock->shouldReceive("getInstantWinTokenAttribute")->andReturn("LOSE");
        // $mock->shouldReceive("getRetryTokenAttribute")->andReturn("RETRY");
        // $mock->shouldReceive("getWinnerTokenAttribute")->andReturn("OK");

        Passport::actingAs( $user, ['instant-win','retry']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->get('/api/instantwin/run/retry');
        $response->assertStatus(200);
        $entry = Entry::orderBy("id","desc")->first() ;
        $response->assertJson([
          "result" => false,
          "finish" => true,
          "token" => "LOSE",
          "winning_entry_code" => null
        ]);
    }

}
