<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class wafEvent extends Model
{
    //
	 protected $guarded = ['id'];
     public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function  getTimestampAttribute()
    {
    	return dateFormatting(Carbon::createFromTimestamp($this->attributes['timestamp'])->format("Y-m-d H:i:s"),"Formated");
    }

    public function  getTsAttribute()
    {
    	return $this->attributes['timestamp'];
    }

}
