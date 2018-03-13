<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected $campaignService;
    protected $projectService;

    public function __construct(
        CampaignService $campaignService,
        ProjectService $projectService
    ) {
        $this->projectService = $projectService;
        $this->campaignService = $campaignService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $project = $this->projectService->getCode();

        return response($this->campaignService->getPageInProject(0,$project));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return response([], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project = $this->projectService->getCode();

        $campaign = $this->campaignService->saveInProject(
            $request->input("name"),
            $request->input("code"),
            $request->input("limited_days"),
            $project
        );
        return response(['created_id' => $campaign->id], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = $this->campaignService->getById($id);
        return response($data->toArray(), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->campaignService->update($id,$request->all());
        return response(['update' => "OK"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->campaignService->destroy($id);
        return response(['destroy' => "OK"], 201);
    }
}
