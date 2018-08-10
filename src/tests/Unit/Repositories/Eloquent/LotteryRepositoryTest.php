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
        $this->model->remaining = 0;
        $mock = \Mockery::mock(LotteryRepository::class,[$this->model]);
        $this->app->instance(LotteryRepository::class,$mock);
        $mock->shouldReceive('performInCampaign')->passthru();

        $campaign = factory(Campaign::class)->create(["code" => "hoge"]);
        $lottery = factory(Lottery::class,10)->create([
            'rate' => 0,
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
    public function testGetFirstInCampaign()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);
        $lottery = factory(Lottery::class)->create([
            'campaign_code' => $campaign->code
        ]);

        $repository = new LotteryRepository($this->model);
        $result = $repository->getFirstInCampaign($campaign->code);
        $this->assertEquals($lottery->id,$result->id);
    }

    /**
     *
     * @return void
     *
     */
    public function testGetByCode()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);
        $lottery = factory(Lottery::class)->create([
            'campaign_code' => $campaign->code
        ]);

        $repository = new LotteryRepository($this->model);
        $result = $repository->getByCode($lottery->code);
        $this->assertEquals($lottery->id,$result->id);
    }

    /**
     *
     * @return void
     *
     */
    public function testPerformInCampaignWin()
    {
        $this->model->remaining = 100;
        $mock = \Mockery::mock(LotteryRepository::class,[$this->model]);
        $this->app->instance(LotteryRepository::class,$mock);
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


}
