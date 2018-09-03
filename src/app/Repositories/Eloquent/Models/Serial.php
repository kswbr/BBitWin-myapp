<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    protected $table = 'serials';
    protected $fillable = ['code','name','project','winner_total', 'total'];

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

}
