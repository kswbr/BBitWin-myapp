<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\PlayerService;
use App\Services\VoteService;
use App\Services\ProjectService;
use App\User;
use Carbon\Carbon;

class VoteController extends Controller
{
    protected $playerService;
    protected $projectService;
    protected $voteService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        VoteService $voteService,
        PlayerService $playerService,
        ProjectService $projectService
    )
    {
        $this->voteService = $voteService;
        $this->playerService = $playerService;
        $this->projectService = $projectService;
    }

    public function run(Request $request, $vote_code = null)
    {
        $project = $this->projectService->getCode();
        if (!$vote_code) {
            $vote = $this->voteService->getFirstInProject($project);
        } else {
            $vote = $this->voteService->getByProjectAndCode($project,$vote_code);
        }

        $last_vote = \Cookie::get("last_vote",0);
        if ((string)$last_vote >= (string)Carbon::today()) {
            return response(["voted" => false]);
        }

        $validatedData = $request->validate([
            'choice' => 'required',
        ]);

        $this->voteService->choice($project, $vote,$request->input("choice"));
        return response(["voted" => true])->cookie( 'last_vote', Carbon::now(), 24 * 60);
    }

    public function pie(Request $request, $vote_code = null){
        $project = $this->projectService->getCode();
        if (!$vote_code) {
            $vote = $this->voteService->getFirstInProject($project);
        } else {
            $vote = $this->voteService->getByProjectAndCode($project,$vote_code);
        }

        $counts = $this->voteService->getDataSetForPie($project,$vote);

        return response(["counts" => $counts]);

    }

}
