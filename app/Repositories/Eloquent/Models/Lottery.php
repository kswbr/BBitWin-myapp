<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lottery extends Model
{
    protected $fillable = ['name', 'rate', 'total', 'limit', 'start_date', 'end_date', 'active', 'order', 'campaign_code','code'];

    public function scopeCampaign($query,Campaign $campaign){
        return $query->where("campaign_code",$campaign->code);
    }

    public function scopeActive($query){
        return $query->where("active",true);
    }

    public function scopeInSession($query){
        $query->where("start_date","<",Carbon::now());
        $query->where("end_date",">",Carbon::now());
        return $query;
    }

}
