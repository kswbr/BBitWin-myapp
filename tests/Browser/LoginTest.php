<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLogin()
    {

        $faker = \Faker\Factory::create('ja_JP');
        $email = $faker->unique()->safeEmail;
        $user = factory(\App\User::class)->create([
            'email' => $email,
        ]);

        $this->browse(function (Browser $browser) use ($email){
            $browser->visit('/admin')
                    ->pause(2000)
                    ->screenshot("access_login")
                    ->type('username',$email)
                    ->type('password',"secret")
                    ->press('Login')
                    ->waitUntilMissing('#login',30)
                    ->assertSee('Campaign');
        });
    }
}
