<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\Models\Lottery as Model;

use App\Services\LotteryService;
use App\Repositories\Eloquent\LotteryRepository;
use App\Repositories\Eloquent\Models\Campaign;

class LotteryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->mockRepository = \Mockery::mock(LotteryRepository::class);
        $this->app->instance(LotteryRepository::class,$this->mockRepository);
        \App::bind('App\Repositories\LotteryRepositoryInterface', 'App\Repositories\Eloquent\LotteryRepository');
        $this->service = \App::make(LotteryService::class);
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
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);

        $this->mockRepository->shouldReceive('store')
            ->with([
                "name" => "TestName",
                "rate" => 99.0,
                "total" => 100,
                "limit" => 20,
                "code" => "TestCode",
                "start_date" => "TestStartDate",
                "end_date" => "TestEndDate",
                "campaign_code" => $campaign->code,
                "active" => true,
                "order" => 0
            ])
            ->andReturn(true);

        $data = $this->service->create(
            "TestName",
            99.0,
            100,
            20,
            "TestCode",
            "TestStartDate",
            "TestEndDate",
            $campaign
        );
        $this->assertTrue($data);


        $this->mockRepository->shouldReceive('destroy')
            ->with(999)
            ->andReturn(true);

        $ret = $this->service->destroy(999);
        $this->assertTrue($ret);

    }

    /**
     * roy data in project
     *
     * @return void
     */
    public function testGetPageInProject()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);

        $this->mockRepository->shouldReceive('getCampaignQuery')
            ->with($campaign->code)
            ->andReturn("TESTQUERY");

        $this->mockRepository->shouldReceive('getPaginate')
            ->with(0,"TESTQUERY")
            ->andReturn(true);

        $ret = $this->service->getPageInCampaign(0, $campaign);
        $this->assertTrue($ret);
    }

}
