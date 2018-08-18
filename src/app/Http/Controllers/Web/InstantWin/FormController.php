<?php

namespace App\Http\Controllers\Web\InstantWin;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use App\Services\LotteryService;
use App\Services\EntryService;
use App\Services\PlayerService;
use App\Services\ProjectService;
use App\User;
use Carbon\Carbon;

class FormController extends Controller
{
    protected $campaignService;
    protected $lotteryService;
    protected $entryService;
    protected $playerService;
    protected $projectService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CampaignService $campaignService,
        LotteryService $lotteryService,
        EntryService $entryService,
        PlayerService $playerService,
        ProjectService $projectService
    )
    {
        $this->campaignService = $campaignService;
        $this->lotteryService = $lotteryService;
        $this->entryService = $entryService;
        $this->playerService = $playerService;
        $this->projectService = $projectService;
    }

    public function init(Request $request )
    {
        $project = $this->projectService->getCode();
        // $user = \Auth::user();
        $entry_id = decrypt(\Cookie::get("entry_code"));
        $entry = $this->entryService->getById($entry_id);
        if ($entry->state_code !== "win" && $entry->state_code !== "win_special") {
            abort(403, 'Unauthorized action.');
        }

        $lottery = $this->lotteryService->getByCodeForWinner($entry->lottery_code);
        if (!$lottery) {
            abort(500, 'Lottery Not Found InSession');
        }
        // $user->append('form_token');
        return response([
          "lottery" => $lottery
        ]);
    }

    public function confirm(Request $request )
    {

        $entry_id = decrypt(\Cookie::get("entry_code"));
        $entry = $this->entryService->getById($entry_id);
        if ($entry->state_code !== "win" && $entry->state_code !== "win_special") {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'name_1' => 'required|max:100',
            'name_2' => 'required|max:100',
            'kana_1' => 'required|katakana|max:100',
            'kana_2' => 'required|katakana|max:100',
            'email' => 'required|email|max:100',
            'zip_1' => 'required|numeric|digits:3',
            'zip_2' => 'required|numeric|digits:4',
            'prefecture' => 'required|prefecture_name',
            'address_1' => 'required|max:100',
            'address_2' => 'required|max:100',
        ]);

        $user = \Auth::user();
        $user->append('postable_token');
        $token = $user->postable_token;

        return response(["token" => $token, "check" => true]);
    }

    public function post(Request $request )
    {

        $entry_id = decrypt(\Cookie::get("entry_code"));
        $entry = $this->entryService->getById($entry_id);
        if ($entry->state_code !== "win" && $entry->state_code !== "win_special") {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'name_1' => 'required|max:100',
            'name_2' => 'required|max:100',
            'kana_1' => 'required|katakana|max:100',
            'kana_2' => 'required|katakana|max:100',
            'email' => 'required|email|max:100',
            'zip_1' => 'required|numeric|digits:3',
            'zip_2' => 'required|numeric|digits:4',
            'prefecture' => 'required|prefecture_name',
            'address_1' => 'required|max:100',
            'address_2' => 'required|max:100',
        ]);

        $user = \Auth::user();
        $user->append('thanks_token');
        $token = $user->thanks_token;
        $thanks_url = "/thanks.html";

        return response(["token" => $token, "thanks_url" => $thanks_url]);
    }

    public function thanks(Request $request )
    {
        $entry_id = decrypt(\Cookie::get("entry_code"));
        $entry = $this->entryService->getById($entry_id);
        if ($entry->state_code !== "win" && $entry->state_code !== "win_special") {
            abort(403, 'Unauthorized action.');
        }

        return response(["entry_id" => $entry_id]);
    }


}
