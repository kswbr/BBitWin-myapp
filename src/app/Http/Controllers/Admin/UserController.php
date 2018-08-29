<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    protected $projectService;
    protected $userService;

    public function __construct(
        UserService $userService,
        ProjectService $projectService
    ) {
        $this->projectService = $projectService;
        $this->userService = $userService;
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

    public function role_list(Request $request)
    {
        return response(config("contents.admin.user.role"));
    }


    public function index(Request $request)
    {
        $project = $this->projectService->getCode();
        return response($this->userService->getPageInProject(config("contents.admin.show_page_count"),$project));
    }

    public function store(Request $request)
    {
        $project = $this->projectService->getCode();
        $this->authorize('allow_create_and_delete');
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'allow_campaign' => 'boolean',
            'allow_vote' => 'boolean',
            'allow_user' => 'boolean',
            'allow_over_project' => 'boolean',
            'allow_serial_campaign' => 'boolean',
            'role' => 'required|integer',
        ]);

        $user = $this->userService->createAdmin(
            $request->input("name"),
            $request->input("email"),
            $request->input("password"),
            $project,
            $request->input("role"),
            $request->input("allow_over_project"),
            $request->input("allow_campaign"),
            $request->input("allow_vote"),
            $request->input("allow_user"),
            $request->input("allow_serial_campaign")
        );

        return response(['created_id' => $user->id], 201);
    }

    public function show(Request $request, $id)
    {
        $data = $this->userService->getById($id);
        return response($data->toArray(), 200);
    }

    public function update(Request $request, $id)
    {
        $password = \Auth::user()->password;
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id.',id',
            // 'old_password' => "required|old_password:$password",
            // 'password' => 'required|string|min:6|confirmed',
            'allow_campaign' => 'boolean',
            'allow_vote' => 'boolean',
            'allow_user' => 'boolean',
            'allow_over_project' => 'boolean',
            'allow_serial_campaign' => 'boolean',
            'role' => 'required|integer',
        ]);
        $this->userService->update($id,$request->all(),$request->is('*/users/*'));
        return response(['update' => true], 201);
    }

    public function change_password(Request $request, $id)
    {
        $password = \Auth::user()->password;
        $validatedData = $request->validate([
            'old_password' => "required|old_password:$password",
            'password' => 'required|string|min:6|confirmed',
        ]);
        $this->userService->changePassword($id, $request->input("password"));
        return response(['update' => true], 201);
    }


    public function destroy(Request $request, $id)
    {
        $this->authorize('allow_create_and_delete');
        $this->userService->destroy($id);
        return response(['destroy' => true], 201);
    }

}
