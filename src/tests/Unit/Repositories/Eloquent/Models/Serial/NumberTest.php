<?php

namespace Tests\Unit\Repositories\Eloquent\Models\Serial;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Serial\Number;
use App\Repositories\Eloquent\Models\Serial;

class NumberRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Number::class);
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
     *
     */
    public function testSave()
    {
        $number = factory(Number::class)->create();
        $data = Number::find($number->id);
        $this->assertEquals($number->id,$data->id);
    }

    public function testFindPlayer()
    {
        $number = factory(Number::class)->create();
        $player = Player::first();
        $data = Number::find($number->id);
        $this->assertEquals($player->id,$data->player->id);
    }


}
