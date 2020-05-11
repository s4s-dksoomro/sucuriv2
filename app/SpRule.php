<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\SpCondition;
class SpRule extends Model
{
    //
     protected $guarded = ['id'];

    //

	public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function spcondition()
    {
    	return $this->hasMany('App\SpCondition','sprule_id');
    }

    // this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();

        static::deleting(function($spRule) { // before delete() method call this

            SpCondition::where('sprule_id',$spRule->id)->delete();

        });
    }

}
