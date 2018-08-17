<?php

namespace Tests\Feature\Http\Controllers\Web;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

use App\Services\VoteService;
use App\Services\PlayerService;
use App\Services\ProjectService;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Vote;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;

class VoteControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->voteService = \App::make(VoteService::class);
        $this->playerService = \App::make(PlayerService::class);
        $this->projectService = \App::make(ProjectService::class);

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

    public function testRun()
    {
        $user = factory(User::class)->create();
        $player = factory(Player::class)->create(["user_id" => $user->id]);
        $project = $this->projectService->getCode();
        $vote = factory(Vote::class)->create(["project" => $project]);

        $mock = \Mockery::mock(\App\User::class)->makePartial();
        $mock->player = $player;

        Passport::actingAs( $user, ['vote-event']);
        $response = $this->actingAs($user,"api");

        \Auth::shouldReceive("user")->andReturn($mock);
        \Auth::makePartial();

        $response = $response->json("POST",'/api/vote/run',["choice" => "sample_1"]);
        $response->assertStatus(200);
        $response->assertJson([ "voted" => true ]);

        $vote = $this->voteService->getFirstInProject($project);
        $count = $this->voteService->getChoiceCount($project,$vote,"sample_1");

        $this->assertEquals($count,1);

    }

}
