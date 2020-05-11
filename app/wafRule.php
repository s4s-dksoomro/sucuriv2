<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wafRule extends Model
{
    //
protected $guarded = ['id'];
    public function wafGroup()
    {
        return $this->belongsTo('App\wafGroup','group_id');
    }

}
