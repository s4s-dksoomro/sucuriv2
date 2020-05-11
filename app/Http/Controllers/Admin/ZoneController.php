<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Sucuri;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

use App\Cfaccount;
use App\CustomCertificate;
use App\customDomain;
use App\Dns;
use App\FirewallRule;
// use App\Http\Controllers\Controller;
use App\Jobs\AddCustomCertificate;
use App\Jobs\DeleteCustomCertificate;
use App\Jobs\DeletePageRule;
use App\Jobs\FetchAnalytics;
use App\Jobs\FetchCustomCertificates;
use App\Jobs\FetchDns;
use App\Jobs\FetchELSAnalytics;
use App\Jobs\FetchFirewallRules;
use App\Jobs\FetchPageRules;
use App\Jobs\FetchSpAnalytics;
use App\Jobs\FetchSpZoneSetting;
use App\Jobs\FetchUaRules;
use App\Jobs\FetchWAFPackages;
use App\Jobs\FetchZoneDetails;
use App\Jobs\FetchZoneSetting;
use App\Jobs\FetchZoneStatus;
use App\Jobs\PauseZone;
use App\Jobs\PurgeCache;
use App\Jobs\stackPath\createCustomDomain;
use App\Jobs\stackPath\DeleteCustomDomain;
use App\Jobs\stackPath\DeleteWAFRule;
use App\Jobs\stackPath\FetchCustomDomains;
use App\Jobs\stackPath\FetchWAFEvents;
use App\Jobs\stackPath\FetchWAFPolicies;
use App\Jobs\stackPath\FetchWAFRules;
use App\Jobs\stackPath\UpdateWAFRule;
use App\Jobs\stackPath\FetchELSLogs;
use App\Jobs\UpdatePageRule;
use App\Jobs\UpdateSetting;
use App\Jobs\UpdateSpSetting;
use App\Package;
use App\PageRule;
use App\PageRuleAction;
use App\panelLog;
use App\Spaccount;
use App\SpCondition;
use App\SpRule;
use App\User;
use App\wafGroup;
use App\wafPackage;
use App\Zone;
use App\ZoneSetting;
use Carbon\Carbon;


use SSH;
use URL;
use Bouncer;
use Redirect;
use \GuzzleHttp\Client;

use App\Libraries\Cfhost;


class ZoneController extends Controller
{

    /**
     * Show All Zones
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

          $sucuri_user = DB::table('sucuri_user')->get();

        //return $sucuri_user = Sucuri::get();

      //  https://monitor22.sucuri.net/scan-api.php?k=1054b11e54487d530f7ddf24f279e61f61fbf1410f9e713786&a=scan

//          $curl = curl_init();

// curl_setopt($curl, CURLOPT_POST, 1);
// curl_setopt($curl, CURLOPT_POSTFIELDS, 1);
// curl_setopt($curl, CURLOPT_URL, 'https://monitor22.sucuri.net/api.php?k=687ba044faad818492c050b83958e3e98afc42146a264e556e&a=scan&host=opticalmart.com');
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
// $result = curl_exec($curl);
// if(!$result){die("Connection Failure");}
// curl_close($curl);
//  return $result;
// die();
 $ided=auth()->user()->id;


if($ided != 1){
          $sucuri_user = DB::table('sucuri_user')->where([['user_id' , $ided]])->get();
          return  view('admin.zones.index', ['sucuri_user'=>$sucuri_user]);
        } else {
        $sucuri_user = DB::table('sucuri_user')->get();        
          // dd($sucuri_user);
         return  view('admin.zones.index', ['sucuri_user'=>$sucuri_user]);
        }
    }

    /**
     * Show Soft Deleted Zones
     * @param  Request $request Request Variable - We used this to get zone type
     * @return \Illuminate\Http\Response           Return View
     */
    

    function backToApproved(Request $req){
        $sucuri_suer = DB::table('sucuri_user')->where('id',$req->id)->get();
        $s_key = $sucuri_suer[0]->s_key;
        if(!empty($s_key) && $s_key != ""){
            DB::table('sucuri_user')->where('id',$req->id)->update(['active' => '1' ]);
        }            
        else{    
            DB::table('sucuri_user')->where('id',$req->id)->update(['active' => '1' , 's_key' => '' ]);
        } 
        $sucuri_user = DB::table('sucuri_user')->get();

 $ided=auth()->user()->id;


if($ided != 1){
          $sucuri_user = DB::table('sucuri_user')->where([['user_id' , $ided]])->get();
          return  view('admin.zones.index', ['sucuri_user'=>$sucuri_user,'messages'=>'Approved/Pending Successful']);
        } else {
        $sucuri_user = DB::table('sucuri_user')->get();        
          // dd($sucuri_user);
         return  view('admin.zones.index', ['sucuri_user'=>$sucuri_user,'messages'=>'Approved/Pending Successful']);
        }
           
        }




    public function trashedZones(Request $request)
    {
        //

        
        if ($request->input('type') == "sp") {
            $type = "sp"; //SP Zones
        } else {
            $type = "cf";
        }

        if (auth()->user()->id == 1) {
            $zones = Zone::onlyTrashed()->get();
        }

        // $spAccounts=User::find(auth()->user()->id)->getAbilities()->where('entity_type','App\Spaccount')->first()->entity_id;

        // $cfAccounts=User::find(auth()->user()->id)->getAbilities()->where('entity_type','App\Cfaccount')->first()->entity_id;

        // $zones = Zone::where('cfaccount_id',$cfAccounts)->orWhere('spaccount_id',$spAccounts)->where('owner',auth()->user()->id)->get();

        return view('admin.zones.trashed', compact('zones', 'type'));
    }

    /**
     * Show StackPath Zone Creation View
     * @return \Illuminate\Http\Response Return SpCreate View
     */
    public function spcreate()
    {
        //
        
        $users      = User::where('owner', auth()->user()->id)->get();
        $spaccounts = spaccount::where('reseller_id', auth()->user()->id)->get();

        return view('admin.zones.spcreate', compact('users', 'spaccounts'));
    }


    /**
     * Show Cloudflare Zone Creation View 
     * @return \Illuminate\Http\Response Return CF Create View
     */
    public function create()
    {
        //
      
        $users      = User::where('owner', auth()->user()->id)->get();
        $cfaccounts = cfaccount::where('reseller_id', auth()->user()->id)->get();
        // get packages from here
        $packages = package::all();

        return view('admin.zones.create', compact('users', 'cfaccounts', 'packages'));
    }

