<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cfaccount extends Model
{
    //
    //

    protected $guarded = ['id'];
    
    public function zone()
    {
        return $this->hasMany(Zone::class);
    }

    public function reseller()
    {
        return $this->belongsTo(User::class);
    }
}
