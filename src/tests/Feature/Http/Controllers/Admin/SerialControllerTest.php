<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Campaign\Serial;
use App\Services\SerialService;
use App\User;

class SerialControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        \App::bind('App\Repositories\SerialRepositoryInterface', 'App\Repositories\Eloquent\SerialRepository');
        $this->service = \App::make(SerialService::class);
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
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        factory(Serial::class)->create(["campaign_code" => $campaign->code]);

        $serials = $this->service->getPageInProject(0,$project);

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/api/serials/')
                         ->assertStatus(302);

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/serials')
                         ->assertStatus(200);
    }

   public function testGate()
    {
        $project = env("PROJECT_NAME", config('app.name'));

        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $user = factory(User::class)->create([
          "allow_serial_campaign" => false
        ]);

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/serials')
                         ->assertStatus(403);

        $user = factory(User::class)->create([
          "allow_serial_campaign" => true,
          "role" => 0
        ]);

        // Passport::actingAs( $user, ['check-admin']);
        // $response = $this->actingAs($user,"api")
        //                  ->json("POST",'/api/serials',[])
        //                  ->assertStatus(403);
        //
        // $serial = factory(Serial::class)->create();
        // $response = $this->actingAs($user,"api")
        //                  ->json("DELETE",'/api/serials/' . $serial->id)
        //                  ->assertStatus(403);
    }


    public function testShow()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $serial = factory(Serial::class)->create(["campaign_code" => $campaign->code, "project" => $campaign->project]);

        $user = factory(User::class)->create();
        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->get('/api/serials/' . $serial->id)
                         ->assertStatus(200)
                         ->assertJson($serial->toArray());
    }


    public function testCreate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $serial = factory(Serial::class)->make(["campaign_code" => $campaign->code]);

        $user = factory(User::class)->create();

        $input = $serial->toArray();
        $input["name"] =  "CREATED_NAME" ;
        $input["campaign_code"] =  $campaign->code;
        $input["total"] =  100;

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api");
        $response = $response->json("POST",'/api/serials',$input);
        $response->assertStatus(201);

        $find = $this->service->getById($response->getOriginalContent()["created_id"]);
        $this->assertEquals($find->campaign_code,$input["campaign_code"]);

        //重複チェック
        $serial = factory(Serial::class)->make(["campaign_code" => $campaign->code]);
        $input = $serial->toArray();
        $input["name"] =  "CREATED_NAME" ;
        $input["campaign_code"] =  $campaign->code ;

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api");
        $response = $response->json("POST",'/api/serials',$input);
        $response->assertStatus(422);

    }

    /**
     *
     * @expectedException Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * */
    public function testDestroy()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $serial = factory(Serial::class)->create(["project" => $project,"campaign_code" => $campaign->code]);

        $user = factory(User::class)->create();

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api");
        $response = $response->json("DELETE",'/api/serials/' . $serial->id);
        $response->assertStatus(201);

        $ret = $this->service->getById($serial->id);

    }


    public function testUpdate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $serial = factory(Serial::class)->create(["project" => $project,"campaign_code" => $campaign->code]);

        $user = factory(User::class)->create();

        $input = $serial->toArray();
        $input["name"] =  "UPDATED_NAME" ;
        $input["campaign_code"] =  "UPDATED_CODE" ;

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("PATCH",'/api/serials/' . $serial->id,$input)
                         ->assertStatus(201);

        $find = $this->service->getById($serial->id);
        $this->assertEquals($find->campaign_code,$serial->campaign_code);
        $this->assertNotEquals($find->campaign_code,$input["campaign_code"]);
        $this->assertEquals($find->name,$input["name"]);

    }

    public function testMigrate()
    {
        $project = env("PROJECT_NAME", config('app.name'));
        $campaign = factory(Campaign::class)->create(["project" => $project]);
        $serial = factory(Serial::class)->create(["project" => $project,"campaign_code" => $campaign->code, "total" => 10]);

        $user = factory(User::class)->create();

        Passport::actingAs( $user, ['check-admin']);
        $response = $this->actingAs($user,"api")
                         ->json("POST",'/api/serials/' . $serial->id . '/migrate')
                         ->assertStatus(201);

        $find = $this->service->getById($serial->id);
        $this->assertEquals($find->numbers_count,10);

        $response = $this->actingAs($user,"api")
                         ->json("POST",'/api/serials/' . $serial->id . '/migrate')
                         ->assertStatus(400);


    }


}
