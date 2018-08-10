<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LotteryService;
use App\Services\EntryService;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    protected $lotteryService;
    protected $entryService;

    public function __construct(
        LotteryService $lotteryService,
        EntryService $entryService
    ) {
        $this->middleware('checkIfCampaignBelongsToProject');
        $this->middleware('checkIfLotteryBelongsToCampaign');
        $this->middleware('checkIfEntryBelongsToLottery',["except" => ["index","state_list","chart"]]);
        $this->lotteryService = $lotteryService;
        $this->entryService = $entryService;
    }

    public function state_list(Request $request, $campaign_id, $lottery_id)
    {
        return response(config("contents.entry.state_data"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $campaign_id, $lottery_id)
    {
        $lottery = $this->lotteryService->getById($lottery_id);
        return response($this->entryService->getPageInLottery(config("contents.admin.show_page_count"),$lottery));
    }

    public function chart(Request $request, $campaign_id, $lottery_id) {
        $lottery = $this->lotteryService->getById($lottery_id);
        return response($this->entryService->getDataSetInLottery($lottery));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $campaign_id, $lottery_id, $id)
    {
        $data = $this->entryService->getById($id);
        return response($data->toArray(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $campaign_id, $lottery_id, $id)
    {
        $validatedData = $request->validate([
            'state' => 'required|numeric|max:5',
        ]);
        $this->entryService->update($id,$request->all());
        return response(['update' => true], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $campaign_id, $lottery_id, $id)
    {
        $this->entryService->destroy($id);
        return response(['destroy' => true], 201);
    }
}
