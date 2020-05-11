<?php

namespace App\Http\Controllers\API;

use App\Dns;
// Jobs
// use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\PageRule;
use App\PageRuleAction;
use App\User;
use App\Zone;
use App\ZoneSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use App\panelLog;
use App\Package;
use App\Cfaccount;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Http\Request;
use App\Libraries\Cfhost;
// use App\Http\Controllers\Controller;


use App\Jobs\FetchZoneSetting;
use App\Jobs\FetchDns;
use App\Jobs\FetchWAFPackages;
use App\Jobs\FetchAnalytics;
use App\Jobs\FetchFirewallRules;

class ZoneController extends Controller
{
    protected $request;
    protected $zons;
    private $user;
    public function __construct(Request $request, Zone $zons)
    {

        $this->request = $request;
        $this->Zone    = $zons;
    }

    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function auth()
    {

        if (Auth::attempt(['email' => $this->request->header('email'), 'password' => $this->request->header('password')])) {
            $this->user = Auth::user();

        } else {
            echo json_encode(['error' => 'Unauthorised']);
            die();
        }
    }

    public function index()
    {

        $this->auth();
        // $zons = $this->Zone->all();

        $zons = $this->user->zone->all('name');
        // dd($zons);
        return response()->json(['data' => $zons,
            'status'                        => 200]);
    }



    public function suspendZone(Request $request)
    {
        //

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        // $zone = Zone::findOrFail($id);
        $zone = Zone::where('id', $request->input('zoneId'))->withTrashed()->first();
        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        // if (!(auth()->user()->id == $zone->user->owner or auth()->user()->id == 1)) {
        //     return abort(401);
        // }

        // if (str_contains(URL::previous(), "trashed")) {

        //     if (!(auth()->user()->id == 1)) // ONly super admin can Delete Permanently.
        //     {
        //         return abort(401);

        //     } else {

        //         $zone->forceDelete(); // Force deleted if it is in Trash
        //         return redirect()->route('admin.zones.trash');
        //     }
        // } else {




            if ($zoneType == "cfaccount") {

                \App\Jobs\PauseZone::dispatch($zone, true);

            }


            if($request->input('terminate'))
            {
                $zone->forceDelete();
                $msg="zone terminated";
            }
            else
            {
                $zone->delete();
                $msg='zone suspended';
            }



            // return redirect()->route('admin.zones.index');

        // return response()->json(['status'=>200, 'data' =>"Zone Suspended"]);
           return response()->json(['status'=>201, 'data' =>[
                    'success' => true,
                    'msg' => $msg
                ]]);
    }

 public function unsuspendZone(Request $request)
    {
        //

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        // $zone = Zone::findOrFail($id);
        $zone = Zone::where('id', $request->input('zoneId'))->withTrashed()->first();
        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        // if (!(auth()->user()->id == $zone->user->owner or auth()->user()->id == 1)) {
        //     return abort(401);
        // }

        // if (str_contains(URL::previous(), "trashed")) {

        //     if (!(auth()->user()->id == 1)) // ONly super admin can Delete Permanently.
        //     {
        //         return abort(401);

        //     } else {

        //         $zone->forceDelete(); // Force deleted if it is in Trash
        //         return redirect()->route('admin.zones.trash');
        //     }
        // } else {
            $zone->restore();

            if ($zoneType == "cfaccount") {
                \App\Jobs\PauseZone::dispatch($zone, false);
            }

            // return redirect()->route('admin.zones.index');

        // return response()->json(['status'=>200, 'data' =>"Zone Suspended"]);
           return response()->json(['status'=>201, 'data' =>[
                    'success' => true,
                    'msg' => "Zone Unsuspended"


                ]]);
    }

