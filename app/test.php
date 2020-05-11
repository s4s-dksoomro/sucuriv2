<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    protected $table = 'noncache';

    // protected $guarded = ['id'];
    public $primaryKey = 'id';
    
   


     public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
}
