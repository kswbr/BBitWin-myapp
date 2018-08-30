<?php

namespace Tests\Unit\Repositories\Eloquent\Campaign\Serial;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Campaign\Serial\Number;
use App\Repositories\Eloquent\Models\Campaign\Serial;

class SerialRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Campaign::class);
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

    public function testFindCampaign()
    {
        $serial = factory(Serial::class)->create();
        $campaign = Campaign::first();
        $data = Serial::campaign($campaign->code)->first();
        $this->assertEquals($serial->id,$data->id);
    }

    public function testFindProject()
    {
        $campaign = factory(Campaign::class)->create();
        $serial = factory(Serial::class)->create([
            "campaign_code" => $campaign->code,
            "project" => $campaign->project,
        ]);
        $data = Serial::project($campaign->project)->first();
        $this->assertEquals($serial->id,$data->id);
    }


    public function testFindNumbers()
    {
        $serial = factory(Serial::class)->create();
        $number = factory(Number::class)->create([
          "serial_id" => $serial->id
        ]);
        $data = Serial::first();
        $number = $data->numbers->first();
        $find_number = Number::first();
        $this->assertEquals($find_number->id,$number->id);
    }


}
