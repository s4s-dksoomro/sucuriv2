<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class panelLog extends Model
{
    //
     protected $guarded = ['id'];

     public function user() {
        return $this->belongsTo('App\User');
    }

     public function zone() {
        return $this->belongsTo('App\Zone');
    }
}
