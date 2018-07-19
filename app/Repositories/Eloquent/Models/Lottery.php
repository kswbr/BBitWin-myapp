<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lottery extends Model
{
    protected $fillable = ['name', 'rate', 'total', 'limit', 'start_date', 'end_date', 'active', 'order', 'campaign_code','code', 'daily_increment', 'daily_increment_time'];

    public function entries() {
        return $this->hasMany(Entry::class,'lottery_code','code');
    }

    public function scopeCode($query,string $lottery_code){
        return $query->where("code",$lottery_code);
    }

    public function scopeCampaign($query,string $campaign_code){
        return $query->where("campaign_code",$campaign_code);
    }

    public function scopeActive($query){
        return $query->where("active",true);
    }

    public function scopeEntriesCountByState($query,int $state){
        return $query->withCount(["entries" => function($query) use ($state){
            $query->where("state",$state);
        }]);
    }

    public function scopeInSession($query){
        $query->where("start_date","<",Carbon::now());
        $query->where("end_date",">",Carbon::now());
        return $query;
    }

    public function scopeCheckIfSetDailyIncrement($query){
        $query->where("daily_increment","!=",0);
        return $query;
    }

    public function scopeCheckIfDailyIncrementHourNow($query){
        $date = Carbon::parse();
        $current_h = $date->hour;
        $query->where("daily_increment_time","=",$current_h);
        return $query;
    }

    public function scopeCheckIfRunTimeOlder($query){
        $update_check = Carbon::now();
        $query->where("run_time","<",$update_check);
        return $query;
    }



    // public function campaign() {
    //     return $this->belongsTo(Campaign::class,"campaign_code","code");
    // }

}
