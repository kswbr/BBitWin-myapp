<?php

namespace App\Repositories\Eloquent\Models\Vote;

use Illuminate\Database\Eloquent\Model;

class Count extends Model
{
    protected $fillable = ['choice','vote_code', 'player_id'];
    protected $table = 'vote_counts';

    public function scopeChoice($query,string $choice)
    {
        return $query->where("choice",$choice);
    }

    public function scopeVoteCode($query,string $vote_code)
    {
        return $query->where("vote_code",$vote_code);
    }

}
