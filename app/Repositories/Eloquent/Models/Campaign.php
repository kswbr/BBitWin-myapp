<?php

namespace App\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
  protected $fillable = ['name', 'limited_days','project'];

  public function scopeProject($query, string $project){
      return $query->where("project",$project);
  }

  public function scopeCode($query, string $code){
      return $query->where("code",$code);
  }

}
