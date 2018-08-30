<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SerialService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class SerialController extends Controller
{
    protected $serialService;
    protected $projectService;

    public function __construct(
        SerialService $serialService,
        ProjectService $projectService
    ) {
        $this->middleware('checkIfSerialBelongsToProject',["except" => ["index","store"]]);
        $this->projectService = $projectService;
        $this->serialService = $serialService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Auth::user();
        $project = $this->projectService->getCode();

        return response($this->serialService->getPageInProject(config("contents.admin.show_page_count"),$project));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('allow_create_and_delete');
        $project = $this->projectService->getCode();
        $min = config("contents.serial.total.min");
        $max = config("contents.serial.total.max");

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'campaign_code' => 'required|unique:serials|max:100',
            'total' => 'required|numeric|min:'.$min.'|max:'.$max,
        ]);

        $serial = $this->serialService->create(
            $request->input("name"),
            $request->input("total"),
            $request->input("campaign_code"),
            $project
        );
        return response(['created_id' => $serial->id], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Serial  $serial
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = $this->serialService->getById($id);
        return response($data->toArray(), 200);
    }

    public function migrate(Request $request, $id)
    {
        $serial = $this->serialService->getById($id);

        if (!$serial->parentCampaign) {
            abort(400, 'parent campaign not found');
        }

        if ($serial->numbers_count >= $serial->total) {
            abort(400, 'aleady exists numbers');
        }

        for($i = 0; $i < ($serial->total - $serial->numbers_count); $i++) {
            $this->serialService->createUniqueNumberInCampaign($serial->parentCampaign);
        }
        return response(['migrate' => true], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Serial  $serial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $serial = $this->serialService->getById($id);

        $max = config("contents.serial.total.max");
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'total' => 'required|numeric|min:'.$serial->total.'|max:'.$max,
        ]);

        $this->serialService->update($id,$request->all());
        return response(['update' => true], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Serial  $serial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('allow_create_and_delete');
        $this->serialService->destroy($id);
        return response(['destroy' => true], 201);
    }
}
