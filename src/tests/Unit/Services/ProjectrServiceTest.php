<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Services\ProjectService;

class ProjectServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->service = \App::make(ProjectService::class);
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
     * get code in project
     *
     * @return void
     */
    public function testGetCode()
    {
        $this->assertEquals($this->service->getCode(),"InstantWin");
    }

}
