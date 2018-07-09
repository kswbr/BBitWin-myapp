<?php

namespace Tests\Browser\Admin;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CampaignTest extends DuskTestCase
{

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCampaignTopFlow()
    {

        $faker = \Faker\Factory::create('ja_JP');
        $email = $faker->unique()->safeEmail;
        $user = factory(\App\User::class)->create([
            'email' => $email,
        ]);

        $this->browse(function (Browser $browser) use ($email) {
            $browser->visit('/admin')
                    ->type('username',$email)
                    ->type('password',"secret")
                    ->press('Login')
                    ->waitUntilMissing('#login',20)
                    ->assertSee('Campaigns');
        });
    }
}
