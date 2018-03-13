<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\Models\Campaign as Model;

use App\Services\CampaignService;

class CampaignServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
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
        $data = $this->service->saveInProject( "TESTCAMPAIGN", "TESTCODE", 1, "TESTPROJECT" );
        $sample = Model::find($data->id);
        $this->assertEquals($data->id,$sample->id);

        $data = $this->service->saveInProject( "TESTCAMPAIGN_2", "TESTCODE", 1, "TESTPROJECT" );
        $sample = Model::find($data->id);
        $this->assertEquals($data->name,"TESTCAMPAIGN_2");

        $ret = $this->service->destroy($data->id);
        $this->assertNull(Model::find($data->id));


    }

    /**
     * roy data in project
     *
     * @return void
     */
    public function testGetPageInProjectSaveAndDestroy()
    {
        $data = $this->service->saveInProject( "TESTCAMPAIGN", "TESTCODE_1", 1, "TESTPROJECT" );
        $data = $this->service->saveInProject( "TESTCAMPAIGN", "TESTCODE_2", 1, "TESTPROJECT" );
        $data = $this->service->saveInProject( "TESTCAMPAIGN", "TESTCODE_3", 1, "_TESTPROJECT_" );
        $find = $this->service->getPageInProject(0, "TESTPROJECT");
        $this->assertEquals($find->total(), 2);
    }

}
