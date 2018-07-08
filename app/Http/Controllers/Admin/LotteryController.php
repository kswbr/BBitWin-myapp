<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use App\Services\LotteryService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class LotteryController extends Controller
{
    protected $campaignService;
    protected $lotteryService;
    protected $projectService;

    public function __construct(
        CampaignService $campaignService,
        LotteryService $lotteryService,
        ProjectService $projectService
    ) {
        $this->middleware('checkIfCampaignBelongsToProject');
        $this->middleware('checkIfLotteryBelongsToCampaign',["except" => ["index","store"]]);
        $this->projectService = $projectService;
        $this->campaignService = $campaignService;
        $this->lotteryService = $lotteryService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaign_id, Request $request)
    {
        $campaign = $this->campaignService->getById($campaign_id);
        return response($this->lotteryService->getPageInCampaign(0,$campaign));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($campaign_id, Request $request)
    {
        $campaign = $this->campaignService->getById($campaign_id);
        $lottery = $this->lotteryService->create(
            $request->input("name"),
            $request->input("rate"),
            $request->input("total"),
            $request->input("limit"),
            $request->input("code"),
            $request->input("start_date"),
            $request->input("end_date"),
            $campaign
        );
        return response(['created_id' => $lottery->id], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lottery  $lottery
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $campaign_id, $id)
    {
        $data = $this->lotteryService->getById($id);
        return response($data->toArray(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lottery  $lottery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $campaign_id, $id)
    {
        $this->lotteryService->update($id,$request->all());
        return response(['update' => true], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lottery  $lottery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $campaign_id, $id)
    {
        $this->lotteryService->destroy($id);
        return response(['destroy' => true], 201);
    }
}
