<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\Models\Vote as Model;

use App\Services\VoteService;
use App\Repositories\Eloquent\VoteRepository;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Vote;
use Carbon\Carbon;

class VoteServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Vote::class);
        $this->mockRepository = \Mockery::mock(VoteRepository::class,[$this->model]);
        $this->app->instance(VoteRepository::class,$this->mockRepository);
        \App::bind('App\Repositories\VoteRepositoryInterface', 'App\Repositories\Eloquent\VoteRepository');
        $this->service = \App::make(VoteService::class);
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
        $this->mockRepository->shouldReceive('store')
          ->with([
            "name" => "testname",
            "code" => "testcode",
            "choice" => "test,choice",
            "active" => true,
            "start_date" => Carbon::yesterday(),
            "end_date" => Carbon::tomorrow(),
            "project" => "TESTPROJECT"
          ])
            ->andReturn(true);

        $data = $this->service->create( "testname", "testcode", "test,choice" , true, Carbon::yesterday(), Carbon::tomorrow(), "TESTPROJECT");
        $this->assertTrue($data);

    }

    /**
     * roy data in project
     *
     * @return void
     */
    public function testGetPageInProject()
    {
        $this->mockRepository->shouldReceive('getProjectQuery')
            ->with("testproject")
            ->andReturn("TESTQUERY");

        $this->mockRepository->shouldReceive('getPaginate')
            ->with(0,"TESTQUERY")
            ->andReturn(true);

        $ret = $this->service->getPageInProject(0, "testproject");
        $this->assertTrue($ret);
    }


}
