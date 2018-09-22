<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CampaignService;
use App\Services\LotteryService;
use App\Services\EntryService;

class UpdateHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '毎時実行処理';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $campaignService = \App::make(CampaignService::class);
        $lotteryService = \App::make(LotteryService::class);
        $entryService = \App::make(EntryService::class);

        foreach($campaignService->getAll() as $campaign) {
            $lotteryService->limitUpDaily($campaign);
            foreach($lotteryService->getInCampaign($campaign) as $lottery) {
                $entryService->updateStateWhenLimitedDaysPassed($campaign, $lottery);
                $this->info("Update Lotteries:{$lottery->code}");
            }
        }

        $this->info("Update Hourly Complete");
    }
}
