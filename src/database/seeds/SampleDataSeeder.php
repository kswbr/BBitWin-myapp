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

use App\Services\SerialService;
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

        $players = Player::all();
        foreach($players as $player) {
            $player->delete();
        }

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

        $votes = Vote::all();
        foreach($votes as $vote) {
            $vote->delete();
        }

        $vote_counts = Count::all();
        foreach($vote_counts as $count) {
            $count->delete();
        }

        $serials = Serial::all();
        foreach($serials as $serial) {
            $serial->delete();
        }

        $numbers = Number::all();
        foreach($numbers as $number) {
            $number->delete();
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

        $entry = factory(Entry::class,1000)->create([
            'lottery_code' => $lottery->code,
            "state" => 1,
        ]);

        $entry = factory(Entry::class,20)->create([
            'lottery_code' => $lottery->code,
            "state" => 2,
        ]);

        $campaign = factory(Campaign::class)->create([
            "name" => 'インスタントウィンキャンペーン 複数商品選択',
            'code' => 'samples',
            "project" => env("PROJECT_NAME", config('app.name'))
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品 Aコース',
            'campaign_code' => $campaign->code,
            'code' => 'sample_a',
            'rate' => 99,
            'total' => 10000,
            'limit' => 1000,
            'run_time' => null,
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品 Bコース',
            'campaign_code' => $campaign->code,
            'code' => 'sample_b',
            'rate' => 50,
            'total' => 10000,
            'limit' => 1000,
            'run_time' => null,
        ]);

        $lottery = factory(Lottery::class)->create([
            "name" => 'インスタントウィンサンプル賞品 Cコース',
            'campaign_code' => $campaign->code,
            'code' => 'sample_c',
            'rate' => 10.5,
            'total' => 10000,
            'limit' => 1000,
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

        $vote = factory(Vote::class)->create([
          "code" => "samples",
          "project" => env("PROJECT_NAME", config('app.name')),
          "choice" => "sample_a,Aコース\nsample_b,Bコース\nsample_c,Cコース\n\n",
        ]);
        factory(Count::class,100)->create(["choice" => "sample_a", "vote_code" => $vote->code]);
        factory(Count::class,90)->create(["choice" => "sample_b", "vote_code" => $vote->code]);
        factory(Count::class,80)->create(["choice" => "sample_c", "vote_code" => $vote->code]);

        $serial = factory(Serial::class)->create([
          "code" => "samples",
          "name" => "サンプルシリアルナンバー抽選",
          "project" => env("PROJECT_NAME", config('app.name')),
          "total" => 1000,
          "winner_total" => 500,
        ]);

        $service = \App::make(SerialService::class);
        for($i = 0; $i < $serial->total; $i++) {
            $service->createUniqueNumber($serial);
        }

    }
}
