<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    protected $projectService;
    protected $model;

    public function __construct(
        User $model,
        ProjectService $projectService
    ) {
        $this->projectService = $projectService;
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        $user = \Auth::user();
        return response($user->toArray());
    }

    public function index(Request $request)
    {
        $project = $this->projectService->getCode();
        $users = $this->model->projectMembers($project)->adminMembers()->paginate(config("contents.admin.show_page_count"));
        return response($users->toArray());
    }

    public function store(Request $request)
    {
        $project = $this->projectService->getCode();
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'allow_campaign' => 'boolean',
            'allow_vote' => 'boolean',
            'allow_user' => 'boolean',
            'allow_over_project' => 'boolean',
            'role' => 'required|integer',
        ]);

        $params = array_merge($request->all(),["project" => $project, "password" => bcrypt($request->input("password"))]);
        $user = $this->model->create($params);

        return response(['created_id' => $user->id], 201);
    }
    public function update(Request $request, $id)
    {
        $password = \Auth::user()->password;
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // 'old_password' => "required|old_password:$password",
            // 'password' => 'required|string|min:6|confirmed',
            'allow_campaign' => 'boolean',
            'allow_vote' => 'boolean',
            'allow_user' => 'boolean',
            'allow_over_project' => 'boolean',
            'role' => 'required|integer',
        ]);
        $user = $this->model->find($id);
        $user->update($request->all());
        return response(['update' => true], 201);
    }

    public function destroy(Request $request, $id)
    {
        $user = $this->model->find($id);
        $user->delete();
        return response(['destroy' => true], 201);
    }

}