    public function crypto($zone)
    {

        $this->auth();

        $zone = $this->user->zone->where('name', $zone)->first();

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        $zoneSetting = $zone->zoneSetting;

        // if(!(auth()->user()->id == $zone->user->id OR auth()->user()->owner == $zone->user->id  OR auth()->user()->id == $zone->{$zoneType}->reseller->id OR auth()->user()->id == 1))
        //    {
        //            return abort(401);
        //    }

        $customCertificates = $zone->customCertificate;
        //SSL
        $ssl = $zoneSetting->where('name', 'ssl')->first()->value;
        // always_use_https
        $always_use_https = $zoneSetting->where('name', 'always_use_https')->first()->value;
        //security_header_strict_transport_security_enabled
        $security_header_strict_transport_security_enabled = $zoneSetting->where('name', 'security_header_strict_transport_security_enabled')->first()->value;
        //tls_client_auth
        $tls_client_auth = $zoneSetting->where('name', 'tls_client_auth')->first()->value;
        // Require Modern TLS
        $tls_1_2_only = $zoneSetting->where('name', 'tls_1_2_only')->first()->value;
        //Automatic HTTPS Rewrites
        $automatic_https_rewrites = $zoneSetting->where('name', 'automatic_https_rewrites')->first()->value;
        // Legacy Browser Support
        $sha1_support = $zoneSetting->where('name', 'sha1_support')->first()->value;
        // Opportunistic Encryption
        $opportunistic_encryption = $zoneSetting->where('name', 'opportunistic_encryption')->first()->value;

        return response()->json(['ssl' => $ssl, 'always_use_https'                  => $always_use_https, 'hsts' => $security_header_strict_transport_security_enabled, 'tls_client_auth' => $tls_client_auth, 'tls_1_2_only' => $tls_1_2_only, 'automatic_https_rewrites' => $automatic_https_rewrites,
            'sha1_support'                 => $sha1_support, 'opportunistic_encryption' => $opportunistic_encryption,
            'status'                       => 200]);

    }

