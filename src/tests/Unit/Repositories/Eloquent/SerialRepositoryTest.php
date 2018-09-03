<?php

namespace Tests\Unit\Repositories\Eloquent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\SerialRepository;
use App\Repositories\Eloquent\Models\Serial;
use App\Repositories\Eloquent\Models\Serial\Number;
use App\Repositories\Eloquent\Models\Player;
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

    public function testHasNumberByCode()
    {
        $serial = factory(Serial::class)->create([
        ]);
        $number = factory(Number::class)->create([
           'serial_code' => $serial->code
        ]);

        $repository = new SerialRepository($this->model);
        $data = $repository->hasNumberByCode($serial->code,$number->number);
        $this->assertTrue($data);
    }

    public function testConnectNumbersToPlayerByCOde()
    {
        $serial = factory(Serial::class)->create();

        $player = factory(Player::class)->create();

        $repository = new SerialRepository($this->model);
        $repository->createNumberByCode($serial->code,9999);
        $repository->connectNumbersToPlayerByCode($serial->code,$player->id,9999);
        $number = Number::first();
        $this->assertEquals($number->number,9999);

        $serial = $repository->getById($serial->id);
        $this->assertEquals($serial->numbers_count,1);
    }

    public function testUpdateRandomWinnerNumbersByCode()
    {
        $serial = factory(Serial::class)->create();
        $player = factory(Player::class)->create();

        $repository = new SerialRepository($this->model);
        $repository->createNumberByCode($serial->code,9999);
        $repository->updateRandomWinnerNumbersByCode($serial->code,1);
        $number = Number::first();
        $this->assertEquals($number->number,9999);
        $this->assertEquals($number->is_winner,true);

        $serial = $repository->getById($serial->id);
        $this->assertEquals($serial->winner_numbers_count,1);
    }

}