    public function created()
    {
        //
       
        $users      = User::where('owner', auth()->user()->id)->get();
        $cfaccounts = cfaccount::where('reseller_id', auth()->user()->id)->get();
        // get packages from here
        $packages = package::all();

        return view('admin.zones.created', compact('users', 'cfaccounts', 'packages'));
    }

    
    /**
     * Store CF Domain
     * @param  Request $request 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
      


      // var_dump(request('name'));
      //  var_dump(request('s_key'));
      //  var_dump(request('a_key'));
      //  var_dump(request('url'));

        $validatedData = $request->validate([
            'name' => 'required',
            
            'url' => 'required' ,
            'user_id' => 'required' ,
            
        ]);
         $ided=auth()->user()->id;
        if($ided > 1){
            $user = DB::select("select * from brandings b inner join packages p on (p.id = b.pckg_detail) where user_id = $ided");

            $domains = DB::select("SELECT COUNT(id) as id FROM sucuri_user WHERE user_id =".$ided." AND active !=2");
            $domainURL = DB::select("SELECT url FROM sucuri_user WHERE url = '".$request->input('url')."';");

            $totalNumberOfDomain = 0;
            $gainDomain = 0;
            foreach ($domains as $key ) {
                $gainDomain = $key->id;
            }
           
            foreach ($user as $key ) {
                $totalNumberOfDomain = $key->domains;
            }
            if($request->sbt == "Update" ){
                // Sucuri::update($request->all())->where("id" , $request->updatedID); 
                      DB::update('update sucuri_user set name = ? , url = ? , user_id = ?  where id = ?',[$request->name,$request->url,$request->user_id,$request->updatedID]);
    
                      $request->session()->flash('status', "Domain Updated");
                }
            else if($totalNumberOfDomain > $gainDomain){
                // Sucuri::create($request->all());
                if(!empty($domainURL)){ 
                   $request->session()->flash('status', "The Domain name (".$request->input('url').") has been added already in the system, please check again."); 
                } else {
                




                   $curl = curl_init();
            $auth_data = array(
            'k'         => '7302b26beb3438873cf29499591358fc',
            'under_ddos_attack='        => '0',
            'restrict_admin_access'     => '0',
            'use_sucuri_dns' => '0',
            'a'=>  'add_site',
            'domains'=>  $request->input('url'),
            'format'=>  'json'
            
            );
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
            curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $result = curl_exec($curl);
            if(!$result){die("Connection Failure");}
            curl_close($curl);
           // dd($result);
            //$result1 = utf8_encode($result);
            //$result2 =json_decode($result1);
            //$result=array($result);$result = json_decode($result , true);
           $result = json_decode($result , true);
           //dd($result);
//die('ok');

// $message="";
// $index=0;
// foreach($result as $ok => $data)
// {   $index++;
//     if($index == 3){
//         foreach ($data as $message) {
//             $this->message= $message;
//         }
//     }
// }



  $string = implode(" ",$result['output']);
 $status = $result['status'];

            //return $result->status ;


if($status==1){


    Sucuri::create([
        'name' => $request->input('name'),
        'url' => $request->input('url'),
        'user_id' => $request->input('user_id'),
        'a_key' => $request->input('a_key'),
        'updated_at' => $request->input('updated_at'),
        'created_at' => $request->input('created_at'),
       ]);  
                   $request->session()->flash('status', "The domain name has been added successfully. Awaiting approval from Admin for activation.");
               
}else{

    $request->session()->flash('status', $request->input('url').' '.$string);

}
               
                }
            } 
            else{
                $request->session()->flash('status', "Your request has not been processed. Your total domains are ".$totalNumberOfDomain." and the active domains are ".$gainDomain.".");   
            }

        }
        else if($ided == 1){
            if($request->user_id){
                $ided = $request->user_id;
                 $user = DB::select("select * from brandings b inner join packages p on (p.id = b.pckg_detail) where user_id = $ided");

            $domains = DB::select("SELECT COUNT(id) as id FROM sucuri_user WHERE user_id =".$ided."");
            $domainURL = DB::select("SELECT url FROM sucuri_user WHERE url = '".$request->input('url')."';");
            
            $totalNumberOfDomain = 0;
            $gainDomain = 0;
            foreach ($domains as $key ) {
                $gainDomain = $key->id;
            }
           foreach ($user as $key ) {
                $totalNumberOfDomain = $key->domains;
            }
            
            if($request->sbt == "Update" ){
            // Sucuri::update($request->all())->where("id" , $request->updatedID); 
                  DB::update('update sucuri_user set name = ? , url = ? , user_id = ?  where id = ?',[$request->name,$request->url,$request->user_id,$request->updatedID]);

                  $request->session()->flash('status', "Domain Updated");
            }
            else if($totalNumberOfDomain > $gainDomain){
                foreach ($domainURL as $key ) {
                    $domainURL = $key->url;
                }
                if(!empty($domainURL)){ 
                   $request->session()->flash('status', "The Domain name (".$request->input('url').") has been added already in the system, please check again."); 
                } else {
                //  Sucuri::create([
                //     'name' => $request->input('name'),
                //     'url' => $request->input('url'),
                //     'user_id' => $request->input('user_id'),
                //     'a_key' => $request->input('a_key'),
                //     'updated_at' => $request->input('updated_at'),
                //     'created_at' => $request->input('created_at'),
                //    ]);  
                   $curl = curl_init();
                   $auth_data = array(
                   'k'         => '7302b26beb3438873cf29499591358fc',
                   'under_ddos_attack='        => '0',
                   'restrict_admin_access'     => '0',
                   'use_sucuri_dns' => '0',
                   'a'=>  'add_site',
                   'domains'=>  $request->input('url'),
                   'format'=>  'json'
                   
                   );
                   curl_setopt($curl, CURLOPT_POST, 1);
                   curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
                   curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
                   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                   $result = curl_exec($curl);
                  // dd($result);
                   if(!$result){die("Connection Failure");}
                   curl_close($curl); 
                   $result = json_decode($result , true);
///die("ok2");
 
$string = implode(" ",$result['output']);
$status = $result['status'];

           //return $result->status ;


if($status==1){


   Sucuri::create([
       'name' => $request->input('name'),
       'url' => $request->input('url'),
       'user_id' => $request->input('user_id'),
       'a_key' => $request->input('a_key'),
       'updated_at' => $request->input('updated_at'),
       'created_at' => $request->input('created_at'),
      ]);  
                  $request->session()->flash('status', "The domain name has been added successfully. Awaiting approval from Admin for activation.");
              
}else{

   $request->session()->flash('status', $request->input('url').' '.$string);

}
               }
            }
            else{
                   $request->session()->flash('status', "Your request has not been processed. Your total domains are ".$totalNumberOfDomain." and the active domains are ".$gainDomain.".");   
                 }
            }
        }
        
        
           return redirect()->route('admin.zones.index')->with('success', 'Post Updated');
    }
   
    

    public function stored(Request $request)
    {
        //
      
//dd('op');

      // var_dump(request('name'));
      //  var_dump(request('s_key'));
      //  var_dump(request('a_key'));
      //  var_dump(request('url'));

        $validatedData = $request->validate([
            'name' => 'required',
            
            'url' => 'required' ,
            'user_id' => 'required' ,
            
        ]);
         $ided=auth()->user()->id;
        if($ided > 1){
            $user = DB::select("select * from brandings b inner join packages p on (p.id = b.pckg_detail) where user_id = $ided");

            $domains = DB::select("SELECT COUNT(id) as id FROM sucuri_user WHERE user_id =".$ided." AND active !=2");
            $domainURL = DB::select("SELECT url FROM sucuri_user WHERE url = '".$request->input('url')."';");

            $totalNumberOfDomain = 0;
            $gainDomain = 0;
            foreach ($domains as $key ) {
                $gainDomain = $key->id;
            }
           
            foreach ($user as $key ) {
                $totalNumberOfDomain = $key->domains;
            }
            if($request->sbt == "Update" ){
                // Sucuri::update($request->all())->where("id" , $request->updatedID); 
                      DB::update('update sucuri_user set name = ? , url = ? , user_id = ?  where id = ?',[$request->name,$request->url,$request->user_id,$request->updatedID]);
    
                      $request->session()->flash('status', "Domain Updated");
                }
            else if($totalNumberOfDomain > $gainDomain){
                // Sucuri::create($request->all());
                if(!empty($domainURL)){ 
                   $request->session()->flash('status', "The Domain name (".$request->input('url').") has been added already in the system, please check again."); 
                } else {
                




                   $curl = curl_init();
            $auth_data = array(
            'k'         => '7302b26beb3438873cf29499591358fc',
            'under_ddos_attack='        => '0',
            'restrict_admin_access'     => '0',
            'use_sucuri_dns' => '0',
            'a'=>  'add_site',
            'domains'=>  $request->input('url'),
            'format'=>  'json'
            
            );
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
            curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $result = curl_exec($curl);
            if(!$result){die("Connection Failure");}
            curl_close($curl);
           // dd($result);
            //$result1 = utf8_encode($result);
            //$result2 =json_decode($result1);
            //$result=array($result);$result = json_decode($result , true);
           $result = json_decode($result , true);
           //dd($result);
//die('ok');

// $message="";
// $index=0;
// foreach($result as $ok => $data)
// {   $index++;
//     if($index == 3){
//         foreach ($data as $message) {
//             $this->message= $message;
//         }
//     }
// }



  $string = implode(" ",$result['output']);
 $status = $result['status'];

            //return $result->status ;

//dd($string);
if($string=='You have already added this domain to your account' and $status==0 ){


    Sucuri::create([
        'name' => $request->input('name'),
        'url' => $request->input('url'),
        'user_id' => $request->input('user_id'),
        'a_key' => $request->input('a_key'),
        'updated_at' => $request->input('updated_at'),
        'created_at' => $request->input('created_at'),
       ]);  
                   $request->session()->flash('status', "The domain name has been added successfully. Awaiting approval from Admin for activation.");
               
}elseif($status==1) { Sucuri::create([
    'name' => $request->input('name'),
    'url' => $request->input('url'),
    'user_id' => $request->input('user_id'),
    'a_key' => $request->input('a_key'),
    'updated_at' => $request->input('updated_at'),
    'created_at' => $request->input('created_at'),
   ]);  
               $request->session()->flash('status', "The domain name has been added successfully. Awaiting approval from Admin for activation."); }else{

    $request->session()->flash('status', $request->input('url').' '.$string);

}
               
                }
            } 
            else{
                $request->session()->flash('status', "Your request has not been processed. Your total domains are ".$totalNumberOfDomain." and the active domains are ".$gainDomain.".");   
            }

        }
        else if($ided == 1){
            if($request->user_id){
                $ided = $request->user_id;
                 $user = DB::select("select * from brandings b inner join packages p on (p.id = b.pckg_detail) where user_id = $ided");

            $domains = DB::select("SELECT COUNT(id) as id FROM sucuri_user WHERE user_id =".$ided."");
            $domainURL = DB::select("SELECT url FROM sucuri_user WHERE url = '".$request->input('url')."';");
            
            $totalNumberOfDomain = 0;
            $gainDomain = 0;
            foreach ($domains as $key ) {
                $gainDomain = $key->id;
            }
           foreach ($user as $key ) {
                $totalNumberOfDomain = $key->domains;
            }
            
            if($request->sbt == "Update" ){
            // Sucuri::update($request->all())->where("id" , $request->updatedID); 
                  DB::update('update sucuri_user set name = ? , url = ? , user_id = ?  where id = ?',[$request->name,$request->url,$request->user_id,$request->updatedID]);

                  $request->session()->flash('status', "Domain Updated");
            }
            else if($totalNumberOfDomain > $gainDomain){
                foreach ($domainURL as $key ) {
                    $domainURL = $key->url;
                }
                if(!empty($domainURL)){ 
                   $request->session()->flash('status', "The Domain name (".$request->input('url').") has been added already in the system, please check again."); 
                } else {
                //  Sucuri::create([
                //     'name' => $request->input('name'),
                //     'url' => $request->input('url'),
                //     'user_id' => $request->input('user_id'),
                //     'a_key' => $request->input('a_key'),
                //     'updated_at' => $request->input('updated_at'),
                //     'created_at' => $request->input('created_at'),
                //    ]);  
                   $curl = curl_init();
                   $auth_data = array(
                   'k'         => '7302b26beb3438873cf29499591358fc',
                   'under_ddos_attack='        => '0',
                   'restrict_admin_access'     => '0',
                   'use_sucuri_dns' => '0',
                   'a'=>  'add_site',
                   'domains'=>  $request->input('url'),
                   'format'=>  'json'
                   
                   );
                   curl_setopt($curl, CURLOPT_POST, 1);
                   curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
                   curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
                   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                   $result = curl_exec($curl);
                  // dd($result);
                   if(!$result){die("Connection Failure");}
                   curl_close($curl); 
                   $result = json_decode($result , true);
///die("ok2");
 
$string = implode(" ",$result['output']);
$status = $result['status'];

           //return $result->status ;


if($string=='You have already added this domain to your account' and $status==0 ){


   Sucuri::create([
       'name' => $request->input('name'),
       'url' => $request->input('url'),
       'user_id' => $request->input('user_id'),
       'a_key' => $request->input('a_key'),
       'updated_at' => $request->input('updated_at'),
       'created_at' => $request->input('created_at'),
      ]);  
                  $request->session()->flash('status', "The domain name has been added successfully. Awaiting approval from Admin for activation.");
              
}elseif($status==1) { Sucuri::create([
    'name' => $request->input('name'),
    'url' => $request->input('url'),
    'user_id' => $request->input('user_id'),
    'a_key' => $request->input('a_key'),
    'updated_at' => $request->input('updated_at'),
    'created_at' => $request->input('created_at'),
   ]);  
               $request->session()->flash('status', "The domain name has been added successfully. Awaiting approval from Admin for activation."); } else{

   $request->session()->flash('status', $request->input('url').' '.$string);

}
               }
            }
            else{
                   $request->session()->flash('status', "Your request has not been processed. Your total domains are ".$totalNumberOfDomain." and the active domains are ".$gainDomain.".");   
                 }
            }
        }
        
        
           return redirect()->route('admin.zones.index')->with('success', 'Post Updated');
    }
    




    /**
     * Store stackPath Zone
     * @param  Request $request 
     * @return \Illuminate\Http\Response
     */
    public function spstore(Request $request)
    {
        //
      

         $id=$request->user_id;
         $internal_ip_main=$request->internal_ip_main;
         $security_level=$request->security_level;
         $admin_access=$request->admin_access;
         $comment_access=$request->comment_access;
         $cache_mode=$request->cache_mode;
         $detect_adv_evasion=$request->detect_adv_evasion;
         $aggressive_bot_filter=$request->aggressive_bot_filter;
         $compression_mode=$request->compression_mode;
         $force_https=$request->force_https;
         $spdy_mode=$request->spdy_mode;
         $max_upload_size=$request->max_upload_size;
         $force_sec_headers=$request->force_sec_headers;
         $unfiltered_html=$request->unfiltered_html;
         $block_php_upload=$request->block_php_upload;
         $behind_cdn=$request->behind_cdn;
         $http_flood_protection=$request->http_flood_protection;
         
          //$sucuri_users;


          //$users      = Sucuri::where('id',$sucuri_users)->get();
         
           $users  = DB::table('sucuri_user')->where('id',$id)->get();
          // dump($users->name); 
           foreach($users as $user){
            // return "$user->name";
             $s_key = "$user->s_key";
              $a_key= "$user->a_key";
         } 
          





         $curl1 = curl_init();
         $auth_data1 = array(
             'k'        => $a_key,
             's'        => $s_key,
             'a'    => 'update_setting',
             'new_internal_ip' =>  $internal_ip_main,
             'securitylevel' =>  $security_level,
             'adminaccess' =>  $admin_access,
             'commentaccess' =>  $comment_access,
             'docache' =>  $cache_mode,
             'detect_adv_evasion' =>  $detect_adv_evasion,
             'aggressive_bot_filter' =>  $aggressive_bot_filter,
             'compression_mode' =>  $compression_mode,
             'force_https' =>  $force_https,
             'spdy_mode' =>  $spdy_mode,
             'max_upload_size' =>  $max_upload_size,
             'force_sec_headers' =>  $force_sec_headers,
             'unfiltered_html' =>  $unfiltered_html,
             'block_php_upload' =>  $block_php_upload,
             'behind_cdn' =>  $behind_cdn,
             'http_flood_protection' =>  $http_flood_protection
             
             
         );
         curl_setopt($curl1, CURLOPT_POST, 1);
         curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
         curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
         curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
         $result1 = curl_exec($curl1);
         if(!$result1){die("Connection Failure");}
         curl_close($curl1);
         //$result1 = utf8_encode($result);
         //$result2 =json_decode($result1);
          //$result=array($result);
           $result1;
           $result1 = json_decode($result1 , true );


           $ApiMessage="";
           $index=0;
           foreach($result1 as $ok => $data)
           {    $index++;
               if($index == 3){
                   foreach ($data as $message) {
                       $ApiMessage=$message;
                   }
               }
           }

           $ApiMessage;
          $sucuri_users = DB::table("sucuri_user")->get();
          // return view('admin.zones.create',['sucuri_user'=>$sucuri_users])->with('message',$ApiMessage); 




        
         $curl = curl_init();
         $auth_data = array(
             'k'        => $a_key,
             's'        => $s_key,
             'a'    => 'show_settings',
             'format' =>  'json'
             
         );
         curl_setopt($curl, CURLOPT_POST, 1);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
         curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
         $result = curl_exec($curl);
         if(!$result){die("Connection Failure");}
         curl_close($curl);
         //$result1 = utf8_encode($result);
         //$result2 =json_decode($result1);
          //$result=array($result);
           $result;

        // return redirect()->route('ok');
      ///  view('admin/1/analytics');
        //return view('admin.zones.show',['ok'=>$result]);

     // return view('admin.zones.index',['sucuri_user'=>$sucuri_users])->with('message',$ApiMessage);;
      return Redirect::back()->with(['message', $ApiMessage]);


    }

   
   /**
    * Show the Zone Overview Page
    * @param  String $zone Domain Name passed to this function
    * @return \Illuminate\Http\Response       Returns Zone Overview Page
    */




public function pending($sucuri_users, Request $request){
     


 //$users      = Sucuri::where('id',$sucuri_users)->get();

  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();
 // dump($users->name);
 $a_key=""; 
  foreach($users as $user){
   // return "$user->name";
    $s_key = "$user->s_key";
     $a_key= "$user->a_key";
} 
 

$curl = curl_init();
$auth_data = array(
    'k'         => $a_key,
    's'         => $s_key,
    'a'     => 'show_settings',
    'format' =>  'json'
    
);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$result = curl_exec($curl);
if(!$result){die("Connection Failure");}
curl_close($curl);

    return view('admin.zones.show',['ok'=>$result]);
    
}
    

