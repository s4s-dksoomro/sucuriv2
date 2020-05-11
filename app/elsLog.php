<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class elsLog extends Model
{
    //
    protected $guarded = ['id'];

    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function  getCreatedOnAttribute()
    {
    	return dateFormatting($this->attributes['created_at'],"Formated");
    }
}
