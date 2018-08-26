<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Repositories\Eloquent\Models\Player;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'project', 'role', 'allow_over_project', 'allow_campaign', 'allow_vote', 'allow_user'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function player()
    {
        return $this->hasOne(Player::class);
    }

    public function scopeProject($query, $project)
    {
        return $query->where("project",$project);
    }

    public function scopeInstantWinPlayers($query)
    {
        return $query->where("email",null)->where("password", null);
    }

    public function scopeAdminMembers($query)
    {
        return $query->whereNotNull("email")->whereNotNull("password")->has("player", "=", 0);
    }

    public function scopeGetById($query,$id)
    {
        return $query->where("id",$id)->first();
    }


    public function scopeProjectMembers($query,$project)
    {
        return $query->where(function($query) use ($project){
            $query->where("project", $project)->orWhere("allow_over_project", true);
        });
    }

    public function getPlayableTokenAttribute()
    {
        return $this->createToken('PlayableToken', ['instant-win','vote-event'])->accessToken;
    }

    public function getRetryTokenAttribute()
    {
        return $this->createToken('RetryToken', ['instant-win','vote-event','retry'])->accessToken;
    }

    public function getPostableTokenAttribute()
    {
        return $this->createToken('PostableToken', ['instant-win','vote-event','form','post'])->accessToken;
    }

    public function getThanksTokenAttribute()
    {
        return $this->createToken('ThanksToken', ['thanks'])->accessToken;
    }


    public function getWinnerTokenAttribute()
    {
        return $this->createToken('WinnerToken', ['instant-win','vote-event','winner'])->accessToken;
    }

    public function getFormTokenAttribute()
    {
        return $this->createToken('FormToken', ['instant-win','vote-event','form'])->accessToken;
    }


}
