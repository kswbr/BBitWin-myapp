<?php

namespace Tests\Feature\Repositories\Eloquent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\CampaignRepository;
use App\Repositories\Eloquent\Models\Campaign;

class CampaignRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->model = \App::make(Campaign::class);

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
     *
     * @return void
     *
     */
    public function testGetByCodeAndPorject()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE", "project" => "TESTPROJECT"]);

        $this->model->project("TESTPROJECT");
        $repository = new CampaignRepository($this->model);
        $result = $repository->getByCodeAndProject("TESTCODE","TESTPROJECT");
        $this->assertEquals($campaign->id,$result->id);
    }

    /**
     *
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     */
    public function testGetByCodeAndPorjectFail()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "ESTCOD", "project" => "TESTPROJECT"]);

        $repository = new CampaignRepository($this->model);
        $repository->getByCodeAndProject("TESTCODE","TESTPROJECT");
    }

}
