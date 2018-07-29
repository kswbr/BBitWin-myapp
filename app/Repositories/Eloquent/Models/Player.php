<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['user_id', 'provider_id', 'provider', 'project', 'type'];
    protected $appends = ['etc_data'];

    public function scopeProject($query,string $project_code){
        return $query->where("project",$project_code);
    }

    public function scopeType($query,int $type){
        return $query->where("type",$type);
    }

    public function scopeName($query,string $name){
        return $query->where("name",$name);
    }

    public function scopeProviderId($query,$provider_id){
        return $query->where("provider_id",$provider_id);
    }

    public function scopeProvider($query,$provider){
        return $query->where("provider",$provider);
    }

    public function getEtcDataAttribute()
    {
        return json_decode($this->attributes['info'], true);
    }

    public function setEtcDataAttribute($value)
    {
        $this->attributes['info'] = json_encode($value, JSON_PRETTY_PRINT);
    }


}
