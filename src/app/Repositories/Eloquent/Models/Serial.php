<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Serial extends Model
{
    protected $table = 'serials';
    protected $fillable = ['code','name','active','project','winner_total','start_date','end_date', 'total'];
    protected $appends = [ 'state'];

    public function scopeProject($query,string $project){
        return $query->where("project",$project);
    }

    public function scopeCode($query,string $code){
        return $query->where("code",$code);
    }

    public function scopeNumbersCount($query){
        return $query->withCount([
            "numbers",
            "numbers as winner_numbers_count" => function ($query) {
                $query->where("is_winner", true);
            }
        ]);
    }

    public function numbers(){
        return $this->hasMany(Serial\Number::class,"serial_code","code");
    }

    public function scopeInSession($query){
        $query->where("start_date","<",Carbon::now());
        $query->where("end_date",">",Carbon::now());
        return $query;
    }

    public function scopeActive($query){
        return $query->where("active",true);
    }

    public function getStateAttribute()
    {
        if ($this->attributes['active'] === false){
            return config("contents.lottery.state.inactive");
        }

        if (Carbon::now() < $this->attributes['start_date']){
            return config("contents.serial.state.stand_by");
        }

        if (Carbon::now() > $this->attributes['end_date']){
            return config("contents.serial.state.finish");
        }

        // if ($this->getRemainingAttribute() <= 0){
        //     return config("contents.lottery.state.full_entry");
        // }
        return config("contents.serial.state.active");
    }


}
