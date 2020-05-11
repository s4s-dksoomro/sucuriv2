<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomCertificate extends Model
{
    //
    protected $guarded = ['id'];

     public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function  getExpirationAttribute()
    {
    	return dateFormatting($this->attributes['expires_on'],"Formated");
    }
}
