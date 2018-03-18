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
    public function testLoginFlow()
    {


        $faker = \Faker\Factory::create('ja_JP');
        $email = $faker->unique()->safeEmail;
        $user = factory(\App\User::class)->create([
            'email' => $email,
        ]);

        $this->browse(function (Browser $browser) use ($email){
            $browser->visit('/admin')
                    ->pause(2000)
                    ->type('username',$email)
                    ->type('password',"secret")
                    ->press('Login')
                    ->waitUntilMissing('#login',10)
                    ->assertSee('Campaigns')
                    ->pause(2000)
                    ->click('#logoutLink')
                    ->dismissDialog()
                    ->pause(2000)
                    ->assertSee('Campaigns')
                    ->click('#logoutLink')
                    ->acceptDialog()
                    ->waitUntilMissing('#campaign',10)
                    ->assertSee('Login')
                    ->type('password',"sece")
                    ->press('Login')
                    ->waitUntilMissing('.el-loading-parent',10)
                    ->pause(5000)
                    ->assertSee('誤りがあります')
                    ->type('username',$email)
                    ->type('password',"secret")
                    ->press('Login')
                    ->waitUntilMissing('#login',10)
                    ->assertSee('Campaign');
        });
    }
}
