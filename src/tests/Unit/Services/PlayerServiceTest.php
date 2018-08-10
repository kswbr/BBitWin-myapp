<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\Models\Player as Model;

use App\Services\PlayerService;
use App\Repositories\Eloquent\PlayerRepository;
use App\User;

class PlayerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->mockRepository = \Mockery::mock(PlayerRepository::class);
        $this->app->instance(PlayerRepository::class,$this->mockRepository);
        \App::bind('App\Repositories\PlayerRepositoryInterface', 'App\Repositories\Eloquent\PlayerRepository');
        $this->service = \App::make(PlayerService::class);
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

    /**
     * save and destroy data in project
     *
     * @return void
     */
    public function testSaveAndDestroy()
    {
        $user = factory(User::class)->create();
        $this->mockRepository->shouldReceive('store')
            ->with([
                "provider_id" => "TESTprovider_id",
                "provider" => "twitter",
                "project" => "testproject",
                "etc_data" => ["test" => 1],
                "user_id" => $user->id,
                "type" => 1
            ])
            ->andReturn(true);
        $data = $this->service->create( "testproject", "twitter", "TESTprovider_id", ["test" => 1], $user );
        $this->assertTrue($data);


        $this->mockRepository->shouldReceive('destroy')
            ->with(999)
            ->andReturn(true);

        $ret = $this->service->destroy(999);
        $this->assertTrue($ret);


    }

    /**
     * roy data in project
     *
     * @return void
     */
    public function testGetPageInProject()
    {
        $this->mockRepository->shouldReceive('getProjectQuery')
            ->with("TESTPROJECT")
            ->andReturn("TESTQUERY");

        $this->mockRepository->shouldReceive('getPaginate')
            ->with(0,"TESTQUERY")
            ->andReturn(true);

        $ret = $this->service->getPageInProject(0, "TESTPROJECT");
        $this->assertTrue($ret);
    }

}