    public function show($sucuri_users, Request $request)
    {
// dd(auth()->user()->getAbilities()->pluck('name')->toArray());
// foreach (auth()->user()->getAbilities()->pluck('name')->toArray() as $ability)
// {
//     dd($ability);
// }
 
 $sucuri_users;


 //$users      = Sucuri::where('id',$sucuri_users)->get();

  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();
 // dump($users->name); 
  foreach($users as $user){
   // return "$user->name";
    $s_key = "$user->s_key";
     $a_key= "$user->a_key";
} 
 

$curl = curl_init();
$auth_data = array(
    'k'         => $a_key,
    's'         => $s_key,
    'a'     => 'show_settings',
    'format' =>  'json'
    
);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$result = curl_exec($curl);
if(!$result){die("Connection Failure");}
curl_close($curl);
//$result1 = utf8_encode($result);
//$result2 =json_decode($result1);
 //$result=array($result);
  $result;
  //$result->toJson();

//return $result->toJson(JSON_PRETTY_PRINT);
 //$result=var_dump ($result); //return  view('admin.zones.show', ['sucuri_user'=>$result]);
   //view('admin.zones.show', compact('sucuri_user', 'result'));
   // view('admin.zones.show')->with('result', $result);

    return view('admin.zones.show',['ok'=>$result]);
//echo  $request->route('id');
 
 
 // 
        //Dispatch Background processes.
       

        //return view('admin.zones.show');
           // return view('admin.zones.show', compact('zone', 'zoneSetting'));
       
    }


