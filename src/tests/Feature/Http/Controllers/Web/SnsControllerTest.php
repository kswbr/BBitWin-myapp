<?php

namespace Tests\Feature\Http\Controllers\Web;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Laravel\Socialite\Facades\Socialite;

use App\Services\VoteService;
use App\Services\PlayerService;
use App\Services\ProjectService;
use App\Services\UserService;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Vote;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;

class SnsControllerTest extends TestCase
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

    public function testTwitterRegisterFind()
    {
        $project = $this->projectService->getCode();

        $twitter_user_mock = \Mockery::mock("\stdClass");
        $twitter_user_mock->shouldReceive("getId")->andReturn("TWITTERUSER");
        $twitter_user_service_mock = \Mockery::mock("\stdClass")->shouldReceive('user')->once()->andReturn($twitter_user_mock)->getMock();

        Socialite::shouldReceive("driver")->once("twitter")->andReturn($twitter_user_service_mock);

        $player_mock = \Mockery::mock(Player::class."Mock");
        $player_mock->id = 1;
        $player_mock->user_id = 9999;
        $player_service_mock = \Mockery::mock(PlayerService::class)->shouldAllowMockingProtectedMethods();

        $user_mock = \Mockery::mock(\App\User::class)->makePartial();
        $user_mock->shouldReceive("getPlayableTokenAttribute")->andReturn("PLAYABLETOKEN");

        $user_service_mock = \Mockery::mock(UserService::class)->shouldAllowMockingProtectedMethods();
        $user_service_mock->shouldReceive("getById")->with(9999)->andReturn($user_mock);
        \App::singleton(UserService::class, function () use ($user_service_mock) {
            return $user_service_mock;
        });

        $player_service_mock->shouldReceive("findByPlayerInfo")->with($project, "twitter" ,"TWITTERUSER")->andReturn($player_mock);

        \App::singleton(PlayerService::class, function () use ($player_service_mock) {
            return $player_service_mock;
        });

        $response = $this->call('GET', '/api/oauth/twitter/login/instantwin');
        $response->assertStatus(200);
        $response->assertJson([
          "token" => "PLAYABLETOKEN",
          "service_url" => "instantwin.html#1"
        ]);
    }

    public function testTwitterRegisterCreate()
    {
        $project = $this->projectService->getCode();

        $twitter_user_mock = \Mockery::mock("\stdClass");
        $twitter_user_mock->shouldReceive("getId")->andReturn("TWITTERUSER");
        $twitter_user_mock->shouldReceive("getName")->andReturn("TWITTERNAME");
        $twitter_user_service_mock = \Mockery::mock(\stdClass::Class)->shouldReceive('user')->once()->andReturn($twitter_user_mock)->getMock();

        Socialite::shouldReceive("driver")->once("twitter")->andReturn($twitter_user_service_mock);

        $user_mock = \Mockery::mock(\App\User::class)->makePartial();
        $user_mock->shouldReceive("getPlayableTokenAttribute")->andReturn("PLAYABLETOKEN");

        $user_service_mock = \Mockery::mock(UserService::class)->shouldAllowMockingProtectedMethods();
        $user_service_mock->shouldReceive("createPlayer")->with($project,"TWITTERNAME")->andReturn($user_mock);
        \App::singleton(UserService::class, function () use ($user_service_mock) {
            return $user_service_mock;
        });


        $player_service_mock = \Mockery::mock(PlayerService::class)->shouldAllowMockingProtectedMethods();
        $player_service_mock->shouldReceive("findByPlayerInfo")->with($project, "twitter" ,"TWITTERUSER")->andReturn(false);

        $player_mock = \Mockery::mock(Player::class."Mock");
        $player_mock->id = 1;
        $player_mock->user_id = 9999;
        $player_service_mock->shouldReceive("create")->with($project, "twitter", "TWITTERUSER", [], $user_mock)->andReturn($player_mock);
        \App::singleton(PlayerService::class, function () use ($player_service_mock) {
            return $player_service_mock;
        });

        $response = $this->call('GET', '/api/oauth/twitter/login/instantwin');
        $response->assertStatus(200);
        $response->assertJson([
          "token" => "PLAYABLETOKEN",
          "service_url" => "instantwin.html#1"
        ]);
    }

}
