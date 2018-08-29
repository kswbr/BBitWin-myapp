<?php

namespace App\Repositories\Eloquent\Models\Campaign\Serial;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Eloquent\Models\Player;
use App\Repositories\Eloquent\Models\Campaign\Serial;

class Number extends Model
{
    protected $fillable = ['serial_id', 'number','player_id'];
    protected $table = 'serial_numbers';

    public function player()
    {
       return $this->belongsTo(Player::class);
    }

    public function serial()
    {
       return $this->belongsTo(Serial::class);
    }

    public function scopeNumber($query, int $number)
    {
        return $query->where("number",$number);
    }

}
