<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Lottery;

class EntryTest extends TestCase
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
        $entry = factory(Entry::class)->create();
        $this->assertEquals(1,$entry->state);
        $this->assertEquals("lose",$entry->state_code);
        $data = Entry::find($entry->id);
        $this->assertEquals($entry->id,$data->id);
    }

    public function testSearch()
    {

        $lottery = factory(Lottery::class)->create();
        $entry = factory(Entry::class)->create([
           "state"  => 100,
           "lottery_code" => $lottery->code,
           "player_id" => 9999,
           "created_at" => Carbon::tomorrow()
        ]);
        $data = Entry::state(100)->lotteryCode($lottery->code)->playerId(9999)->notPassed(Carbon::now())->first();
        $this->assertEquals($entry->id,$data->id);

        $entry2 = factory(Entry::class)->create([
           "state"  => 100,
           "lottery_code" => $lottery->code,
           "player_id" => 9999,
           "created_at" => Carbon::yesterday()
        ]);

        $data2 = Entry::state(100)->lotteryCode($lottery->code)->playerId(9999)->passed(Carbon::now())->first();
        $this->assertEquals($entry2->id,$data2->id);
    }

    public function testSearchWinner(){
        factory(Entry::class,200)->create([ "state"  => config("contents.entry.state.win") ]);

        factory(Entry::class,100)->create([ "state"  => config("contents.entry.state.win_special") ]);

        $count = Entry::winner(false)->count();
        $this->assertEquals($count,200);

        $count = Entry::winner()->count();
        $this->assertEquals($count,300);
    }

}
