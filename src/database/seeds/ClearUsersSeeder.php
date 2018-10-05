<?php

use Illuminate\Database\Seeder;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Vote;
use App\Repositories\Eloquent\Models\Vote\Count;
use App\Repositories\Eloquent\Models\Serial;
use App\Repositories\Eloquent\Models\Serial\Number;
use App\User;

use App\Services\SerialService;
use Carbon\Carbon;


class ClearUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach($users as $user) {
            $user->delete();
        }
    }
}
