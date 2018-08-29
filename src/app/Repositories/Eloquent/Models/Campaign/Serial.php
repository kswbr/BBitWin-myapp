<?php

namespace App\Repositories\Eloquent\Models\Campaign;

use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    protected $table = 'campaign_serials';
    protected $fillable = ['campaign_code', 'total'];

    public function scopeCampaign($query,string $campaign_code){
        return $query->where("campaign_code",$campaign_code);
    }

    public function numbers(){
        return $this->hasMany(Serial\Number::class);
    }

}
