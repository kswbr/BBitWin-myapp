<?php

use Illuminate\Database\Seeder;

use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Lottery;
use App\Repositories\Eloquent\Models\Campaign;
use App\Repositories\Eloquent\Models\Entry;
use App\Repositories\Eloquent\Models\Vote;
use App\Repositories\Eloquent\Models\Vote\Count;

use Carbon\Carbon;


class FirstSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // \DB::table('lotteries')->truncate();
        // \DB::table('campaigns')->truncate();
        // \DB::table('entries')->truncate();

        $campaigns = Campaign::all();
        foreach($campaigns as $campaign) {
            $campaign->delete();
        }

        $lotteries = Lottery::all();
        foreach($lotteries as $lottery) {
            $lottery->delete();
        }

        $entries = Entry::all();
        foreach($entries as $entry) {
            $entry->delete();
        }

        $campaign = factory(Campaign::class)->create([
            "name" => 'インスタントウィンキャンペーン',
            'code' => uniqid(rand()),
            "project" => env("PROJECT_NAME", config('app.name'))
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品',
            'campaign_code' => $campaign->code,
            'code' => uniqid(rand()),
            'rate' => 50,
            'run_time' => null,
        ]);

    }
}
