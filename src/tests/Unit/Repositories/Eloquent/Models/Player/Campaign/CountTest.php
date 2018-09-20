<?php

namespace Tests\Unit\Repositories\Eloquent\Models\Player\Campaign;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Repositories\Eloquent\Models\Player\Campaign\Count;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Player;

class CountTest extends TestCase
{
    use RefreshDatabase;

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
     * A basic test example.
     *
     * @return void
     */
    public function testSave()
    {
        $count = factory(Count::class)->create();
        $this->assertTrue(true);
        $data = Count::find($count->id);
        $this->assertEquals($count->id,$data->id);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSearch()
    {
        $count = factory(Count::class)->create(["check_date" => Carbon::now()]);
        $data = Count::checkToday()->first();
        $this->assertEquals($count->id,$data->id);

        $count = factory(Count::class)->create(["check_date" => Carbon::yesterday()]);
        $data = Count::checkYesterday()->first();
        $this->assertEquals($count->id,$data->id);

        $player = factory(player::class)->create();
        $count = factory(Count::class)->create(["player_id" => $player->id]);
        $data = Count::player($player->id)->first();
        $this->assertEquals($count->id,$data->id);
    }

}
