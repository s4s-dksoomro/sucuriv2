<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branding extends Model
{
    //
    protected $guarded = ['id'];

    public function reseller()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
