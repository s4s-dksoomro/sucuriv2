<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Hash;
use App\Http\Controllers\Traits\AccessToken;
/**
 * Class User
 
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasRolesAndAbilities;
  use AccessToken;
    protected $fillable = ['name', 'email', 'password', 'remember_token'];
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
    
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function zone()
    {
        return $this->hasMany(Zone::class);
    }

    public function cfaccount()
    {
        return $this->hasMany(Cfaccount::class,'reseller_id');
    }
    
     public function spaccount()
    {
        return $this->hasMany(Spaccount::class,'reseller_id');
    }
    public function branding()
    {
        return $this->hasOne(Branding::class);
    }


    public function subUsers()
    {
        return $this->hasMany(User::class,'owner');
    }

    public function panelLog()
    {
        return $this->hasMany('App\panelLog');
    }

    public function  getCfZoneCountAttribute()
    {



    return $this->subUsers->reduce(function ($total, $subUser) {
      return $total + $subUser->zone->where('cfaccount_id','!=','0')->count();
    },0);
        //return $this->zone->count();
    }

    public function  getSpZoneCountAttribute()
    {



    return $this->subUsers->reduce(function ($total, $subUser) {
      return $total + $subUser->zone->where('cfaccount_id','0')->count();
    },0);
        //return $this->zone->count();
    }
    
}
