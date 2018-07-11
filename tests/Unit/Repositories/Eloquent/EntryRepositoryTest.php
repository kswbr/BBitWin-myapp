<?php

namespace Tests\Unit\Repositories\Eloquent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\Repositories\Eloquent\EntryRepository;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Entry;
use Carbon\Carbon;

class EntryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Entry::class);
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

    /**
     *
     * @return void
     * @exception Illuminate\Database\Eloquent\ModelNotFoundException
     *
     */
    public function testMockery()
    {
        $mock = \Mockery::mock(EntryRepository::class,[$this->model]);
        $this->app->instance(EntryRepository::class,$mock);

        $mock->shouldReceive('countUser')->andReturn(5);
        $this->assertEquals(5, $mock->countUser());
        $mock->shouldReceive('getById')->passthru();
    }

    /**
     *
     * @return void
     *
     */
    public function testGetCountOfStateInLottery()
    {
        $lottery = factory(Lottery::class)->create();
        factory(Entry::class,10)->create(["state" => config("contents.entry.state.win"),'lottery_code' => $lottery->code  ]);

        $mock = \Mockery::mock(EntryRepository::class,[$this->model]);
        $this->app->instance(EntryRepository::class,$mock);
        $mock->shouldReceive('getCountOfStateInLottery')->passthru();
        $count = $mock->getCountOfStateInLottery($lottery->code,config("contents.entry.state.win"));
        $this->assertEquals($count,10);
    }

    /**
     *
     * @return void
     *
     */
    public function testGetPrevDataOfPlayerInCampaign()
    {
        $player = factory(Player::class)->create(["type" => 1]);
        $campaign = factory(Campaign::class)->create();
        $lottery = factory(Lottery::class)->create(['campaign_code' => $campaign->code]);

        $entry = factory(Entry::class)->create(["state" => config("contents.entry.state.win"),'lottery_code' => $lottery->code, 'player_id' => $player->id, "player_type" => 1]);
        factory(Entry::class,10)->create(["state" => config("contents.entry.state.lose"),'lottery_code' => $lottery->code, 'player_id' => $player->id, "player_type" => 1]);

        $mock = \Mockery::mock(EntryRepository::class,[$this->model]);
        $this->app->instance(EntryRepository::class,$mock);
        $mock->shouldReceive('getPrevDataOfPlayerInCampaign')->passthru();

        $prev_entry = $mock->getPrevDataOfPlayerInCampaign($player->id, $player->type,$campaign->code,$campaign->limited_days);
        $this->assertEquals($entry->id,$prev_entry->id);

        $entry_special = factory(Entry::class)->create(["state" => config("contents.entry.state.win_special"),'lottery_code' => $lottery->code, 'player_id' => $player->id, "player_type" => 1]);
        $entry = factory(Entry::class)->create(["state" => config("contents.entry.state.win"),'lottery_code' => $lottery->code, 'player_id' => $player->id, "player_type" => 1]);

        $prev_entry = $mock->getPrevDataOfPlayerInCampaign($player->id, $player->type,$campaign->code,$campaign->limited_days);

        $this->assertNotEquals($entry->id,$prev_entry->id);
        $this->assertEquals($entry_special->id,$prev_entry->id);
    }

    /**
     *
     * @return void
     *
     */
    public function testUpdateStateWhenLimitedDaysPassed()
    {
        $player = factory(Player::class)->create(["type" => 1]);
        $campaign = factory(Campaign::class)->create();
        $lottery = factory(Lottery::class)->create(['campaign_code' => $campaign->code]);

        factory(Entry::class,10)->create(["state" => config("contents.entry.state.win"),'created_at' => Carbon::yesterday(), "lottery_code" => $lottery->code]);
        factory(Entry::class,20)->create(["state" => config("contents.entry.state.win_posting_expired"),'created_at' => Carbon::tomorrow(), "lottery_code" => $lottery->code]);

        $mock = \Mockery::mock(EntryRepository::class,[$this->model]);
        $this->app->instance(EntryRepository::class,$mock);
        $mock->shouldReceive('updateStateWhenLimitedDaysPassed')->passthru();
        $mock->updateStateWhenLimitedDaysPassed(1,$lottery->code);

        $this->assertEquals(Entry::state(config("contents.entry.state.win"))->count(), 20);
        $this->assertEquals(Entry::state(config("contents.entry.state.win_posting_expired"))->count(), 10);
    }


}
