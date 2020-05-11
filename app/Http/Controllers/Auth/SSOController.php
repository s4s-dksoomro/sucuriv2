<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Auth;
use App\User;
use App\Zone;
use Illuminate\Http\Request;

class SSOController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function ssologin(Request $request)
    {
            $token=$request->input("token");
            $app=$request->input("app");
            $zone=Zone::where("name",$request->input("zone"))->first();
            $user=\App\User::where('whmcs_token',$token)->first();
            $request->session()->put('zone', $zone->name);
        if(!Auth::check())
        {

            if($zone->user->id==$user->id)
            {
               Auth::login($user,true);
               $user->whmcs_token=str_random(50);
               $user->save();
               // $request->session()->put('zone', $zone->name);
            }
            // 
            // return redirect($this->redirectTo);
        }
        else
        {
            if(Auth::user()->id==$zone->user->id)
            {
               return redirect("admin/".$zone->name."/".$app);
            }
            else
            {
                Auth::login($user,true); 
                $user->whmcs_token=str_random(50);
               $user->save();
               // $request->session()->put('zone', $zone->name);
            }
           
        }
        

        return redirect("admin/".$zone->name."/".$app);
        

    }
}