   public function createZone(Request $request)
    {


            //   panelLog::create([
            //     'user_id' => 1,
            //     'zone_id' => 1,
            //     'name' => "tes",
            //     'parameters' => json_encode($request->all()),
            //     'type' => 1,
            //     'uri'   => json_encode($request->all()),
            //     'payload' => ''
            // ]);
// dd(auth()->user()->id);
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

       // return response()->json(['status'=>201, 'data' =>"in"]);



        $package=Package::findOrFail($request->input("package"));
        // Log::debug($request->input("package"));
        // die();
        // dd($package->abilities);
        $exists=User::where("email",$request->input("email"))->where("owner",auth()->user()->id)->count();

        if($exists!=0)
        {
            $mainUser=User::where("email",$request->input("email"))->where("owner",auth()->user()->id)->first();



        }
        else
        {


            $mainUser = User::createOrFail([
                "name" => $request->input("fullname"),
                "email" => $request->input("email"),
                 "password" => str_random(20),
                 "owner"    => auth()->user()->id

            ]);



             $mainUser->assign('organization');
        }

        $owner=$mainUser->id;
        // dd($owner);
        $email=$request->input("username")."__".$request->input("email");




        $exists=User::where("email",$email)->where("owner",$owner)->count();

        // dd($exists);
        if($exists!=0)
        {
            $domUser=User::where("email",$email)->where("owner",$owner)->first();



        }
        else
        {

            // dd([
            //     "name" => $request->input("username"),
            //     "email" => $email,
            //      "password" => $request->input("password"),
            //      "owner"    => $owner

            // ]);

         $domUser = User::create([
                "name" => $request->input("username"),
                "email" => $email,
                 "password" => $request->input("password"),
                 "owner"    => $owner

            ]);
         $domUser->owner=$owner;
         // dd();
          $domUser->assign('subUser');

// $domUser->save();

                foreach ($package->abilities as $ability) {
                    # code...
                     // dd($ability->name);
                     $domUser->allow($ability->name);
                }
$domUser->save();

        }
// dd($domUser->abilities);
// sdf

           // return response()->json(["status" => 201,"data"=>["success"=>true,"msg"=>"Please update the DNS at domain registrar for funfog.com to <b>clark.ns.cloudflare.com<\/b> & <b>liv.ns.cloudflare.com<\/b>","zoneid"=>12,"status"=>"pending"]]);


       $zoneExists=Zone::where("name",$request->name)->count();

       if($zoneExists!=0)
       {




        $zone=Zone::where("name",$request->name)->first();
              return response()->json(['status'=>201, 'data' =>[
                    'success' => false,
                    'msg' => "Zone Already exists in Panel",
                    'zoneid' => $zone->id,
                    'status' => $zone->status,


                ]]);

       }


  // dd($package->base);

        if($package->base!="sp")
        {




        if (auth()->user()->id == 1) // Super admin is allowed to select cfaccount from dropdown.
        {
            $cf = Cfaccount::findOrfail(1);
            // $cf = Cfaccount::where('id', $request->cfaccount)->first();
        } else // resellers are only allowed the specific cfaccount
        {

            $user = User::find(auth()->user()->id);
            $cf   = Cfaccount::find($user->getAbilities()->where('entity_type', 'App\Cfaccount')->first()->entity_id);


            //Check if User has not reached the zones limit
            if (!($user->branding and $user->cfZoneCount < $user->branding->cf)) {
                return abort(401);
            }
        }

        $key     = new \Cloudflare\API\Auth\APIKey($cf->email, $cf->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
       $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);

        $type=$request->type;
        $created=true;
        if($type!=null)
        {

            if($cf->user_key=="")
            {


                $res=Cfhost::request("USER_LOOKUP","cloudflare_email",$cf->email);
                $user_key=$res->response->user_key;
                if($user_key!=null)
                {
                    $cf->user_key=$user_key;
                    $cf->save();
                }
            }
            else
            {
                $user_key=$cf->user_key;
            }

            // dd($user_key);

            if($user_key==null)
            {
                $created=false;
                $msg="This User is not created using the HostKey... and cannot retreive the user_key which is required for cname based zone creation";
            }
            else
            {


            // dd();
            // $resolveTo=$request->resolveTo;
                $resolveTo="blockdos-protection";
            // $params["user_key"]             = $arg2;
            // $params["zone_name"]            = $arg3;
            // $params["resolve_to"]           = $arg4;
            // $params["subdomains"]           = $arg5;
            $res=Cfhost::request("zone_set",$user_key,$request->name,$resolveTo.".".$request->name,"www");

             if(isset($res->err_code))
             {
                if($res->err_code==208)
                {
                    $created=false;
                    $msg="Zone is already configured using different partner. Please remove the zone from that account and then try adding it again";
                }
            }
            // var_dump($res->response->forward_tos->{"www.".$res->response->zone_name});
            // die();
            $msg= "Zone ".$res->response->zone_name." Added and is set to resolve to ".$res->response->resolving_to." Please add Cname for "."<b>www.".$res->response->zone_name."</b> and point it to <b>".$res->response->forward_tos->{"www.".$res->response->zone_name}."</b> ";




           $zone_id=$zones->getZoneID($request->name);

           if($created)
           {
                $zone = Zone::create([
                    "name"         => $request->name,
                    "zone_id"      => $zone_id,

                    "status"       => 'active',
                    "type"         => 'partial',
                    "user_id"      => $domUser->id,
                    "cfaccount_id" => $cf->id,
                    "package_id"   => $request->package,

                ]);


                //Fetch Initial data in background
                FetchZoneSetting::dispatch($zone);
                FetchDns::dispatch($zone);
                FetchWAFPackages::dispatch($zone);
                FetchAnalytics::dispatch($zone);
                FetchFirewallRules::dispatch($zone);
                $request->session()->flash('status', $msg);


                return response()->json(['status'=>201, 'data' =>[
                    'success' => true,
                    'msg' => $msg,
                    'zoneid' => $zone->id,
                    'status' => $zone->status,


                ]]);

                // return redirect()->route('admin.zones.index');
           }
           else
           {
                 $request->session()->flash('error', $msg);
                // return redirect()->route('admin.zones.create');
                  return response()->json(['status'=>201, 'data' =>[
                    'success' => false,
                    'msg' => $msg,


                ]]);
           }

            }

        }
        else
        {

            // Execute the API command for the selected CF Account


            $result = $zones->addZone($request->name, true);

            //var_dump($zr);
            if ($result) {
                $zone = Zone::create([
                    "name"         => $request->name,
                    "zone_id"      => $result->id,
                    "name_server1" => $result->name_servers[0],
                    "name_server2" => $result->name_servers[1],
                    "status"       => $result->status,
                    "type"         => $result->type,
                    "user_id"      => $domUser->id,
                    "cfaccount_id" => $cf->id,
                    "package_id"   => $request->package,

                ]);


                //Fetch Initial data in background
                FetchZoneSetting::dispatch($zone);
                FetchDns::dispatch($zone);
                FetchWAFPackages::dispatch($zone);
                FetchAnalytics::dispatch($zone);
                FetchFirewallRules::dispatch($zone);

                $msg='Please update the DNS at domain registrar for ' . $request->name . " to " . $result->name_servers[0] . " & " . $result->name_servers[1] . "</b>";

                return response()->json(['status'=>201, 'data' =>[
                    'success' => $created,
                    'msg' => $msg,
                    'zoneid' => $zone->id,
                    'status' => $zone->status,


                ]]);

            }

        }


             return response()->json(['status'=>201, 'data' =>[
                    'success' => $created,
                    'msg' => $msg,
                    'zoneid' => $zone->id,
                    'status' => $zone->status,


                ]]);

            // return redirect()->route('admin.zones.index');

        }

    }


