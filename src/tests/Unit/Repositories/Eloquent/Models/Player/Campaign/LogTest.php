<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Repositories\Eloquent\Models\Player\Campaign\Log;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Player;

class LogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     * @group tmp
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     * @group tmp
     */
    public function testSave()
    {
        $log = factory(Log::class)->create();
        $this->assertTrue(true);
        $data = Log::find($log->id);
        $this->assertEquals($log->id,$data->id);
    }

    /**
     * A basic test example.
     *
     * @return void
     * @group tmp
     */
    public function testSearch()
    {
        $log = factory(Log::class)->create(["check_date" => Carbon::now()]);
        $data = Log::checkToday()->first();
        $this->assertEquals($log->id,$data->id);

        $log = factory(Log::class)->create(["check_date" => Carbon::yesterday()]);
        $data = Log::checkYesterday()->first();
        $this->assertEquals($log->id,$data->id);

        $player = factory(player::class)->create();
        $log = factory(Log::class)->create(["player_id" => $player->id]);
        $data = Log::player($player->id)->first();
        $this->assertEquals($log->id,$data->id);
    }

}
