<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\Models\Entry as Model;

use App\Services\EntryService;
use App\Repositories\Eloquent\EntryRepository;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Entry;
use Carbon\Carbon;

class EntryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Entry::class);
        $this->mockRepository = \Mockery::mock(EntryRepository::class,[$this->model]);
        $this->app->instance(EntryRepository::class,$this->mockRepository);
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

    /**
     * save and destroy data in project
     *
     * @return void
     */
    public function testSaveAndDestroy()
    {
        $player = factory(Player::class)->create();
        $lottery = factory(Lottery::class)->create();

        $this->mockRepository->shouldReceive('store')
            ->with([
                "player_id" => $player->id,
                "player_type" => $player->type,
                "lottery_code" => $lottery->code,
                "state" => config("contents.entry.state.win")
            ])
            ->andReturn(true);

        $data = $this->service->create( $player, $lottery, "win" );
        $this->assertTrue($data);

        $data = $this->service->create( $player, $lottery, "hoge" );
        $this->assertFalse($data);
    }

    /**
     * roy data in project
     *
     * @return void
     */
    public function testGetPageInLottery()
    {
        $lottery = factory(Lottery::class)->create(["code" => "TESTCODE"]);

        $this->mockRepository->shouldReceive('getLotteryQuery')
            ->with($lottery->code)
            ->andReturn("TESTQUERY");

        $this->mockRepository->shouldReceive('getPaginate')
            ->with(0,"TESTQUERY")
            ->andReturn(true);

        $ret = $this->service->getPageInLottery(0, $lottery);
        $this->assertTrue($ret);
    }

    /**
     * roy data in project
     *
     * @return void
     */
    public function testGetStateData()
    {
        $entry = factory(Entry::class)->create(["state" => config("contents.entry.state.win")]);

        $this->mockRepository->shouldReceive('getById')
            ->with($entry->id)
            ->andReturn($entry);

        $ret = $this->service->getStateData($entry->id);
        $this->assertEquals($ret,config("contents.entry.state_data." . config("contents.entry.state.win")));
    }

    /**
     * roy data in project
     *
     * @return void
     */
    public function testUpdateState()
    {
        $entry = factory(Entry::class)->create(["state" => config("contents.entry.state.lose")]);

        $this->mockRepository->shouldReceive('update')
            ->with($entry->id,["state" => config("contents.entry.state.win")]);

        $ret = $this->service->updateState($entry->id,"win");
        $this->assertTrue($ret);
    }

    /**
     * roy data in project
     *
     * @return void
     */
    public function testGetCountOfStateInLottery()
    {
        $lottery = factory(Lottery::class)->create();

        $this->mockRepository->shouldReceive('getCountOfStateInLottery')
            ->with($lottery->code,config("contents.entry.state.win"))
            ->andReturn(true);

        $ret = $this->service->getCountOfStateInLottery($lottery,"win");
        $this->assertTrue($ret);
    }

    public function testUpdateStateWhenLimitedDaysPassed()
    {
        $campaign = factory(Campaign::class)->create();
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);

        $this->mockRepository->shouldReceive('updateStateWhenLimitedDaysPassed')
            ->with($campaign->limited_days,$lottery->code)
            ->andReturn(true);

        $ret = $this->service->updateStateWhenLimitedDaysPassed($campaign,$lottery);
        $this->assertTrue($ret);
    }

    public function testGetPrevDataOfPlayerInCampaign()
    {
        $player = factory(Player::class)->create();
        $campaign = factory(Campaign::class)->create();

        $this->mockRepository->shouldReceive('getPrevDataOfPlayerInCampaign')
            ->with($player->id,$player->type,$campaign->code,$campaign->limited_days)
            ->andReturn(true);

        $ret = $this->service->getPrevDataOfPlayerInCampaign($player,$campaign);
        $this->assertTrue($ret);
    }

    public function testGetDatasetInLottery()
    {
        $campaign = factory(Campaign::class)->create();
        $lottery = factory(Lottery::class)->create(["campaign_code" => $campaign->code]);
        $entry = factory(Entry::class,10)->create(["state" => config("contents.entry.state.lose"),'lottery_code' => $lottery->code, "player_type" => 1]);

        $this->mockRepository->shouldReceive('getDataInLottery')->passthru();

        $ret = $this->service->getDataSetInLottery($lottery);
        $this->assertTrue(is_array($ret));

        $entry = factory(Entry::class,20)->create(["state" => config("contents.entry.state.lose"),'lottery_code' => $lottery->code, "player_type" => 1, "created_at" => Carbon::yesterday()]);

        $ret = $this->service->getDataSetInLottery($lottery);
        $this->assertEquals(count($ret),2);
        $first_date = $ret[Carbon::yesterday()->format("Y-m-d-h")];
        $this->assertEquals(count($first_date),20);

        $first_data = reset($first_date);
        $this->assertEquals(count($first_data),4);

    }


}
