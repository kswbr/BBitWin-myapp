<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VoteService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    protected $voteService;
    protected $projectService;

    public function __construct(
        VoteService $voteService,
        ProjectService $projectService
    ) {
        $this->middleware('checkIfVoteBelongsToProject',["except" => ["index","store"]]);
        $this->projectService = $projectService;
        $this->voteService = $voteService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $project = $this->projectService->getCode();
        return response($this->voteService->getPageInProject(config("contents.admin.show_page_count"),$project));
    }

    public function chart(Request $request, $id) {
        $project = $this->projectService->getCode();
        $vote = $this->voteService->getById($id);
        return response($this->voteService->getDataSet($project, $vote));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'code' => 'required|string|unique:votes',
            'choice' => 'required|string|check_vote_choice',
            'start_date' => 'required|string',
            'end_date' => 'required|string'
        ]);

        $project = $this->projectService->getCode();
        $vote = $this->voteService->create(
            $request->input("name"),
            $request->input("code"),
            $request->input("choice"),
            $request->input("active"),
            $request->input("start_date"),
            $request->input("end_date"),
            $project
        );
        return response(['created_id' => $vote->id], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = $this->voteService->getById($id);
        return response($data->toArray(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'choice' => 'required|string|check_vote_choice',
            'start_date' => 'required|string',
            'end_date' => 'required|string'
        ]);

        $this->voteService->update($id,$request->all());
        return response(['update' => true], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->voteService->destroy($id);
        return response(['destroy' => true], 201);
    }
}
