<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(env('OAUTH2_URL',url("")) . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config("passport.client_id",env("PASSPORT_CLIENT_ID")),
                'client_secret' => config("passport.client_secret",env("PASSPORT_CLIENT_SECRET")),
                'username' => $request->input("username"),
                'password' => $request->input("password"),
                'scope' => 'check-admin',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);

    }

}
