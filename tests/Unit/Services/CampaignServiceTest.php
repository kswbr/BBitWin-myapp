<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\Models\Campaign as Model;

use App\Services\CampaignService;
use App\Repositories\Eloquent\CampaignRepository;

class CampaignServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->mockRepository = \Mockery::mock(CampaignRepository::class);
        $this->app->instance(CampaignRepository::class,$this->mockRepository);
        \App::bind('App\Repositories\CampaignRepositoryInterface', 'App\Repositories\Eloquent\CampaignRepository');
        $this->service = \App::make(CampaignService::class);
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
                "name" => "TESTCAMPAIGN",
                "code" => "TESTCODE",
                "limited_days" => 1,
                "project" => "TESTPROJECT"
            ])
            ->andReturn(true);
        $data = $this->service->create( "TESTCAMPAIGN", "TESTCODE", 1, "TESTPROJECT" );
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
