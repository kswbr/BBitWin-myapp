<?php

namespace App\Repositories\Eloquent\Models\Serial;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Serial;

class Number extends Model
{
    protected $fillable = ['serial_code', 'number','is_winner','player_id','post_complete'];
    protected $table = 'serial_numbers';

    public function player()
    {
       return $this->belongsTo(Player::class);
    }

    public function serial()
    {
       return $this->belongsTo(Serial::class,"serial_code","code");
    }

    public function scopeNumber($query, int $number)
    {
        return $query->where("number",$number);
    }

    public function scopeCode($query, string $code)
    {
        return $query->where("serial_code",$code);
    }


}
