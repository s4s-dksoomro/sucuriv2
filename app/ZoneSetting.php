<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZoneSetting extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'value',
        'modified_on',
        'editable',
        'zone_id',
        'created_at',
        'Updated_at',
    ];


    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
}
