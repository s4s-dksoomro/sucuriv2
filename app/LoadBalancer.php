<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoadBalancer extends Model
{
    protected $guarded = ['id'];

    //

	public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function LoadBalancerPool()
    {
    	return $this->belongsToMany('App\LoadBalancerPool','load_balancer_load_balancer_pool')->withPivot('relType','location');
    }
}
