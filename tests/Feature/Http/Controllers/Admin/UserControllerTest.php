<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\User;

class UserControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(User::class);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testGetList()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/api/users')
                         ->assertStatus(302);

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api");
        $response = $response->get('/api/users');
        $response->assertStatus(200);
    }

    /**
     *
     * @expectedException Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * */
    public function testDestroy()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $user = factory(User::class)->create();
        $target = factory(User::class)->create();

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("DELETE",'/api/users/' . $target->id)
                         ->assertStatus(201);
        $this->model->findOrFail($target->id);

    }


    public function testCreate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $user = factory(User::class)->create();

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("POST",'/api/users',[
                            'name' => 'TESTNAME',
                            'email' => 'm.testtest@dummymail.com',
                            'password' => 'secret',
                            'password_confirmation' => 'secret',
                            'allow_campaign' => true,
                            'allow_vote' => true,
                            'allow_user' => true,
                            'allow_over_project' => true,
                            'role' => 2,
                         ]);
        $response->assertStatus(201);
        $this->assertEquals($this->model->where("email","m.testtest@dummymail.com")->count(),1);

    }

    public function testUpdate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $user = factory(User::class)->create();

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("PATCH",'/api/users/' . $user->id,[
                            'name' => 'TESTNAME2',
                            'email' => $user->email,
                            'password' => 'secret2',
                            'allow_campaign' => true,
                            'allow_vote' => true,
                            'allow_user' => true,
                            'allow_over_project' => true,
                            'role' => 2,
                         ]);
        $response->assertStatus(201);
        $data = $this->model->find($user->id);
        $this->assertEquals($data->name,"TESTNAME2");
        $this->assertNotEquals($data->password,"secret2");

    }

}
