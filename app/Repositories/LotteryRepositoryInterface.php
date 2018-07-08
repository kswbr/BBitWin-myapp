<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface LotteryRepositoryInterface
{
    public function getCampaignQuery($project);

}
