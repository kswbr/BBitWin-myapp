<?php

namespace Tests\Unit\Repositories\Eloquent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\LotteryRepository;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Campaign;
use Carbon\Carbon;

class LotteryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Lottery::class);
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
        $mock = \Mockery::mock(LotteryRepository::class,[$this->model]);
        $this->app->instance(LotteryRepository::class,$mock);

        $mock->shouldReceive('countUser')->andReturn(5);
        $this->assertEquals(5, $mock->countUser());
        $mock->shouldReceive('getById')->passthru();
    }

    /**
     *
     * @return void
     *
     */
    public function testPerformInCampaignLose()
    {
        $mock = \Mockery::mock(LotteryRepository::class,[$this->model]);
        $this->app->instance(LotteryRepository::class,$mock);
        $mock->shouldReceive('getRemaining')->andReturn(0);
        $mock->shouldReceive('performInCampaign')->passthru();

        $campaign = factory(Campaign::class)->create(["code" => "hoge"]);
        $lottery = factory(Lottery::class,10)->create([
            'campaign_code' => function () use ($campaign){
                return $campaign->code;
            }
        ]);

        $result = $mock->performInCampaign("hoge");
        $this->assertEquals(10, count($result["losed_lotteries"]));
    }

    /**
     *
     * @return void
     *
     */
    public function testPerformInCampaignWin()
    {
        $mock = \Mockery::mock(LotteryRepository::class,[$this->model]);
        $this->app->instance(LotteryRepository::class,$mock);
        $mock->shouldReceive('getRemaining')->andReturn(100);
        $mock->shouldReceive('performInCampaign')->passthru();

        $campaign = factory(Campaign::class)->create(["code" => "hoge"]);

        factory(Lottery::class,10)->create([
            'rate' => 0,
            'campaign_code' => function () use ($campaign){
                return $campaign->code;
            }
        ]);

        factory(Lottery::class)->create([
            'rate' => 100,
            'campaign_code' => function () use ($campaign){
                return $campaign->code;
            }
        ]);

        $result = $mock->performInCampaign("hoge");
        $this->assertEquals(10, count($result["losed_lotteries"]));
        $this->assertTrue($result["is_winner"]);
    }

    public function testGetRemaining() {
        $lottery = factory(Lottery::class)->create(["limit" => 100]);

        factory(Entry::class,10)->create(["state" => config("contents.entry.state.win"),'lottery_code' => $lottery->code  ]);
        factory(Entry::class,20)->create(["state" => config("contents.entry.state.win_special"), 'lottery_code' => $lottery->code ]);
        factory(Entry::class,5)->create(["state" => config("contents.entry.state.win_posting_completed"), 'lottery_code' => $lottery->code ]);

        $mock = \Mockery::mock(LotteryRepository::class,[$this->model]);
        $this->app->instance(LotteryRepository::class,$mock);
        $mock->shouldReceive('getRemaining')->passthru();
        $count = $mock->getRemaining($lottery->code);

        $this->assertEquals($count,65);

        $mock->shouldReceive('getRemainingOfCompleted')->passthru();
        $count = $mock->getRemainingOfCompleted($lottery->code);
        $this->assertEquals($count,95);
    }

    public function testGetState() {
        $lottery = factory(Lottery::class)->create();

        $mock = \Mockery::mock(LotteryRepository::class,[$this->model]);
        $this->app->instance(LotteryRepository::class,$mock);

        $mock->shouldReceive('getRemaining')->once()->andReturn(100);
        $mock->shouldReceive('getState')->passthru();

        $state = $mock->getState($lottery->code);
        $this->assertEquals($state,config("contents.lottery.state.active"));

        $lottery = factory(Lottery::class)->create( ["start_date" => Carbon::tomorrow()]);
        $state = $mock->getState($lottery->code);
        $this->assertEquals($state,config("contents.lottery.state.stand_by"));

        $lottery = factory(Lottery::class)->create( ["end_date" => Carbon::yesterday()]);
        $state = $mock->getState($lottery->code);
        $this->assertEquals($state,config("contents.lottery.state.finish"));

        $mock->shouldReceive('getRemaining')->once()->andReturn(0);

        $lottery = factory(Lottery::class)->create();
        $state = $mock->getState($lottery->code);
        $this->assertEquals($state,config("contents.lottery.state.full_entry"));
    }

}
