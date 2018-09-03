<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\SerialService;
use App\Repositories\Eloquent\SerialRepository;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Serial;
use App\Repositories\Eloquent\Models\Serial\Number;
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
        $this->mockRepository->shouldReceive('store')
            ->with([
                "name" => "testserial",
                "total" => 1000,
                "winner_total" => 100,
                "code" => "TESTCODE",
                "project" => "TESTPROJECT",
            ])
            ->andReturn(true);

        $data = $this->service->create("testserial", 1000, 100, "TESTCODE", "TESTPROJECT");
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
             ->with(999,[ "name" => "TESTNAME", "total" => 1000, "winner_total" => 100])
             ->andReturn(true);

        $ret = $this->service->update(999, ["name" => "TESTNAME","total" => 1000,"winner_total" => 100, "test" => 9999]);
        $this->assertTrue($ret);


    }

    public function testGetByCode()
    {
        $this->mockRepository->shouldReceive('getByCode')
             ->with("TESTCODE")
             ->andReturn(true);

        $ret = $this->service->getByCode("TESTCODE");
        $this->assertTrue($ret);

    }

    public function testHasNumber()
    {
        $serial = factory(Serial::class)->create(["code" => "TESTCODE"]);
        $this->mockRepository->shouldReceive('hasNumber')
             ->with("TESTCODE", 9999)
             ->andReturn(true);

        $ret = $this->service->hasNumber($serial,9999);
        $this->assertTrue($ret);
    }

    public function testConnectNumbersToPlayer()
    {
        $player = factory(Player::class)->create();

        $serial = factory(Serial::class)->create(["code" => "TESTCODE"]);
        $this->mockRepository->shouldReceive('connectNumbersToPlayerByCode')
             ->with("TESTCODE",$player->id, 9999)
             ->andReturn(true);

        $ret = $this->service->connectNumbersToPlayer($serial,$player,9999);
        $this->assertTrue($ret);
    }

    public function testCreateUniqueNumber()
    {
        $serial = factory(Serial::class)->create(["project" => "TESTPROJECT"]);
        $player = factory(Player::class)->create();

        $this->mockRepository->shouldReceive('hasNumberByCode')->once()->andReturn(true);
        $this->mockRepository->shouldReceive('hasNumberByCode')->once()->andReturn(false);
        $this->mockRepository->shouldReceive('createNumberByCode')->passthru();
        $ret = $this->service->createUniqueNumber($serial);
        $min = config("contents.serial.number.min");
        $max = config("contents.serial.number.max");

        $this->assertTrue($ret >= $min);
        $this->assertTrue($ret <= $max);
        $this->assertTrue(is_numeric($ret));

        $number = Number::first();
        $this->assertEquals($number->number, $ret);
    }



}
