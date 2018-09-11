<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vote extends Model
{
    protected $fillable = ['name','active','code','choice', 'project', 'start_date', 'end_date'];
    protected $appends = ['state'];

    public function scopeCode($query,string $code)
    {
        return $query->where("code",$code);
    }

    public function scopeProject($query,string $project)
    {
        return $query->where("project",$project);
    }

    public function scopeActive($query)
    {
        return $query->where("active",true);
    }

    public function scopeInSession($query){
        $query->where("start_date","<",Carbon::now());
        $query->where("end_date",">",Carbon::now());
        return $query;
    }

    public function counts() {
        return $this->hasMany(Vote\Count::class,'vote_code','code');
    }

    public function scopeCountsByChoice($query, $choice){
        return $query->withCount(["counts" => function($query) use ($choice){
            $query->where("choice",$choice);
        }]);
    }

    public function getStateAttribute()
    {
        if ($this->attributes['active'] === false){
            return config("contents.vote.state.inactive");
        }

        if (Carbon::now() < $this->attributes['start_date']){
            return config("contents.vote.state.stand_by");
        }

        if (Carbon::now() > $this->attributes['end_date']){
            return config("contents.vote.state.finish");
        }

        return config("contents.vote.state.active");
    }



}
