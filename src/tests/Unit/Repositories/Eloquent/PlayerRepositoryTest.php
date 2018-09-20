<?php

namespace Tests\Unit\Repositories\Eloquent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\Repositories\Eloquent\PlayerRepository;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Player\Campaign\Count;
use Carbon\Carbon;

class PlayerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Player::class);
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
        $mock = \Mockery::mock(PlayerRepository::class,[$this->model]);
        $this->app->instance(PlayerRepository::class,$mock);

        $mock->shouldReceive('countUser')->andReturn(5);
        $this->assertEquals(5, $mock->countUser());
        $mock->shouldReceive('getById')->passthru();
    }

    /**
     *
     * @return void
     *
     */
    public function testFindByPlayerInfo()
    {

        $mock = \Mockery::mock(PlayerRepository::class,[$this->model]);
        $this->app->instance(PlayerRepository::class,$mock);

        $player = factory(Player::class)->create([
            "project" => "__TESTPROJECT__",
            "provider" => "facebook",
            "provider_id" => "facebook_id",
            "type" => 2,
        ]);

        $mock->shouldReceive('findByPlayerInfo')->passthru();

        $data = $mock->FindByPlayerInfo("__TESTPROJECT__","facebook","facebook_id");
        $this->assertEquals($data->id,$player->id);

        $data = $mock->FindByPlayerInfo("__TESTPROJECT__","facebook","facebook_id",1);
        $this->assertNull($data);
    }

    public function testCheckInCapmaign()
    {

        $mock = \Mockery::mock(PlayerRepository::class,[$this->model]);
        $this->app->instance(PlayerRepository::class,$mock);

        $player = factory(Player::class)->create([
            "project" => "__TESTPROJECT__",
            "provider" => "facebook",
            "provider_id" => "facebook_id",
            "type" => 1,
        ]);

        $campaign = factory(Campaign::class)->create([
            "project" => "__TESTPROJECT__",
        ]);

        $mock->shouldReceive('checkInCampaignCount')->passthru();
        $ret = $mock->checkInCampaignCount($player->id,$campaign->code, (string)Carbon::now());
        $this->assertEquals($ret->days_count,1);

        $ret = $mock->checkInCampaignCount($player->id,$campaign->code, (string)Carbon::now());
        $this->assertEquals($ret->days_count,1);
    }

    public function testCheckInCapmaignPtn2()
    {

        $mock = \Mockery::mock(PlayerRepository::class,[$this->model]);
        $this->app->instance(PlayerRepository::class,$mock);

        $player = factory(Player::class)->create([
            "project" => "__TESTPROJECT__",
            "provider" => "facebook",
            "provider_id" => "facebook_id",
            "type" => 1,
        ]);

        $campaign = factory(Campaign::class)->create([
            "project" => "__TESTPROJECT__",
        ]);

        $mock->shouldReceive('checkInCampaignCount')->passthru();
        $ret = $mock->checkInCampaignCount($player->id,$campaign->code, (string)Carbon::yesterday());
        $this->assertEquals($ret->days_count,1);

        $ret = $mock->checkInCampaignCount($player->id,$campaign->code, (string)Carbon::now());
        $this->assertEquals($ret->days_count,2);
        $this->assertEquals($ret->continuous_days_count,2);

        $ret = $mock->checkInCampaignCount($player->id,$campaign->code, (string)Carbon::now());
        $this->assertEquals($ret->days_count,2);
        $this->assertEquals($ret->continuous_days_count,2);
    }

    public function testCheckInCapmaignPtn3()
    {

        $mock = \Mockery::mock(PlayerRepository::class,[$this->model]);
        $this->app->instance(PlayerRepository::class,$mock);

        $player = factory(Player::class)->create([
            "project" => "__TESTPROJECT__",
            "provider" => "facebook",
            "provider_id" => "facebook_id",
            "type" => 1,
        ]);

        $campaign = factory(Campaign::class)->create([
            "project" => "__TESTPROJECT__",
        ]);

        $mock->shouldReceive('checkInCampaignCount')->passthru();
        $dt = new Carbon();
        $dt->subDay(2);
        $ret = $mock->checkInCampaignCount($player->id,$campaign->code, (string)$dt);
        $this->assertEquals($ret->days_count,1);

        $ret = $mock->checkInCampaignCount($player->id,$campaign->code, (string)Carbon::now());
        $this->assertEquals($ret->days_count,2);
        $this->assertEquals($ret->continuous_days_count,1);

    }


}
