<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['lottery_code','player_id', 'player_type', 'state'];
    protected $appends = ['state_code'];

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

    public function scopePostingExpiredWinner($query)
    {
        $query->where("state",config("contents.entry.state.win_posting_expired"));
        return $query;
    }

    public function lottery()
    {
       return $this->belongsTo(Lottery::class,"lottery_code","code");
    }

    public function player()
    {
       return $this->belongsTo(Player::class);
    }

    public function scopeLotteryCode($query, $lottery_code)
    {
        return $query->where("lottery_code",$lottery_code);
    }

    public function scopePlayerId($query, $player_id)
    {
        return $query->where("player_id",$player_id);
    }

    public function scopePassed($query, string $limited_time)
    {
        return $query->where("created_at","<",$limited_time);
    }

    public function scopeNotPassed($query, string $limited_time)
    {
        return $query->where("created_at",">=",$limited_time);
    }

    public function getStateCodeAttribute()
    {
        $states = config("contents.entry.state");
        return array_search($this->attributes['state'],$states);
    }


}
