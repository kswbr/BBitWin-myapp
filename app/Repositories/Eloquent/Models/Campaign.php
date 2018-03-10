<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends \Illuminate\Database\Eloquent\Model
{
  protected $fillable = ['name', 'limited_days','project','code'];

  public function scopeProject($query, string $project){
      return $query->where("project",$project);
  }

  public function scopeCode($query, string $code){
      return $query->where("code",$code);
  }

}
