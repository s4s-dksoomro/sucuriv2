<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoadBalancerPool extends Model
{
    protected $guarded = ['id'];

    //

	public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function LoadBalancer()
    {
    	return $this->belongsToMany('App\LoadBalancer','load_balancer_load_balancer_pool')->withPivot('relType','location');
    }
}
