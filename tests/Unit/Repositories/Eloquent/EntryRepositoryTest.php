<?php

namespace Tests\Unit\Repositories\Eloquent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\Repositories\Eloquent\EntryRepository;
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
    public function testGetPrevDataOfUserInCampaign()
    {
        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create();
        $lottery = factory(Lottery::class)->create(['campaign_code' => $campaign->code]);

        factory(Entry::class,10)->create(["state" => config("contents.entry.state.lose"),'lottery_code' => $lottery->code, 'user_id' => $user->id]);
        $entry = factory(Entry::class)->create(["state" => config("contents.entry.state.win"),'lottery_code' => $lottery->code, 'user_id' => $user->id]);

        $mock = \Mockery::mock(EntryRepository::class,[$this->model]);
        $this->app->instance(EntryRepository::class,$mock);
        $mock->shouldReceive('getPrevDataOfUserInCampaign')->passthru();

        $prev_entry = $mock->getPrevDataOfUserInCampaign($user->id, 1,$campaign->code,$campaign->limited_days); //TODO Userモデルを使わず、Playerモデルを新たに作るべきか検討
        $this->assertEquals($entry->id,$prev_entry->id);
    }


}