    /**
     * Update the Enterprise Log share Enable/Disable Status
     * @param  Request $request 
     *   
     */
    public function elsSetting(Request $request)
    {

        // dd("ytest");

        $data = $request->all();
        $zone = Zone::find($data['id']);

        if (auth()->user()->id == 1) {
            if ($data['value'] != 1) {
                $data['value'] = 0;
                $zone->els     = $data['value'];

                $zone->save();
                echo "Disabled,ELS Disabled,success";
            } else {


                if($zone->cfaccount_id!="0")
                {



                $key        = new \Cloudflare\API\Auth\APIKey($zone->cfaccount->email, $zone->cfaccount->user_api_key);
                $adapter    = new \Cloudflare\API\Adapter\Guzzle($key);
                $els        = new \Cloudflare\API\Endpoints\ELS($adapter);
                $internalID = $els->getInternalID($zone->zone_id);
                if ($internalID != "FALSE") {
                    $zone->internalID = $internalID;
                    $zone->els        = $data['value'];
                    $zone->els_bucket = $data['minutes'];
                    $zone->els_ts     = Carbon::now('UTC')->subHours($data['hours'])->timestamp;
                    $zone->save();
                    echo "Enabled, ELS Enabled,success";
                } else {

                    echo "Error!,Could not enable ELS. Please make sure that ELS is enabled at cloudflare end as well and then try again.,warning";
                }

            
            }
            else
            {

                    //


                 $hosts = [
    'http://elasticsearch:ONiNeVB5NRDNo&F9@CgJAi7d@148.251.176.73:9201'       // HTTP Basic Authentication
   
];

// dd($body);

// curl -XPUT http://<es node>:9200/.kibana/index-pattern/cloudflare -d '{"title" : "cloudflare",  "timeFieldName": "EdgeStartTimestamp"}'
$client = \Elasticsearch\ClientBuilder::create()
                    ->setHosts($hosts)
                    ->build();

 $indexParams['index']  = 'sp_'.$zone->zone_id;   
 $exists=$client->indices()->exists($indexParams);

// $params = ['index' => 'sp_'.$zone->zone_id];
// $response = $client->indices()->delete($params);

// die();
if(!$exists)
{

$indexName= $indexParams['index'];

$params = [
    'index' => $indexName,
    'body' => [
        'settings' => [
            'number_of_shards' => 1,
            'number_of_replicas' => 0
        ],
        'mappings' => [
            'doc' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'time' => [
                        'type' => 'date'
                        
                    ]

                ]
            ]
        ]
    ]
];




$response = $client->indices()->create($params);




// if ($internalID != "FALSE") {
                  
                // } else {

                //     echo "Error!,Could not enable ELS. Please make sure that ELS is enabled at cloudflare end as well and then try again.,warning";
                // }

}
  $zone->internalID = $zone->zone_id;
                    $zone->els        = $data['value'];
                    $zone->els_bucket = $data['minutes'];
                    $zone->els_ts     = Carbon::now('UTC')->subHours($data['hours'])->timestamp;
                    $zone->save();
                    echo "Enabled, ELS Enabled,success";


            }

            }

