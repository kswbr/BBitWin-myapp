<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\PlayerService;
use App\Services\ProjectService;
use App\User;

class SnsController extends Controller
{
    protected $playerService;
    protected $projectService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        PlayerService $playerService,
        ProjectService $projectService
    )
    {
        $this->playerService = $playerService;
        $this->projectService = $projectService;
    }

    public function twitter_redirect(Request $request)
    {
        $redirect_url = Socialite::driver('twitter')->redirect()->getTargetUrl();
        return response(["redirect_url" => $redirect_url]);
    }

    public function twitter_register(Request $request)
    {
        $twitter_user = Socialite::driver('twitter')->user();
        $project = $this->projectService->getCode();

        if ($player = $this->playerService->findByPlayerInfo($project, "twitter" ,$twitter_user->getId())) {
            $user = User::find($player->user_id);
        } else {
            $user = User::create([
                'name'         => $twitter_user->getName(),
                'email'        => null,
                'password'     => null,
            ]);
            $player = $this->playerService->create($project, "twitter", $twitter_user->getId(), [], $user);
        }

        return response([
            "token" => $user->createToken('InstantWinToken', ['instant-win'])->accessToken
        ]);
    }


}
