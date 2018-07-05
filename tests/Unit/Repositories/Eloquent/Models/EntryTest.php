<?php

namespace Tests\Unit\Repositories\Eloquent\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Campaign;

class EntryTest extends TestCase
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
        $entry = factory(Entry::class)->create();
        $this->assertEquals(1,$entry->state);
        $data = Entry::find($entry->id);
        $this->assertEquals($entry->id,$data->id);
    }


}
