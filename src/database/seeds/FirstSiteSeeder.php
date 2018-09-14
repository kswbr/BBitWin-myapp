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
            "name" => 'インスタントウィンキャンペーン',
            'code' => 'sample_campaign',
            "project" => env("PROJECT_NAME", config('app.name'))
        ]);

        factory(Lottery::class)->create([
            "name" => '金賞 とても素晴らしい架空の賞品',
            'campaign_code' => $campaign->code,
            'rate' => 5,
            'run_time' => null,
        ]);

        factory(Lottery::class)->create([
            "name" => '銀賞 本当に素敵な架空の賞品',
            'campaign_code' => $campaign->code,
            'rate' => 20,
            'run_time' => null,
        ]);

        factory(Lottery::class)->create([
            "name" => '銅賞 とても役に立つ架空の賞品',
            'campaign_code' => $campaign->code,
            'rate' => 50,
            'run_time' => null,
        ]);

        $vote = factory(Vote::class)->create([
          "code" => "samples",
          "project" => env("PROJECT_NAME", config('app.name')),
          "choice" => "twitter,ツイッター\nline,LINE\n",
        ]);
    }
}
