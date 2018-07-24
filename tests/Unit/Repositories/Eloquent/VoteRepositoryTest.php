<?php

namespace Tests\Unit\Repositories\Eloquent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\VoteRepository;
use App\Repositories\Eloquent\Models\Vote;
use App\Repositories\Eloquent\Models\Vote\Count;
use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Campaign;
use Carbon\Carbon;

class VoteRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Vote::class);
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
        $mock = \Mockery::mock(VoteRepository::class,[$this->model]);
        $this->app->instance(VoteRepository::class,$mock);

        $mock->shouldReceive('countUser')->andReturn(5);
        $this->assertEquals(5, $mock->countUser());
        $mock->shouldReceive('getById')->passthru();
    }

    public function testGetParsedChoiceList() {
        $vote = factory(Vote::class)->create();
        $mock = \Mockery::mock(VoteRepository::class,[$this->model]);
        $this->app->instance(VoteRepository::class,$mock);

        $mock->shouldReceive('getByProjectAndCode')->passthru();
        $mock->shouldReceive('getParsedChoiceList')->passthru();

        $choice_list = $mock->getParsedChoiceList($vote->project, $vote->code);
        $this->assertTrue(is_array($choice_list));
        $this->assertEquals(count($choice_list),3);
    }

    public function testGetChoiceCount() {
        $vote = factory(Vote::class)->create();
        $count_data = factory(Count::class,20)->create( ["choice" => "sample_1","vote_code" => $vote->code]);

        $mock = \Mockery::mock(VoteRepository::class,[$this->model]);
        $mock->shouldReceive('getByProjectAndCode')->passthru();
        $mock->shouldReceive('getChoiceCount')->passthru();
        $count = $mock->getChoiceCount($vote->project, $vote->code,"sample_1");
        $this->assertEquals($count,20);

        $mock->shouldReceive('getCounts')->passthru();
        $count_data = factory(Count::class,30)->create( ["choice" => "sample_2","vote_code" => $vote->code]);

        $counts= $mock->getCounts($vote->project, $vote->code);
        $this->assertEquals($counts->count(),50);
    }

    public function testChoice() {
        $vote = factory(Vote::class)->create();
        $mock = \Mockery::mock(VoteRepository::class,[$this->model]);
        $mock->shouldReceive('getParsedChoiceList')->passthru();
        $mock->shouldReceive('getByProjectAndCode')->passthru();
        $mock->shouldReceive('choice')->passthru();
        $count = $mock->choice($vote->project, $vote->code,"sample_hogehoge");
        $this->assertNull($count);

        $count = $mock->choice($vote->project, $vote->code,"sample_1");
        $this->assertTrue(is_numeric($count->id));

    }


    public function testGetState() {
        $vote = factory(Vote::class)->create();

        $mock = \Mockery::mock(VoteRepository::class,[$this->model]);
        $this->app->instance(VoteRepository::class,$mock);

        $mock->shouldReceive('getByProjectAndCode')->passthru();
        $mock->shouldReceive('getState')->passthru();

        $state = $mock->getState($vote->project, $vote->code);
        $this->assertEquals($state,config("contents.vote.state.active"));

        $vote = factory(Vote::class)->create( ["start_date" => Carbon::tomorrow()]);
        $state = $mock->getState($vote->project, $vote->code);
        $this->assertEquals($state,config("contents.vote.state.stand_by"));

        $vote = factory(Vote::class)->create( ["end_date" => Carbon::yesterday()]);
        $state = $mock->getState($vote->project, $vote->code);
        $this->assertEquals($state,config("contents.vote.state.finish"));

    }

}
