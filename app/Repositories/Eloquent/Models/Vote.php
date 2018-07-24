<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vote extends Model
{
    protected $fillable = ['name','active','code','choice', 'project', 'start_date', 'end_date'];

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
}
