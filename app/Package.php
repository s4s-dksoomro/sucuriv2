<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;


class Package extends Model
{
	 use HasRolesAndAbilities;
    protected $table = 'packages';
  protected $guarded = ['id'];


  
}
