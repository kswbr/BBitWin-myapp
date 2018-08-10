<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Vote;
use App\Repositories\Eloquent\Models\Vote\Count;

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
     * test save and find one.
     *
     * @return void
     */
    public function testSave()
    {
        $vote = factory(Count::class)->create(["choice" => "test"]);
        $this->assertEquals("test",$vote->choice);
        $data = Count::find($vote->id);
        $this->assertEquals($data->choice,$vote->choice);
    }

    public function testSearch()
    {
        $vote = factory(Count::class)->create(["vote_code" => "testtest","choice" => "test"]);
        $data = Count::choice("test")->voteCode("testtest")->first();
        $this->assertEquals($data->id,$vote->id);
    }

}
