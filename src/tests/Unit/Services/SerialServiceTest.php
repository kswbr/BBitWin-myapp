<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\SerialService;
use App\Repositories\Eloquent\SerialRepository;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Campaign\Serial;
use App\Repositories\Eloquent\Models\Campaign\Serial\Number;
use Carbon\Carbon;

class SerialServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Serial::class);
        $this->mockRepository = \Mockery::mock(SerialRepository::class,[$this->model]);
        $this->app->instance(SerialRepository::class,$this->mockRepository);
        // \App::bind('App\Repositories\SerialRepositoryInterface', 'App\Repositories\Eloquent\SerialRepository');
        $this->service = \App::make(SerialService::class);
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

    public function testSaveAndDestroy()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);

        $this->mockRepository->shouldReceive('store')
            ->with([
                "name" => "testserial",
                "total" => 1000,
                "campaign_code" => $campaign->code,
                "project" => "TESTPROJECT",
            ])
            ->andReturn(true);

        $data = $this->service->create("testserial", 1000, $campaign, "TESTPROJECT");
        $this->assertTrue($data);

        $this->mockRepository->shouldReceive('destroy')
            ->with(999)
            ->andReturn(true);

        $ret = $this->service->destroy(999);
        $this->assertTrue($ret);

    }

    public function testUpdate()
    {
        $this->mockRepository->shouldReceive('update')
             ->with(999,[ "name" => "TESTNAME", "total" => 1000, ])
             ->andReturn(true);

        $ret = $this->service->update(999, ["name" => "TESTNAME","total" => 1000, "test" => 9999]);
        $this->assertTrue($ret);


    }

    public function testGetByCampaign()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);

        $this->mockRepository->shouldReceive('getByCampaign')
             ->with($campaign->code)
             ->andReturn(true);

        $ret = $this->service->getByCampaign($campaign);
        $this->assertTrue($ret);

    }

    public function testGetNumbersCountInCampaign()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);

        $this->mockRepository->shouldReceive('getNumbersCountInCampaign')
             ->with($campaign->code)
             ->andReturn(true);

        $ret = $this->service->getNumbersCountInCampaign($campaign);
        $this->assertTrue($ret);
    }

    public function testHasNumberInCampaign()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);

        $this->mockRepository->shouldReceive('hasNumberInCampaign')
             ->with($campaign->code, 9999)
             ->andReturn(true);

        $ret = $this->service->hasNumberInCampaign($campaign,9999);
        $this->assertTrue($ret);
    }

    public function testConnectNumbersToPlayerInCampaign()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);
        $player = factory(Player::class)->create();

        $this->mockRepository->shouldReceive('connectNumbersToPlayerInCampaign')
             ->with($campaign->code,$player->id, 9999)
             ->andReturn(true);

        $ret = $this->service->connectNumbersToPlayerInCampaign($campaign,$player,9999);
        $this->assertTrue($ret);
    }

    public function testCreateUniqueNumberInCampaign()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);
        $serial = factory(Serial::class)->create(["campaign_code" => $campaign->code, "project" => $campaign->project]);
        $player = factory(Player::class)->create();

        $this->mockRepository->shouldReceive('hasNumberInCampaign')->once()->andReturn(true);
        $this->mockRepository->shouldReceive('hasNumberInCampaign')->once()->andReturn(false);
        $this->mockRepository->shouldReceive('createNumberInCampaign')->passthru();
        $ret = $this->service->createUniqueNumberInCampaign($campaign);
        $min = config("contents.serial.number.min");
        $max = config("contents.serial.number.max");

        $this->assertTrue($ret >= $min);
        $this->assertTrue($ret <= $max);
        $this->assertTrue(is_numeric($ret));

        $number = Number::first();
        $this->assertEquals($number->number, $ret);
    }



}
