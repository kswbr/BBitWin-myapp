<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Vote;
use App\Repositories\Eloquent\Models\Vote\Count;
use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Campaign;

class VoteTest extends TestCase
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
        $vote = factory(Vote::class)->create(["name" => "test"]);
        $this->assertEquals("test",$vote->name);
        $data = Vote::find($vote->id);
        $this->assertEquals($data->name,$vote->name);
    }

    public function testSearch()
    {
        $vote = factory(Vote::class)->create(["name" => "test"]);
        $this->assertEquals("test",$vote->name);

        $vote_in_active = factory(Vote::class)->create(["name" => "test2", "active" => false]);

        $this->assertEquals("test",$vote->name);
        $data = Vote::active()->get();
        $this->assertEquals($data->count(),1);


        $vote_out_session_1 = factory(Vote::class)->create(["name" => "test3", "start_date" => Carbon::tomorrow()]);
        $vote_out_session_2 = factory(Vote::class)->create(["name" => "test3", "end_date" => Carbon::yesterday()]);

        $this->assertEquals("test",$vote->name);
        $data = Vote::inSession()->get();
        $this->assertEquals($data->count(),2);

        $vote = factory(Vote::class)->create(["code" => "test"]);
        $data = Vote::code("test")->first();
        $this->assertEquals($data->id, $vote->id);

        $vote = factory(Vote::class)->create(["project" => "test"]);
        $data = Vote::project("test")->first();
        $this->assertEquals($data->id, $vote->id);
    }

    public function testCountRelation() {
        $vote = factory(Vote::class)->create(["name" => "test"]);
        $count = factory(Count::class)->create(["vote_code" => $vote->code]);

        $this->assertEquals($vote->counts->count(), 1);
    }

}