            // UpdateDnsRecord::dispatch($zone,$dns->id);

        }
    }


    


   


    /**
     * Update the ZoneSetting
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $zone
     * @return \Illuminate\Http\Response
     */
    public function updateSetting(Request $request, $zone)
    {
        //
        //sleep(2);
        $zone = Zone::where('name', $zone)->first();

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
            return abort(401);
        }

        $setId       = $request->input('id');
        $settingName = $request->input('setting');
        $setting     = $zone->zoneSetting->where('id', $setId)->where('name', $settingName)->first();

        if ($setting == null and $request->input('setting') == "zoneshield") {

            $setting = zoneSetting::create([
                'name'     => 'zoneshield',
                'zone_id'  => $zone->id,
                'editable' => 1,
                'value'    => 'Disabled',

            ]);

            $setId       = $setting->id;
            $settingName = "zoneshield";
        }

        $nameCorrections = [
            "tls_1_2_only" => " Require Modern TLS",
        ];

        if (isset($nameCorrections[$setting->name])) {
            $setName = $nameCorrections[$setting->name];
        } else {
            $setName = ucwords(str_replace("_", " ", $setting->name));
        }

        $setOld         = ucwords(str_replace("_", " ", $setting->value));
        $setting->value = $request->input('value');

        if ($setting->name == "always_use_https") {
            if ($request->input('value') == 0) {
                $setting->value = "off";
            } else {
                $setting->value = "on";
            }
        }
        $setting->save();
        $setting1 = zoneSetting::where('id', $setId)->where('name', $settingName)->first();
        $setNew   = ucwords(str_replace("_", " ", $setting1->value));

        if ($setOld == 0 and $setNew == 1) {
            $setOld = "Off";
            $setNew = "On";
        } elseif ($setOld == 1 and $setNew == 0) {
            $setOld = "On";
            $setNew = "Off";
        }

        $data = "<b>" . $setName . "</b> changed from " . $setOld . " to <b>" . $setNew . "</b>";
        echo $data;
        //echo($request->input('id'));

        if ($zone->cfaccount_id != 0) {
            UpdateSetting::dispatch($zone, $setting->id);
        } else {
            UpdateSpSetting::dispatch($zone, $setting->id);
        }

        panelLog::create([
            'user_id'    => auth()->user()->id,
            'zone_id'    => $zone->id,
            'name'       => 'Update Setting',
            'parameters' => $setting->id,
            'type'       => 3,

            'payload'    => $data,
        ]);
    }

    public function customActions(Request $request, $zone)
    {
        //

        $zone = Zone::where('name', $zone)->first();

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        
        if ($request->input('action') == "purgeCacheAll") {
            PurgeCache::dispatch($zone, true, [], []); //Zone, ALL, Files, Tags

            return response("Successfully purged all assets. Please allow up to 30 seconds for changes to take effect.");
        } elseif ($request->input('action') == "purgeFiles") {

            $files = str_replace("\r", "", $request->input('extra'));

            $files = explode("\n", $files);
            if (!is_array($files)) // User is allowed to enter comma separated URL's.
            {
                $files = explode(",", $files);
            }
            PurgeCache::dispatch($zone, false, $files, []); //Zone, ALL, Files, Tags

            return response("Successfully purged all assets. Please allow up to 30 seconds for changes to take effect.");
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */



    public function destroy($id)
    {
        //

        // DB::table('sucuri_user')->where('id',  $id)->delete();
        //
        DB::update('update sucuri_user set active = 0  where id = ?',[$id]);
        return redirect()->route('admin.zones.index');

    }

    public function restore(Request $request)
    {
        //

        $id = (int) $request->input('id');

        $zone = Zone::where('id', $id)->withTrashed()->first();

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        if (!(auth()->user()->id == 1)) // Only super admin can restore
        {

            return abort(401);
        }

        if (str_contains(URL::previous(), "trashed")) {

            if ($zoneType == "cfaccount") {
                PauseZone::dispatch($zone, false);
            }

            $zone->restore(); // Restore a Zone;
        } else {

        }

        return redirect()->route('admin.zones.trash');

    }

    public function crypto($zone)
    {

        $sucuri_user = DB::table('sucuri_user')->where('id',$zone)->get();
        return  view('admin.zones.crypto', ['sucuri_user'=>$sucuri_user]);
    }

public function seo($sucuri_users,Request $request)
    {

        $sucuri_users = DB::table('sucuri_user')->where('id',$sucuri_users)->get();
        return view('admin.zones.seo',['sucuri_user'=>$sucuri_users]);

    }
    public function addsite($sucuri_users,Request $request)
    {
       
$this->validate($request,[
            'add' => 'required'
        ]);
    
  $sucuri_users;
//   return $request->add;
    //$users      = Sucuri::where('id',$sucuri_users)->get();
    
    $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();
    // dump($users->name); 
    foreach($users as $user){
    // return "$user->name";
    $s_key = "$user->s_key";
     $a_key= "$user->a_key";
    } 
    
    // return $request->email;
    // return $request->format;
    // return $request->period;
    $curl = curl_init();
    $auth_data = array(
    'k'         => $a_key,
    'under_ddos_attack='        => '0',
    'restrict_admin_access'     => '0',
    'use_sucuri_dns' => '0',
    'a'=>  'add_site',
    'domains'=>  $request->add,
    'format'=>  'json'
    
    );
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
    curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    //$result1 = utf8_encode($result);
    //$result2 =json_decode($result1);
    //$result=array($result);$result = json_decode($result , true);
    $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {   $index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ",$result['output']);
    
    //$result->toJson();
    
    //return $result->toJson(JSON_PRETTY_PRINT);
    //$result=var_dump ($result); //return  view('admin.zones.show', ['sucuri_user'=>$result]);
    //view('admin.zones.show', compact('sucuri_user', 'result'));
    // view('admin.zones.show')->with('result', $result);
    return view('admin.zones.seo', ['ok'=>$result, 'message'=> $string,'sucuri_user'=>$users]);
    
    // return view('admin.zones.show',['ok'=>$result]);
    
        // return $request->url;

    }

    public function deletesite($sucuri_users,Request $request)
    {
       
$this->validate($request,[
            'delete' => 'required'
        ]);
    
  $sucuri_users;
//   return $request->add;
    //$users      = Sucuri::where('id',$sucuri_users)->get();
    
    $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();
    // dump($users->name); 
    foreach($users as $user){
    // return "$user->name";
    $s_key = "$user->s_key";
     $a_key= "$user->a_key";
    } 
    
    // return $request->email;
    // return $request->format;
    // return $request->period;
    $curl = curl_init();
    $auth_data = array(
    'k'         => $a_key,
    'a'=>  'delete_site',
    'domain'=>  $request->delete,
    'format'=>  'json'
    
    );
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
    curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    //$result1 = utf8_encode($result);
    //$result2 =json_decode($result1);
    //$result=array($result);$result = json_decode($result , true);
    $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {   $index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ", (array)$result['output']);
    
    //$result->toJson();
    
    //return $result->toJson(JSON_PRETTY_PRINT);
    //$result=var_dump ($result); //return  view('admin.zones.show', ['sucuri_user'=>$result]);
    //view('admin.zones.show', compact('sucuri_user', 'result'));
    // view('admin.zones.show')->with('result', $result);
    return view('admin.zones.seo', ['ok'=>$result, 'message'=> $string,'sucuri_user'=>$users]);
    
    // return view('admin.zones.show',['ok'=>$result]);
    
        // return $request->url;

    }


    public function origin($zone)
    {

        $zone = Zone::where('name', $zone)->first();

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        $zoneSetting = $zone->zoneSetting;

        $customDomains = $zone->customDomain;
        if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
            return abort(401);
        }

        return view('admin.zones.origin', compact('zone', 'zoneSetting', 'customDomains'));

    }

    public function createCustomDomain(Request $request)
    {
        //

        $data = $request->all();

        $zone = Zone::find($data['zid']);

        if ($zone->user->id == \Auth::user()->id or auth()->user()->id == 1) {

            // $record_id=$customDomain->resource_id;
            // $customDomain->delete();

            $domain = [
                'custom_domain' => $data['customDomain'],

                'resource_id'   => 'PENDING',
                'zone_id'       => $zone->id,
            ];

            customDomain::create($domain);

            createCustomDomain::dispatch($zone, $data['customDomain'])->onConnection("sync");

            echo "";

        }

    }
    public function deleteCustomDomain(Request $request)
    {
        //

        $data         = $request->all();
        $customDomain = customDomain::find($data['id']);

        $zone = $customDomain->zone;

        if ($zone->user->id == \Auth::user()->id or auth()->user()->id == 1) {

            $record_id = $customDomain->resource_id;
            $customDomain->delete();

            DeleteCustomDomain::dispatch($zone, $record_id);

        }

    }

    public function loadBalancers($sucuri_users)
    {   
        $sucuri_users = DB::table('sucuri_user')->where('id',$sucuri_users)->get();

        return view('admin.zones.loadBalancers', ['sucuri_user'=> $sucuri_users]);

    }

    public function loadBalancer($sucuri_users,Request $request)
    {
        $this->validate($request,[
            'email' => 'required',
            'period' => 'required',
            'format' => 'required'
        ]);
    
  $sucuri_users;
    
    
    //$users      = Sucuri::where('id',$sucuri_users)->get();
    
    $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();
    // dump($users->name); 
    foreach($users as $user){
    // return "$user->name";
    $s_key = "$user->s_key";
     $a_key= "$user->a_key";
    } 
    
    // return $request->email;
    // return $request->format;
    // return $request->period;
    $curl = curl_init();
    $auth_data = array(
    'k'         => $a_key,
    's'         => $s_key,
    'a'     => 'email_reports_settings',
    'status' => 'enabled',
    'period'=>  $request->period,
    'format'=>  $request->format,
    'emails'=>  $request->email
    
    );
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
    curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    //$result1 = utf8_encode($result);
    //$result2 =json_decode($result1);
    //$result=array($result);
    // return $result;
    $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {   $index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ", (array)$result['output']);
    
    //$result->toJson();
    
    //return $result->toJson(JSON_PRETTY_PRINT);
    //$result=var_dump ($result); //return  view('admin.zones.show', ['sucuri_user'=>$result]);
    //view('admin.zones.show', compact('sucuri_user', 'result'));
    // view('admin.zones.show')->with('result', $result);
    return view('admin.zones.loadBalancers', ['ok'=>$result, 'message'=> $string,'sucuri_user'=> $users]);
    
    // return view('admin.zones.show',['ok'=>$result]);
    
        // return $request->url;
    }

    public function pageRules($zone)
    {

        $zone = Zone::where('name', $zone)->first();

        FetchPageRules::dispatch($zone, true)->onConnection('sync');

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
            return abort(401);
        }

        if ($zoneType == "cfaccount") {

            $pagerules = $zone->PageRule->sortByDesc("priority");

            return view('admin.pagerules.index', compact('zone', 'pagerules'));
        } else {
            die();
        }

    }
    
    public function addPageRule(Request $request)
    {
        //

        $zone_id = $request->input('zid');

        

        $url = $request->input('url');

        $data = [
            'record_ID' => 'PENDING',
            'value'     => $url,
            'status'    => 'active',
            'priority'  => null,

            'zone_id'   => $zone_id,
        ];

        $actions = $request->input('action');
        $values  = $request->input('actionValue');
        $extra   = $request->input('extra');

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
        UpdatePageRule::dispatch($zone, $pageRule->id)->onConnection('sync');

        echo "success";
        // return redirect()->route('admin.pagerules',['zone'   =>  $zone->name]);
    }


    public function addWAFRule(Request $request)
    {
        

        $zone_id = $request->input('zid');

        
        $name   = $request->input('name');
        $action = $request->input('action');

        $data = [
            'record_ID' => 'PENDING',
            'action'    => $action,
            'active'    => '1',
            'name'      => $name,

            'zone_id'   => $zone_id,
        ];

        $spRule = SpRule::create($data);

        $actions = $request->input('scope');
        $values  = $request->input('data');
        $values2 = $request->input('data2');
        $extra   = $request->input('extra');

        foreach ($actions as $key => $action) {
            # code...
            $value = $values[$key];
            if ($action == "IpRange") {
                if (ip2long($values[$key]) >= ip2long($values2[$key])) {
                    echo "IP Range invalid, Please check starting and ending IP's again.";

                    $spRule->delete();
                    die();
                }
                $value = $values[$key] . "," . $values2[$key];
            }
            $data =
                [
                'sprule_id' => $spRule->id,
                'scope'     => $action,
                'data'      => $value,

            ];

            SpCondition::create($data);
        }

        UpdateWAFRule::dispatch($zone, $spRule->id)->onConnection('sync');
       
    }

    public function editWAFRule(Request $request)
    {
      

        $zone_id = $request->input('zid');
        $rule_id = $request->input('ruleid');

        
        $name   = $request->input('name');
        $action = $request->input('action');

        $data = [

            'name'   => $name,
            'action' => $action,

        ];

        $pageRule = spRule::findOrFail($rule_id);

        $pageRule->update($data);

        $pageRule->save();

        $actions = $request->input('scope');
        $values  = $request->input('data');
        $values2 = $request->input('data2');
        $extra   = $request->input('extra');

        $actionID = $request->input('actionID');
        $delete   = $request->input('delete');
        $extra    = $request->input('extra');

        foreach ($actions as $key => $action) {
            # code...

            $value = $values[$key];
            if ($action == "IpRange") {
                $value = $values[$key] . "," . $values2[$key];
            }

            if (isset($delete[$key]) and $delete[$key] == "true") {
                $SpCondition = SpCondition::findOrFail($actionID[$key]);
                $SpCondition->delete();
            } elseif (isset($actionID[$key])) {

                $data =
                    [
                    'scope' => $action,
                    'data'  => $value,
                ];

                $SpCondition = SpCondition::findOrFail($actionID[$key]);

                $SpCondition->update($data);

                $SpCondition->save();
            } else {

                $data =
                    [
                    'sprule_id' => $pageRule->id,
                    'scope'     => $action,
                    'data'      => $value,

                ];

                SpCondition::create($data);

            }

        }
       
        UpdateWAFRule::dispatch($zone, $pageRule->id, auth()->user()->id);
        
    }

    public function editPageRule(Request $request)
    {
        //

        $zone_id = $request->input('zid');
        $rule_id = $request->input('ruleid');

        
        $url = $request->input('url');

        $data = [

            'value' => $url,

        ];

        $pageRule = PageRule::findOrFail($rule_id);

        $pageRule->update($data);

        $pageRule->save();

        $actions  = $request->input('action');
        $values   = $request->input('actionValue');
        $actionID = $request->input('actionID');
        $delete   = $request->input('delete');
        $extra    = $request->input('extra');

        if ($actions[0] == null) {
            echo "You should add atleast one action";
            die();
        }

        foreach ($actions as $key => $action) {
            # code...

            if ($action == null) {
                echo "Please select action";
                //$pageRule->delete();
                die();
            }

            $value = $values[$key];
            if ($action == "forwarding_url") {
                $value = $values[$key] . ",SPLIT," . $extra[$key];
            }

            if (isset($delete[$key]) and $delete[$key] == "true") {
                $PageRuleAction = PageRuleAction::findOrFail($actionID[$key]);
                $PageRuleAction->delete();
            } elseif (isset($actionID[$key])) {

                $data =
                    [
                    'action' => $action,
                    'value'  => $value,
                ];

                $PageRuleAction = PageRuleAction::findOrFail($actionID[$key]);

                $PageRuleAction->update($data);

                $PageRuleAction->save();
            } else {

                $data =
                    [
                    'pagerule_id' => $pageRule->id,
                    'action'      => $action,
                    'value'       => $value,

                ];

                PageRuleAction::create($data);

            }

        }

        UpdatePageRule::dispatch($zone, $pageRule->id)->onConnection('sync');

        echo "success";
        // return redirect()->route('admin.pagerules',['zone'   =>  $zone->name]);
    }

    public function sortPageRule(Request $request)
    {
        //

        $zone_id = $request->input('zid');

        
        $i = count($request->input('rule'));

        foreach ($request->input('rule') as $rule) {

            $data = [

                'priority' => $i,

            ];

            $pageRule = PageRule::findOrFail($rule);

            $pageRule->update($data);

            $pageRule->save();

            UpdatePageRule::dispatch($zone, $pageRule->id, false);

            $i--;
        }

        //echo "Rule Updated";

        // return redirect()->route('admin.pagerules',['zone'   =>  $zone->name]);
    }

    public function pageRuleStatus(Request $request)
    {

          $sucuri_user = DB::table('sucuri_user')->get();
         return  view('admin.zones.create', ['sucuri_user'=>$sucuri_user]);
        

    }

    public function destroyPageRule(Request $request)
    {
        //

        $data     = $request->all();
        $PageRule = PageRule::find($data['id']);

        $zone = $PageRule->zone;

        
        $rule_id = $PageRule->record_id;

        foreach ($PageRule->pageruleaction as $pageruleaction) {
            $pageruleaction->delete();
        }

        $PageRule->delete();

        DeletePageRule::dispatch($zone, $rule_id);

    }

    public function destroyCustomCertificate(Request $request)
    {
        //

        $data              = $request->all();
        $CustomCertificate = CustomCertificate::find($data['id']);

        $zone = $CustomCertificate->zone;

        if (!(auth()->user()->id == $zone->user->id or auth()->user()->id == $zone->user->owner or auth()->user()->id == 1)) {
            return abort(401);
        }

        $rule_id = $CustomCertificate->resource_id;

        $CustomCertificate->delete();

        DeleteCustomCertificate::dispatch($zone, $rule_id);

    }

    public function destroyWAFRule(Request $request)
    {
        //

        $data   = $request->all();
        $sprule = spRule::find($data['id']);

        $zone = $sprule->zone;


        $rule_id = $sprule->record_id;

        foreach ($sprule->SpCondition as $pageruleaction) {
            $pageruleaction->delete();
        }

        $sprule->delete();

        DeleteWAFRule::dispatch($zone, $rule_id);

    }

    public function caching($zone)
    {

        $zone        = Zone::where('name', $zone)->first();
        $zoneSetting = $zone->zoneSetting;

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }


        if ($zoneType == "cfaccount") {
            return view('admin.zones.caching', compact('zone', 'zoneSetting'));
        } else {
            return view('admin.zones.spcaching', compact('zone', 'zoneSetting'));
        }

    }

    public function changeOwnership($zoneId)
    {

        $zone = Zone::findOrFail((int) $zoneId);

        // dd($zone);

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }

        if (!(auth()->user()->id == 1)) {
            return abort(401);
        }

        // dd($zone->name);

        $users = User::whereIs('organization')->where('owner', auth()->user()->id)->with('roles')->get();

        return view('admin.zones.ownership', compact('zone', 'users'));

    }

    public function storeOwnership($zoneId, Request $request)
    {

        $zone = Zone::findOrFail((int) $zoneId);

        // dd($zone);

        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }


        // dd($zone->name);

        $zone->user_id = $request->input('user');

        $zone->save();

        $request->session()->flash('status', ' Ownership of ' . $zone->name . " Changed to <b>" . $zone->user->name . "</b> (<b>" . $zone->user->email . "</b> )");

        return redirect()->route('admin.zones.index');

    }

    public function network($zone)
    {
        

       
        $sucuri_user = DB::table('sucuri_user')->get();
         return  view('admin.zones.origin', ['sucuri_user'=>$sucuri_user]);

    }
    
    
    
    
    public function reportsettings($sucuri_users)
    {
        $sucuri_users;
        return view('admin.zones.reportsettings', ['message'=>'','sucuri_user'=>$sucuri_users]);

    }


    
    
    
    
    

    public function contentProtection($zone)
    {

        $sucuri_user = DB::table('sucuri_user')->where('id',$zone)->get();
         return  view('admin.zones.network', ['sucuri_user'=>$sucuri_user]);

    }


    public function contentZone(Request $request,$zone)
    {

      /*  if ($request->input('type') == "sp") {
           echo  $type = "sp"; //SP Zones
        } else {
          echo   $type = "cf";
        }

        */

/*
        if (auth()->user()->id == 1) {
               $users = User::whereIs('organization')->where('owner', auth()->user()->id)->with('zone')->get();
        } else {
            echo  $users = User::whereIs('organization')->where('owner', auth()->user()->id)->with('zone')->get();

        }
  */     
/*
        
         $user = User::whereIs('organization')->where('owner', auth()->user()->id)->first();

        // $spAccounts=User::find(auth()->user()->id)->getAbilities()->where('entity_type','App\Spaccount')->first()->entity_id;

        // $cfAccounts=User::find(auth()->user()->id)->getAbilities()->where('entity_type','App\Cfaccount')->first()->entity_id;

        // $zones = Zone::where('cfaccount_id',$cfAccounts)->orWhere('spaccount_id',$spAccounts)->where('owner',auth()->user()->id)->get();

         $user = User::findOrFail(auth()->user()->id);

      if (auth()->user()->id == 1) {
          $zones = Zone::onlyTrashed()->get();
    }


     $users      = User::where('owner', auth()->user()->id)->get();
        echo  $spaccounts = spaccount::where('reseller_id', auth()->user()->id)->get();

        


       
        
        $character = json_decode($spaccounts);
        echo $character->id;

        */

/*

        if (auth()->user()->id == 1) // Super admin is allowed to select cfaccount from dropdown.
        {
             $cf = Cfaccount::where('id', $request->cfaccount)->first();

        
        } else // resellers are only allowed the specific cfaccount
        {

             $user = User::find(auth()->user()->id);
               $cf   = Cfaccount::find($user->getAbilities()->where('entity_type', 'App\Cfaccount')->first()->entity_id);
echo "ok";

            //Check if User has not reached the zones limit
            if (!($user->branding and $user->cfZoneCount < $user->branding->cf)) {
                return abort(401);
            }
        }
        
       
      //  return $cf->user->email;
    echo  $key     = new \Cloudflare\API\Auth\APIKey($cf->email, $cf->user_api_key);
        // $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
       // $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);

         //echo $character["key"];

      //echo $character['2']->key;
//return $request[];


/*
        $zone = Zone::where('name', $zone)->first();
        if ($zone->cfaccount_id != 0) {
            $zoneType = "cfaccount";
        } else {
            $zoneType = "spaccount";
        }
         $zoneSetting = $zone->zoneSetting;
     echo  $zone->user->id ;
        if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
            return abort(401);
        }

      //  return view('admin.zones.contentZone', compact('zone', 'zoneSetting'));

        

*/

  $zone = Zone::where('name', $zone)->first();
if ($zone->cfaccount_id != 0) {
    $zoneType = "cfaccount";
} else {
    $zoneType = "spaccount";
}
$zoneSetting = $zone->zoneSetting;

if (!(auth()->user()->id == $zone->user->id or auth()->user()->owner == $zone->user->id or auth()->user()->id == $zone->{$zoneType}->reseller->id or auth()->user()->id == 1)) {
    return abort(401);
}
 $useride=$zone->user_id;
echo "<br>";
 $cfaccount_id=$zone->cfaccount_id;
echo "<br/>";
 $zone_id=$zone->zone_id;
echo "<br/>";
//echo $users      = User::where('id',auth()->user()->id )->get();
  $cfaccounts = cfaccount::where('id', $cfaccount_id)->first();
echo "<br/>";
  $email=$cfaccounts->email;
 echo "<br/>";
  $user_api_key=$cfaccounts->user_api_key;
 echo "<br/>";

/*



 $id = 'security_level';

 //Your account email address
 $email = $email;
 
 //Account API Key. you can get it from account settings
 $key = $user_api_key;
 
 //Domain identifier. you can get it by clicking on domain name then go to DNS page for example and right click on page
 //and click get page source and search for {"zones" then you will find [{"id":"XXXXX". XXXXX is the zone identifier
 $zone_identifier = $zone_id;
 
 $req = array(
   'http'=>array(
     'ignore_errors' => true,
     'method'=>"GET",
     'header'=>
     "X-Auth-Email: $email\r\n" .
     "X-Auth-Key: $key\r\n" .
     "Content-Type: application/json\r\n"
   )
 );
 
 $context = stream_context_create($req);
 
 $response = file_get_contents('https://api.cloudflare.com/client/v4/zones/023e105f4ecef8ad9ca31a8372d0c353/available_rate_plans', false, $context);
 
 //print response
 echo $response;

 */
/*

 $token = "kdqJmAgbOj5gfZXTTgzBMxcjm6FWxEcCE98cIVgD";
 $key = "kdqJmAgbOj5gfZXTTgzBMxcjm6FWxEcCE98cIVgD";
 //setup the request, you can also use CURLOPT_URL
 $ch = curl_init('https://api.cloudflare.com/client/v4/zones/023e105f4ecef8ad9ca31a8372d0c353/available_rate_plans');
 
 // Returns the data/output as a string instead of raw data
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
 //Set your auth headers
 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    "X-Auth-Email: $email" ,
     "X-Auth-Key: $key" 
    ));
 
 // get stringified data/output. See CURLOPT_RETURNTRANSFER
 echo $data = curl_exec($ch);
 echo "<br/>";
 echo "<br/>";
 // get info about the request
  $info = curl_getinfo($ch);
  print_r ($info);
 // close curl resource to free up system resources
 curl_close($ch);
 
 */

/*
$postRequest = array(
    'Content-Type'=> 'application/json',
    "X-Auth-Email"=> $email ,
     "X-Auth-Key"=> $user_api_key 
);

$cURLConnection = curl_init('https://api.cloudflare.com/client/v4/zones/023e105f4ecef8ad9ca31a8372d0c353/available_rate_plans');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

echo $apiResponse = curl_exec($cURLConnection);
curl_close($cURLConnection);

// $apiResponse - available data from the API request
 $jsonArrayResponse = json_decode($apiResponse);
*/

 $ch = curl_init();
$headers = array(
                 'X-Auth-Email:'.$email,
                 'X-Auth-Key:' .$user_api_key,
                 'Content-Type: application/json',
                  );
$data = array(
              'value' => 'off',
               );

               
$json = json_encode($data);
curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/$zone_id/settings/rocket_loader");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_exec($ch);
curl_close($ch);
echo "<br/>";

$ch = curl_init();
$headers = array(
                 'X-Auth-Email:'.$email,
                 'X-Auth-Key:' .$user_api_key,
                 'Content-Type: application/json',
                  );
//$data = array(
  //  addEventListener['fetch', event =>  [event.respondWith(fetch(event.request))] ]
    //           );

               
$json = json_encode($data);
curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/$zone_id/workers/routes");
//curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_exec($ch);
curl_close($ch);



}

}
