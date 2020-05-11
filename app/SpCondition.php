<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpCondition extends Model
{
    //

    protected $guarded = ['id'];

    //

	public function sprule()
    {
        return $this->belongsTo('App\SpRule');
    }
}
