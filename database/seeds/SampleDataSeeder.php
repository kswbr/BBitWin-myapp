<?php

use Illuminate\Database\Seeder;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Entry;

use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaign = factory(Campaign::class)->create(["project" => env("PROJECT_NAME", config('app.name'))]);
        $lottery = factory(Lottery::class)->create(['campaign_code' => $campaign->code]);
        $dt = new Carbon();
        factory(Player::class,10)->create()->each(function($p) use ($lottery,$dt) {
            $dt->addHour(5);
            factory(Entry::class)->create([
                "state" => config("contents.entry.state.win"),
                'lottery_code' => $lottery->code,
                'player_id' => $p->id,
                'created_at' => $dt
            ]);
        });
    }
}
