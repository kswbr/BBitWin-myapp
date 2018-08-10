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
            "name" => 'インスタントウィンキャンペーン 単一商品',
            'code' => uniqid(rand()),
            "project" => env("PROJECT_NAME", config('app.name'))
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品',
            'campaign_code' => $campaign->code,
            'code' => 'campaign_solo',
            'rate' => 50,
            'run_time' => null,
        ]);

        $campaign = factory(Campaign::class)->create([
            "name" => 'インスタントウィンキャンペーン 複数商品選択',
            'code' => 'campaign_select_course',
            "project" => env("PROJECT_NAME", config('app.name'))
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品 Aコース',
            'campaign_code' => $campaign->code,
            'code' => 'lottery_select_a',
            'rate' => 50,
            'run_time' => null,
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品 Bコース',
            'campaign_code' => $campaign->code,
            'code' => 'lottery_select_b',
            'rate' => 50,
            'run_time' => null,
        ]);

        $campaign = factory(Campaign::class)->create([
            "name" => 'インスタントウィンキャンペーン 複数商品同時抽選',
            'code' => 'campaign_multi_course',
            "project" => env("PROJECT_NAME", config('app.name'))
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品 金賞',
            'campaign_code' => $campaign->code,
            'code' => 'lottery_multi_a',
            'rate' => 50,
            'run_time' => null,
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品 銀賞',
            'campaign_code' => $campaign->code,
            'code' => 'lottery_multi_b',
            'rate' => 50,
            'run_time' => null,
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品 銅賞',
            'campaign_code' => $campaign->code,
            'code' => 'lottery_multi_c',
            'rate' => 50,
            'run_time' => null,
        ]);

    }
}
