<?php

namespace App\Repositories\Eloquent\Models\Player\Campaign;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    //
    protected $table = 'player_campaign_logs';

    public function scopeCheckToday($query){
        $query->where("check_date",">=",Carbon::today());
        $query->where("check_date","<",Carbon::tomorrow());
        return $query;
    }

    public function scopeCheckYesterday($query){
        $query->where("check_date",">=",Carbon::yesterday());
        $query->where("check_date","<",Carbon::today());
        return $query;
    }

    public function scopeCampaign($query,string $campaign_code){
        return $query->where("campaign_code",$campaign_code);
    }

    public function scopePlayer($query,int $player_id){
        return $query->where("player_id",$player_id);
    }

}
