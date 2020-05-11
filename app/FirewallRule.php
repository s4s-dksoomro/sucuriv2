<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirewallRule extends Model
{
    //

     protected $guarded = ['id'];

   


     public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

}
