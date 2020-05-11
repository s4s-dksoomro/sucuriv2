<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageRule extends Model
{
    //
     protected $guarded = ['id'];

    //

	public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function PageRuleAction()
    {
    	return $this->hasMany('App\PageRuleAction','pagerule_id');
    }
}
