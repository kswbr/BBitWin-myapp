<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\PlayerService;
use App\Services\ProjectService;
use App\Services\UserService;
use App\Services\SerialService;
use App\User;

class SerialNumberController extends Controller
{
    protected $playerService;
    protected $projectService;
    protected $serialService;
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        PlayerService $playerService,
        ProjectService $projectService,
        SerialService $serialService,
        UserService $userService
    )
    {
        $this->playerService = $playerService;
        $this->projectService = $projectService;
        $this->userService = $userService;
        $this->serialService = $serialService;
    }

    public function register(Request $request, $serial_code = null)
    {
        $number = $request->input('number');
        $project = $this->projectService->getCode();

        if (!$serial_code) {
            $serial = $this->serialService->getFirstInProject($project);
        } else {
            $serial = $this->serialService->getByProjectAndCode($project,$serial_code);
        }

        // IPアドレスによる重複投稿を抑止する方法を検討
        if (!$number = $this->serialService->getNumber($serial, $number)) {
            abort(412, 'serial number not found');
        }

        $token = null;
        $result = false;

        if ($number->player_id) {
          return response([
              "token" => $token,
              "result" => $result
          ]);
        }

        $user = $this->userService->createPlayer($project, $serial->code . "_" . $number);
        $player = $this->playerService->create($project, "serialnumber", $serial->code . "_" . $number, [], $user);
        $this->serialService->connectNumbersToPlayerByCode($serial, $player, $number);

        if ($number->is_winner) {
            $user->append('serial_number_winner_token');
            $token = $user->serial_number_winner_token;
            $result = true;
        }

        return response([
            "token" => $token,
            "result" => $result
        ]);
    }

}
