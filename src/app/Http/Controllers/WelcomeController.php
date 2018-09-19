<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampaignService;
use App\Services\ProjectService;

class WelcomeController extends Controller
{

    protected $campaignService;
    protected $projectService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ProjectService $projectService,
        CampaignService $campaignService
    )
    {
        $this->middleware('web');
        $this->projectService = $projectService;
        $this->campaignService = $campaignService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = $this->projectService->getCode();
        $this->campaignService->getFirstInProject($project);
        return view('welcome');
    }

}
