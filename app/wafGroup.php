<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wafGroup extends Model
{
    //

    protected $guarded = ['id'];

    public function wafPackage()
    {
        return $this->belongsTo('App\wafPackage','package_id');
    }

    public function wafRule()
    {
    	return $this->hasMany('App\wafRule','group_id');
    }
}
