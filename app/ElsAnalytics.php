<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElsAnalytics extends Model
{
    //
    	  protected $guarded = ['id'];

    //

	  public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
}
