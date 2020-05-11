<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dns extends Model
{
    protected $table = 'url_paths';

    // protected $guarded = ['id'];
    public $primaryKey = 'id';
    
   


     public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

}
