<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Zone;
use Session;


class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        
        // dd($zones);
        // FetchAnalytics::dispatch($zone);
        // FetchFirewallRules::dispatch($zone);
        // FetchDns::dispatch($zone);
        // FetchZoneSetting::dispatch($zone); //
        


        return view('home');
    }

    public function setCurrentTimeZone(Request $request){ //To set the current timezone offset in session
    $input = $request->all();
    if(!empty($input)){
        $current_time_zone = $request->get('curent_zone');
        Session::put('current_time_zone',  $current_time_zone);
    }
}

}
