<?php

namespace App\Http\Controllers\API;

use App\Dns;
// Jobs
use App\Http\Controllers\Controller;
use App\PageRule;
use App\PageRuleAction;
use App\User;
use App\Zone;
use App\ZoneSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
// use Illuminate\Http\Request;

// use App\Http\Controllers\Controller;

class ZonsController extends Controller
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

    public function show($zone)
    {
        $this->auth();
        $zone = $this->user->zone->where('name', $zone)->first();

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        $zoneSetting = $zone->zoneSetting;

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

        return response()->json(['security_level' => $securityLevel, 'browser_cache_ttl' => $cachingLevel, 'ssl' => $ssl, 'development_mode' => $developmentMode, 'dns_records' => $dNSRecords, 'email_obfuscation' => $emailAddressObfuscation,
            'status'                                  => 200]);
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



    public function contentZone($zone)
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
