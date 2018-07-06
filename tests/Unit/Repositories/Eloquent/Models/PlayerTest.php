<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Campaign;

class PlayerTest extends TestCase
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
        $player = factory(Player::class)->create();
        $this->assertEquals("twitter",$player->provider);
        $data = Player::find($player->id);
        $this->assertEquals($player->id,$data->id);
    }

    /**
     * test search.
     *
     * @return void
     */
    public function testSearch()
    {
        $player = factory(Player::class)->create([
            "name" => "test",
            "project" => "__TESTPROJECT__",
            "provider" => "facebook",
            "provider_id" => "facebook_id",
            "type" => 2,
        ]);

        $data = Player::project("__TESTPROJECT__")
            ->type(2)
            ->provider("facebook")
            ->providerId("facebook_id")
            ->name("test")
            ->first();

        $this->assertEquals($data->name,$player->name);
    }


}
