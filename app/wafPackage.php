<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wafPackage extends Model
{
    //
	  protected $guarded = ['id'];
    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function wafGroup()
    {
    	return $this->hasMany('App\wafGroup','package_id');
    }
}
