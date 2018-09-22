<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lottery extends Model
{
    protected $fillable = ['name', 'rate', 'total', 'limit', 'start_date', 'end_date', 'active', 'order', 'campaign_code','code', 'daily_increment', 'daily_increment_time'];
    protected $appends = ['remaining', 'remaining_of_completed', 'state', 'entries_count', 'entries_win_completed_count', 'entries_win_count'];

    public function entries() {
        return $this->hasMany(Entry::class,'lottery_code','code');
    }

    public function scopeCode($query,string $lottery_code){
        return $query->where("code",$lottery_code);
    }

    public function scopeOrdered($query){
        return $query->orderBy("order","desc");
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
        $query->where(function($query) {
            $update_check = Carbon::now();
            $query->orWhere("run_time","<",(string)$update_check);
            $query->orWhereNull("run_time");
        });
        return $query;
    }

    public function getResultAttribute()
    {
        $base = 10000;
        $rand = mt_rand(0, 100 * $base);
        return $rand < ($this->attributes['rate'] * $base);
    }

    public function getRemainingAttribute()
    {
        $state = config("contents.entry.state");
        $win_count = $this->entries()->state($state["win"])->count();
        $win_special_count = $this->entries()->state($state["win_special"])->count();
        $win_completed_count = $this->entries()->state($state["win_posting_completed"])->count();
        $ret = $this->attributes['limit'] - ($win_count + $win_special_count + $win_completed_count);
        ($ret < 0) and $ret = 0;
        return $ret;
    }

    public function getRemainingOfCompletedAttribute()
    {
        $state = config("contents.entry.state");
        $win_completed_count = $this->entries()->state($state["win_posting_completed"])->count();
        return $this->attributes['limit'] - $win_completed_count;
    }

    public function getStateAttribute()
    {
        if ($this->attributes['active'] === false){
            return config("contents.lottery.state.inactive");
        }

        if (Carbon::now() < $this->attributes['start_date']){
            return config("contents.lottery.state.stand_by");
        }

        if (Carbon::now() > $this->attributes['end_date']){
            return config("contents.lottery.state.finish");
        }

        if ($this->getRemainingAttribute() <= 0){
            return config("contents.lottery.state.full_entry");
        }

        return config("contents.lottery.state.active");
    }

    public function getEntriesCountAttribute()
    {
        return $this->entries()->count();
    }

    public function getEntriesWinCountAttribute()
    {
        $state = config("contents.entry.state");
        return $this->entries()->state($state["win"])->count() + $this->entries()->state($state["win_special"])->count();
    }


    public function getEntriesWinCompletedCountAttribute()
    {
        $state = config("contents.entry.state");
        return $this->entries()->state($state["win_posting_completed"])->count();
    }



    // public function campaign() {
    //     return $this->belongsTo(Campaign::class,"campaign_code","code");
    // }

}
