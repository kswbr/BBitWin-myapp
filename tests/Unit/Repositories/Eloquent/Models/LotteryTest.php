<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Campaign;

class LotteryTest extends TestCase
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
     * test save and find one.
     *
     * @return void
     */
    public function testSave()
    {
        $lottery = factory(Lottery::class)->create(["name" => "test"]);
        $this->assertEquals("test",$lottery->name);
        $data = Lottery::find($lottery->id);
        $this->assertEquals($data->name,$lottery->name);
    }

    public function testSearch()
    {
        $lottery = factory(Lottery::class)->create(["name" => "test"]);
        $lottery_in_active = factory(Lottery::class)->create(["name" => "test2", "active" => false]);

        $this->assertEquals("test",$lottery->name);
        $data = Lottery::active()->get();
        $this->assertEquals($data->count(),1);

        $lottery_out_session_1 = factory(Lottery::class)->create(["name" => "test3", "start_date" => Carbon::tomorrow()]);
        $lottery_out_session_2 = factory(Lottery::class)->create(["name" => "test3", "end_date" => Carbon::yesterday()]);

        $this->assertEquals("test",$lottery->name);
        $data = Lottery::inSession()->get();
        $this->assertEquals($data->count(),2);

        $other_campaign = factory(Campaign::class)->create(["code" => "hogeoghoege"]);

        $lottery_campain_not_match = factory(Lottery::class)->create([
            "name" => "test4",
            'campaign_code' => function () use ($other_campaign){
                return $other_campaign->code;
            }
        ]);
        $data = Lottery::campaign($other_campaign->code)->get();
        $this->assertEquals($data->count(),1);
    }

    public function testSearchEntriesByState() {
        $lottery = factory(Lottery::class)->create(["name" => "test"]);
        $entries_a = factory(Entry::class,10)->create([
            "user_type" => 1,
            "state" => 1,
            'user_id' => function () {
                return factory(\App\User::class)->create()->id;
            },
            'lottery_code' => function () use ($lottery){
                return $lottery->code;
            }
        ]);
        $data = $lottery->entries()->state(1)->get();
        $this->assertEquals($data->count(),10);

        $data = Lottery::entriesCountByState(1)->first();
        $this->assertEquals($data->entries_count,10);
    }

}
