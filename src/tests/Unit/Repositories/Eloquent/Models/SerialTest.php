<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Serial\Number;
use App\Repositories\Eloquent\Models\Serial;

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
     *
     */
    public function testSave()
    {
        $serial = factory(Serial::class)->create();
        $data = Serial::find($serial->id);
        $this->assertEquals($serial->id,$data->id);
    }

    public function testFindCode()
    {
        $serial = factory(Serial::class)->create();
        $data = Serial::code($serial->code)->first();
        $this->assertEquals($serial->id,$data->id);
    }

    public function testFindProject()
    {
        $serial = factory(Serial::class)->create([
            "project" => "TESTPROJECT",
        ]);
        $data = Serial::project("TESTPROJECT")->first();
        $this->assertEquals($serial->id,$data->id);
    }


    public function testFindNumbers()
    {
        $serial = factory(Serial::class)->create();
        $number = factory(Number::class)->create([
          "serial_code" => $serial->code
        ]);
        $data = Serial::first();
        $number = $data->numbers->first();
        $find_number = Number::first();
        $this->assertEquals($find_number->id,$number->id);
    }


}
