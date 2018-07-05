<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Entry extends Model
{
    protected $fillable = ['lottery_code','user_id', 'user_type', 'state'];

    public function scopeState($query,int $state)
    {
        return $query->where("state",$state);
    }

    public function scopeWinner($query, $include_win_special = true)
    {
        $query->where("state",config("contents.entry.state.win"));
        if ($include_win_special) {
           $query->orWhere("state",config("contents.entry.state.win_special"));
        }
        return $query;
    }

    public function lottery()
    {
       return $this->belongsTo(Lottery::class,"lottery_code","code");
    }

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function scopeLotteryCode($query, $lottery_code)
    {
        return $query->where("lottery_code",$lottery_code);
    }

    public function scopeUserId($query, $user_id)
    {
        return $query->where("user_id",$user_id);
    }

    public function scopePassed($query, $limited_time)
    {
        return $query->where("created_at","<",$limited_time);
    }

    public function scopeNotPassed($query, $limited_time)
    {
        return $query->where("created_at",">=",$limited_time);
    }
}
