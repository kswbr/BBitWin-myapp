<?php

namespace App\Repositories\Eloquent\Models\Player\Campaign;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Count extends Model
{
    //
    protected $table = 'player_campaign_counts';
    protected $fillable = [ 'campaign_code', 'days_count', 'continuous_days_count', 'check_date'];
    protected $appends = [ 'is_checked_today', 'is_checked_yesterday'];

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

    public function getIsCheckedTodayAttribute() {
        return $this->attributes['check_date'] >= Carbon::today() && $this->attributes['check_date'] < Carbon::tomorrow();
    }

    public function getIsCheckedYesterdayAttribute() {
        return $this->attributes['check_date'] >= Carbon::yesterday() && $this->attributes['check_date'] < Carbon::today();
    }

    public function scopeCampaign($query,string $campaign_code){
        return $query->where("campaign_code",$campaign_code);
    }

    public function scopePlayer($query,int $player_id){
        return $query->where("player_id",$player_id);
    }

}