    public function cfApi($zone,Request $request)
    {
	

      $user=\Auth::user();

      $allowed_endpoints=[
          ["post","zones/:zone_id/firewall/access_rules/rules"],
      ];
      $zone=Zone::where("name",$zone)->first();

      if($user->id!=$zone->user_id)
      {
        echo json_encode(["success"=> false, "message"=> "Zone Ownership Verification Failed"]);
        die;
      }




      $path=str_replace("api/","",$request->path());
      $path=str_replace($zone->name,$zone->zone_id,$path);

      $key     = new \Cloudflare\API\Auth\APIKey($zone->cfAccount->email, $zone->cfAccount->user_api_key);
      $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
      $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);

      $Proxy   = new \Cloudflare\API\Endpoints\Proxy($adapter);
      $data = json_decode(file_get_contents('php://input'), true);

      // dd($request);
      // $ret=$AccessRules->createRule($zone->zone_id,$data['mode'],$data['configuration']);

      $ret=$Proxy->post($path,$data);
      echo $ret;
      // echo json_encode($ret);
      // echo "sf";
      // medeliver.net

    }

    public function show(Request $request)
    {




         $credentials = $request->only('email', 'password');
         // $credentials['password']=bcrypt($credentials['password']);
         // dd($credentials);
         $user=User::where('email',$credentials['email'])->first();


        // dd();
        if (!\Hash::check($credentials['password'],$user->password)) {


              return response()->json(['status'=>201, 'data' =>[
                    'success' => false,
                    'msg' => "Could not authenticate subuser",



                ]]);

             die();

        }

        $zone = zone::where('id', $request->get('zone_id'))->first();
        $user= User::where("email",$request->get('email'))->first();
// dd($user->id);
        if($user->id != $zone->user->id)
        {
              return response()->json(['status'=>201, 'data' =>[
                    'success' => false,
                    'msg' => "Zone is not assigned to this user",
                    'zoneid' => $zone->id,
                    'status' => $zone->status,


                ]]);

             die();
        }


        $user->whmcs_token=str_random(191);
        $user->save();
        // $this->auth();

        // dd($zone);

        // dd($zone);
        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        // dd($zone->user->email);
        $zoneSetting = $zone->zoneSetting;
        if($zoneSetting->count()==0)
        {
            return response()->json(['status'=>201, 'data' =>[
                    'success' => false,
                    'msg' => "No data is configured yet",
                    'zoneid' => $zone->id,
                    'status' => $zone->status,


                ]]);

             die();
        }
        $securityLevel = $zoneSetting->where('name', 'security_level')->first()->value;
        // Caching Level
        $cachingLevel = $zoneSetting->where('name', 'browser_cache_ttl')->first()->value;
        //SSL
        $ssl = $zoneSetting->where('name', 'ssl')->first()->value;
        //Development Mode
        $developmentMode = $zoneSetting->where('name', 'development_mode')->first()->value;
        // DNS Records
        $dNSRecords = $zone->dns->count();
        // Email Address Obfuscation
        $emailAddressObfuscation = $zoneSetting->where('name', 'email_obfuscation')->first()->value;



        $data=['security_level' => $securityLevel, 'browser_cache_ttl' => $cachingLevel, 'ssl' => $ssl, 'development_mode' => $developmentMode, 'dns_records' => $dNSRecords, 'email_obfuscation' => $emailAddressObfuscation, 'permissions' => $user->getAbilities()->pluck('name')->toArray()];
        return response()->json(['status'=>201, 'data' =>[
                    'success' => true,

                    'zoneid' => $zone->id,
                    'status' => $zone->status,
                    'data'  => $data


                ]]);
    }


    public function sso_token(Request $request)
    {




         $credentials = $request->only('email', 'password');
         // $credentials['password']=bcrypt($credentials['password']);
         // dd($credentials);
         $user=User::where('email',$credentials['email'])->first();


        // dd();
        if (!\Hash::check($credentials['password'],$user->password)) {


              return response()->json(['status'=>201, 'data' =>[
                    'success' => false,
                    'msg' => "Could not authenticate subuser",



                ]]);

             die();


        }
        $zone = zone::where('id', $request->get('zone_id'))->first();
        $user= User::where("email",$request->get('email'))->first();
// dd($user->id);
        if($user->id != $zone->user->id)
        {
              return response()->json(['status'=>201, 'data' =>[
                    'success' => false,
                    'msg' => "Zone is not assigned to this user",


                ]]);

             die();
        }


        $user->whmcs_token=str_random(191);
        $user->save();
        // $this->auth();

        // dd($zone);

        // dd($zone);


        // dd($zone->user->email);



        $data=[];
        return response()->json(['status'=>201, 'data' =>[
                    'success' => true,
                    'token'  => $user->whmcs_token


                ]]);
    }

    public function performance($zone)
    {
        $this->auth();
        $zone        = $this->user->zone->where('name', $zone)->first();
        $zoneSetting = $zone->zoneSetting;

        // Auto Minify
        $javascript = $zoneSetting->where('name', 'minify_js')->first()->value;
        $minifyCss  = $zoneSetting->where('name', 'minify_css')->first()->value;
        $minifyHtml = $zoneSetting->where('name', 'minify_html')->first()->value;
        // GEt polish
        $polish = $zoneSetting->where('name', 'polish')->first()->value;
        // Get Mirage
        $mirage = $zoneSetting->where('name', 'mirage')->first()->value;
        // Rocket Loader™
        $rocketLoader = $zoneSetting->where('name', 'rocket_loader')->first()->value;

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        //      if(!(auth()->user()->id == $zone->user->id OR auth()->user()->owner == $zone->user->id  OR auth()->user()->id == $zone->{$zoneType}->reseller->id OR auth()->user()->id == 1))
        // {
        //         return abort(401);
        // }

        if ($zoneType == "cfaccount") {
            return response()->json(['minify' => ['js' => $javascript, 'css' => $minifyCss, 'html' => $minifyHtml],
                'polish'                          => $polish, 'mirage' => $mirage, 'rocket_loader' => $rocketLoader,
                'status'                          => 200]);
        }
        /*else
    {
    return view('admin.zones.spperformance', compact('zone','zoneSetting'));
    }*/

    }

    public function caching($zone)
    {
        $this->auth();
        $zone        = $this->user->zone->where('name', $zone)->first();
        $zoneSetting = $zone->zoneSetting;

        // Caching Level
        $cachingLevel = $zoneSetting->where('name', 'cache_level')->first()->value;

        // Browser Cache Expiration
        $browser_cache_ttl = $zoneSetting->where('name', 'browser_cache_ttl')->first()->value;

        // Always Online
        $always_online = $zoneSetting->where('name', 'always_online')->first()->value;

        // Development Mode
        $development_mode = $zoneSetting->where('name', 'development_mode')->first()->value;

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        /*if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
        return abort(401);
        }*/

        if ($zoneType == "cfaccount") {
            return response()->json(['cache_level' => $cachingLevel, 'browser_cache_ttl' => $browser_cache_ttl, 'always_online' => $always_online, 'development_mode' => $development_mode,
                'status'                               => 200]);
        } else {
            // Default Cache Time
            $cacheValid = $zoneSetting->where('name', 'cache_valid')->first()->value;
            return response()->json(['cache_valid' => $cacheValid,
                'status'                               => 200]);
        }

    }

    public function network($zone)
    {
        $this->auth();
        $zone = $this->user->zone->where('name', $zone)->first();
        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        $zoneSetting = $zone->zoneSetting;
        // WebSockets
        $websockets = $zoneSetting->where('name', 'websockets')->first()->value;

        // Pseudo IPv4
        $pseudoIpv4 = $zoneSetting->where('name', 'pseudo_ipv4')->first()->value;

        // IP Geolocation
        $ipGeolocation = $zoneSetting->where('name', 'ip_geolocation')->first()->value;

        /*if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
        return abort(401);
        }*/
        return response()->json(['websockets' => $websockets, 'pseudo_ipv4' => $pseudoIpv4, 'ip_geolocation' => $ipGeolocation,
            'status'                              => 200]);
    }

    public function contentProtection($zone)
    {

        $this->auth();
        $zone = $this->user->zone->where('name', $zone)->first();
        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }
        $zoneSetting = $zone->zoneSetting;

        // Email Address Obfuscation
        $emailObfuscation = $zoneSetting->where('name', 'email_obfuscation')->first()->value;

        // Server-side Excludes
        $serverSideExclude = $zoneSetting->where('name', 'server_side_exclude')->first()->value;

        // Hotlink Protection
        $hotlinkProtection = $zoneSetting->where('name', 'hotlink_protection')->first()->value;

        /* if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
        return abort(401);
        }
         */
        return response()->json(['email_obfuscation' => $emailObfuscation, 'server_side_exclude' => $serverSideExclude, 'hotlink_protection' => $hotlinkProtection,
            'status'                                     => 200]);

    }
    public function pageRules($zone)
    {
        $this->auth();
        $zone = $this->user->zone->where('name', $zone)->first();
        // dd($zone->id);

        // FetchPageRules::dispatch($zone, true)->onConnection('sync');

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        /*if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
        return abort(401);
        }*/

        if ($zoneType == "cfaccount") {

            $pagerules = $zone->PageRule->sortByDesc("priority")->pluck('value', 'pageRuleAction');

            // $pageRuleAction = pageRuleAction::where('action','value')->get();

            return response()->json(['pagerules' => $pagerules,
                'status'                             => 200]);
            // return view('admin.pagerules.index', compact('zone', 'pagerules'));
        } else {
            die();
        }

    }
    public function addPageRule(Request $request)
    {
        //

        $zone_id = $request->input('zid');
        $this->auth();
        $zone = $this->user->zone->where('name', $zone)->first();
        // if (!(auth()->user()->id == $zone->user->id or auth()->user()->id == $zone->user->owner or auth()->user()->id == 1)) {
        //     return abort(401);
        // }

        $url = $request->input('url');

        $data = [
            'record_ID' => 'PENDING',
            'value'     => $url,
            'status'    => 'active',
            'priority'  => null,

            'zone_id'   => $zone_id,
        ];

        $actions = $request->input('action');
        // dd($actions);
        $values = $request->input('actionValue');
        $extra  = $request->input('extra');

        if ($actions[0] == null) {
            echo "You should add atleast one action";
            die();
        }

        $pageRule = PageRule::create($data);

        foreach ($actions as $key => $action) {

            if ($action == null) {
                echo "Please select action";
                $pageRule->delete();
                die();
            }
            # code...
            $value = $values[$key];
            if ($action == "forwarding_url") {
                $value = $values[$key] . ",SPLIT," . $extra[$key];
            }
            $data =
                [
                'pagerule_id' => $pageRule->id,
                'action'      => $action,
                'value'       => $value,

            ];

            PageRuleAction::create($data);
        }
        // echo "Rule Created";
        // UpdatePageRule::dispatch($zone, $pageRule->id)->onConnection('sync');

        return response()->json([
            'status' => 200]);
    }

}
