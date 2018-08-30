<?php

namespace App\Repositories\Eloquent\Models\Campaign;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Eloquent\Models\Campaign;

class Serial extends Model
{
    protected $table = 'campaign_serials';
    protected $fillable = ['name','project','campaign_code', 'total'];

    public function scopeCampaign($query,string $campaign_code){
        return $query->where("campaign_code",$campaign_code);
    }
    public function scopeProject($query,string $project){
        return $query->where("project",$project);
    }
    public function parentCampaign() {
        return $this->belongsTo(Campaign::class,"campaign_code","code");
    }

    public function numbers(){
        return $this->hasMany(Serial\Number::class);
    }

}
