<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\PlayerService;
use App\Services\ProjectService;
use App\User;

class InstantWinController extends Controller
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

    public function run(Request $request)
    {
        return response(["sts" => true]);
    }

}
