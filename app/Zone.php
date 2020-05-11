<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'zones';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'zone_id',
        'user_id',
        'cfaccount_id',
        'spaccount_id',
        'name_server1',
        'name_server2',
        'plan',
        'status',
        'paused',
        'type',
        'created_at',
        'Updated_at',
        'deleted_at',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function cfaccount() {
        return $this->belongsTo('App\Cfaccount');
    }
    public function spaccount() {
        return $this->belongsTo('App\Spaccount');
    }

    public function zoneSetting()
    {
        return $this->hasMany('App\ZoneSetting');
    }

    public function customDomain()
    {
        return $this->hasMany('App\customDomain');
    }

    public function dns()
    {
        return $this->hasMany('App\Dns');
    }
    public function analytics()
    {
        return $this->hasMany('App\Analytics');
    }
    public function ElsAnalytics()
    {
        return $this->hasMany('App\ElsAnalytics');
    }
    public function spanalytics()
    {
        return $this->hasMany('App\SpAnalytics');
    }
    public function firewallRule()
    {
        return $this->hasMany('App\FirewallRule');
    }

     public function uaRule()
    {
        return $this->hasMany('App\UaRule');
    }


    public function wafPackage()
    {
        return $this->hasMany('App\wafPackage');
    }
   public function SpRule()
    {
        return $this->hasMany('App\SpRule');
    }

       public function PageRule()
    {
        return $this->hasMany('App\PageRule');
    }


    public function elsLog()
    {
        return $this->hasMany('App\elsLog');
    }

    public function wafEvent()
    {
		ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        return $this->hasMany('App\wafEvent');
    }

    public function CustomCertificate()
    {
        return $this->hasMany('App\CustomCertificate');
    }

    public function panelLog()
    {
        return $this->hasMany('App\panelLog');
    }

}
