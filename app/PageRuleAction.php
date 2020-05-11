<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageRuleAction extends Model
{
    //
    protected $guarded = ['id'];

    //

	public function PageRule()
    {
        return $this->belongsTo('App\PageRule');
    }
}
