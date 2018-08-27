<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\PlayerService;
use App\Services\ProjectService;
use App\Services\UserService;
use App\User;

class SnsController extends Controller
{
    protected $playerService;
    protected $projectService;
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        PlayerService $playerService,
        ProjectService $projectService,
        UserService $userService
    )
    {
        $this->playerService = $playerService;
        $this->projectService = $projectService;
        $this->userService = $userService;
    }

    public function twitter_redirect(Request $request)
    {
        $redirect_url = Socialite::driver('twitter')->redirect()->getTargetUrl();
        return response(["redirect_url" => $redirect_url]);
    }

    public function line_redirect(Request $request)
    {
        $redirect_url = Socialite::driver('line')->redirect()->getTargetUrl();
        return response(["redirect_url" => $redirect_url]);
    }


    public function twitter_register(Request $request)
    {
        $twitter_user = Socialite::driver('twitter')->user();
        $project = $this->projectService->getCode();

        if ($player = $this->playerService->findByPlayerInfo($project, "twitter" ,$twitter_user->getId())) {
            $user = $this->userService->getById($player->user_id);
        } else {
            $user = $this->userService->createPlayer($project, $twitter_user->getName());
            $player = $this->playerService->create($project, "twitter", $twitter_user->getId(), [], $user);
        }

        $service_url = $request->route("service") . ".html#" . $player->id;

        $user->append('playable_token');

        return response([
            "token" => $user->playable_token,
            "service_url" => $service_url,
        ]);
    }

    public function line_register(Request $request)
    {
        $line_user = Socialite::driver('line')->user();
        $project = $this->projectService->getCode();

        if ($player = $this->playerService->findByPlayerInfo($project, "line" ,$line_user->getId())) {
            $user = $this->userService->getById($player->user_id);
        } else {
            $user = $this->userService->createPlayer($project, $line_user->getName());
            $player = $this->playerService->create($project, "line", $line_user->getId(), [], $user);
        }

        $service_url = $request->route("service") . ".html#" . $player->id;

        $user->append('playable_token');

        return response([
            "token" => $user->playable_token,
            "service_url" => $service_url,
        ]);
    }

}
