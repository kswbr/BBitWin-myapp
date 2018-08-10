<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\Eloquent\Models\Campaign;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

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
     * test save and find one.
     *
     * @return void
     */
    public function testSave()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test"]);
        $this->assertEquals("test",$campaign->name);
        $data = Campaign::find($campaign->id);
        $this->assertEquals($data->name,$campaign->name);
    }

    /**
     * test find one by project.
     *
     * @return void
     */
    public function testGetByProject()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","project" => "TESTPROJECT"]);
        $data = Campaign::project("TESTPROJECT")->first();
        $this->assertEquals($data->name,$campaign->name);
    }

    /**
     * test find one by code.
     *
     * @return void
     */
    public function testGetByCode()
    {
        $campaign = factory(Campaign::class)->create(["name" => "test","code" => "TESTCODE"]);
        $data = Campaign::code("TESTCODE")->first();
        $this->assertEquals($data->name,$campaign->name);
    }


}
