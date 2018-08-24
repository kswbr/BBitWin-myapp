<?php

namespace Tests\Feature\Http\Controllers\Admin\InstantWin;

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

class FormControllerTest extends TestCase
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

    public function testInit()
    {
        $project = $this->projectService->getCode();

        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 2]);

        \Crypt::shouldReceive("decrypt")->once()->andReturn($entry->id);

        Passport::actingAs( $user, ['instant-win','form']);
        $response = $this->actingAs($user,"api");
        $response = $response->get('/api/instantwin/form/init');
        $response->assertStatus(200);
    }

    public function testConfirm()
    {
        $project = $this->projectService->getCode();

        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 2]);

        \Crypt::shouldReceive("decrypt")->once()->andReturn($entry->id);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;
        $mock->shouldReceive("getPostableTokenAttribute")->andReturn("OK");

        Passport::actingAs( $user, ['instant-win','form']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->post('/api/instantwin/form/confirm',[
            'name_1' => 'testname',
            'name_2' => 'testname2',
            'kana_1' => 'テストネーム',
            'kana_2' => 'テストネームツー',
            'email' => 'dummymail@dummy.example.com',
            'zip_1' => '123',
            'zip_2' => '4567',
            'prefecture' => '東京都',
            'address_1' => 'テスト住所',
            'address_2' => 'テスト住所2',
        ]);
        $response->assertStatus(200);

    }

    public function testPost()
    {
        $project = $this->projectService->getCode();

        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 2]);

        \Crypt::shouldReceive("decrypt")->once()->andReturn($entry->id);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;
        $mock->shouldReceive("getThanksTokenAttribute")->andReturn("OK");

        Passport::actingAs( $user, ['instant-win','post']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->post('/api/instantwin/form/post',[
            'name_1' => 'testname',
            'name_2' => 'testname2',
            'kana_1' => 'テストネーム',
            'kana_2' => 'テストネームツー',
            'email' => 'dummymail@dummy.example.com',
            'zip_1' => '123',
            'zip_2' => '4567',
            'prefecture' => '東京都',
            'address_1' => 'テスト住所',
            'address_2' => 'テスト住所2',
        ]);
        $response->assertStatus(200);

    }

    public function testThanks()
    {
        $project = $this->projectService->getCode();

        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code, "rate" => 100]);
        $entry = factory(Entry::class)->create(["lottery_code" => $lottery->code, "player_id" => $player->id, "player_type" => $player->type, "state" => 3]);

        \Crypt::shouldReceive("decrypt")->once()->andReturn($entry->id);

        Passport::actingAs( $user, ['instant-win','thanks']);
        $response = $this->actingAs($user,"api");

        $response = $response->get('/api/instantwin/form/thanks');
        $response->assertStatus(200);
        $response->assertJson([
          "entry_id" => $entry->id
        ]);

    }

}
