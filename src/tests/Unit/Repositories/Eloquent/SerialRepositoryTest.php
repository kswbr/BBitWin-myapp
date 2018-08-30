<?php

namespace Tests\Unit\Repositories\Eloquent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\SerialRepository;
use App\Repositories\Eloquent\Models\Campaign\Serial;
use App\Repositories\Eloquent\Models\Campaign\Serial\Number;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Campaign;
use Carbon\Carbon;

class SerialRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Serial::class);
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
        $mock = \Mockery::mock(SerialRepository::class,[$this->model]);
        $this->app->instance(SerialRepository::class,$mock);

        $mock->shouldReceive('countUser')->andReturn(5);
        $this->assertEquals(5, $mock->countUser());
        $mock->shouldReceive('getById')->passthru();
    }

    public function testHasNumberInCampaign()
    {
        $campaign = factory(Campaign::class)->create(["code" => "hoge"]);
        $serial = factory(Serial::class)->create([
           'campaign_code' => $campaign->code
        ]);

        $number = factory(Number::class)->create([
           'serial_id' => $serial->id
        ]);

        $repository = new SerialRepository($this->model);
        $data = $repository->hasNumberInCampaign($campaign->code,$number->number);
        $this->assertTrue($data);
    }

    public function testConnectNumbersToPlayerInCampaign()
    {
        $campaign = factory(Campaign::class)->create(["code" => "hoge"]);
        $serial = factory(Serial::class)->create([
           'campaign_code' => $campaign->code
        ]);
        $number = factory(Number::class)->create([
           'serial_id' => $serial->id,
           'number' => 9999,
        ]);

        $player = factory(Player::class)->create();

        $repository = new SerialRepository($this->model);
        $repository->connectNumbersToPlayerInCampaign($campaign->code,$player->id,9999);
        $number = Number::first();
        $this->assertEquals($number->number,9999);

        $count = $repository->getNumbersCountInCampaign($campaign->code);
        $this->assertEquals($count,1);
    }

}

