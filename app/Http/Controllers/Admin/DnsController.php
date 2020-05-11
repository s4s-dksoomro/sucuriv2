<?php

namespace App\Http\Controllers\Admin;

use App\Analytics;
use App\Dns;
use App\test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Zone;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\wafEvent;
//use App\Http\Controllers\Admin\Gate;
use App\wafRule;
use App\ElsAnalytics;
use App\Jobs\FetchAnalytics;
class DnsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function spAnalytics($zone, Request $request)
    {


        // die("sd");
        $zone =   Zone::where('name',$zone)->first();

        if($request->input('minutes') !==null)
        {
            $minutes=$request->input('minutes');
        }
        else
        {
            $minutes=43200;    
            // $minutes=259200;    
        }


         switch ($minutes) {
            case 1440:
                $timestamp = 'Last 24 Hours';
                $period="hourly";
                $limit=24;
                 $xlabel= 'hour';
                 $tsFormat='Y-m-d H';
                break;
             case 10080:
                $timestamp = 'Last 7 Days';
                $period="daily";
                $limit=7;
                 $xlabel= 'day';
                  $tsFormat='Y-m-d';
                break;
             case 43200:
                $timestamp = 'Last Month';
                $period="daily";
                $limit=30;
                 $xlabel= 'day';
                  $tsFormat='Y-m-d';
                break;

            case 259200:
                $timestamp = 'Last 6 Months';
                $period="daily";
                $limit=180;
                 $xlabel= 'day';
                  $tsFormat='Y-m-d';
            break;
            
            default:
                $timestamp = 'Last 24 Hours';
                 $xlabel= 'hour';
                 $period="hourly";
                  $tsFormat='Y-m-d H';
                break;
        }



        $latest=$zone->SpAnalytics->sortByDesc('timestamp')->first();
        if($latest)
        {
            $latest= $latest->timestamp;
        }
        else
        {
            $latest= Carbon::now()->format('Y-m-d H:i:s');
        }
        $ts = Carbon::createFromFormat('Y-m-d H:i:s',$latest)->subMinutes($minutes)->format('Y-m-d H:i:s');
        // dd($ts);
        //
        ////->where('timestamp',">=",$timestamp)
        ///
        
        $dataCollection=$zone->SpAnalytics->sortByDesc('timestamp');
        $statsCollection=$dataCollection->where('type','stats')->where('period',$period)->where('timestamp','>',$ts);
        $stats = $statsCollection->sortBy('timestamp')->all();

        $request_all = $statsCollection->sum("hit");
        $request_cached = $statsCollection->sum("cache_hit");
        $request_uncached = $statsCollection->sum("noncache_hit");
        


        $bandwidth = $statsCollection->sum("size");

         $threats_all = $statsCollection->sum("blocked");

        $bandwidth=number_format($bandwidth / (1024 * 1024 * 1024) , 2);


        $status_codes = $dataCollection->where('type','statuscodes')->where('period',$period)->where('timestamp','>',$ts)->sortByDesc('timestamp')->groupBy('timestamp')->all();

        // dd($status_codes);
        $filetypes = $dataCollection->where('type','filetypes')->where('period',$period)->groupBy('timestamp')->where('timestamp','>',$ts)->sortBy('timestamp')->all();
        
        $parsed_status=[];
        foreach (array_reverse($status_codes) as $key=>$status_period) {
            # code...
            $parsed_status[$key]['timestamp']=dateFormatting($status_period[0]['timestamp'],$xlabel);
            foreach ($status_period as $status) {
                # code...
                if(starts_with($status['status_code'],'2'))
                {
                    $parsed_status[$key]['2xx']=$status['hit'];
                }elseif(starts_with($status['status_code'],'3'))
                {
                    $parsed_status[$key]['3xx']=$status['hit'];
                }elseif(starts_with($status['status_code'],'4'))
                {
                    $parsed_status[$key]['4xx']=$status['hit'];
                }elseif(starts_with($status['status_code'],'5'))
                {
                    $parsed_status[$key]['5xx']=$status['hit'];
                }
                

            }
        }


$parsed_filetypes=[];


    
      

        foreach ($filetypes as $key=>$status_period) {
            # code...
            $parsed_filetypes[$key]['timestamp']=dateFormatting($status_period[0]['timestamp'],$xlabel);
            foreach ($status_period as $filetype) {
                # code...
               
                
                $parsed_filetypes[$key][$filetype['file_type']]=$filetype['hit'];
            }
        }

       // $stats_json=array();
        foreach ($stats as $stat) {
            # code...

            $stat['period']=dateFormatting($stat['timestamp'],$xlabel);
            $stat['cached']=$stat['cache_hit'];
            $stat['uncached']=$stat['noncache_hit'];
            $stat['size']= number_format($stat['size'] / (1024 * 1024 * 1024) , 2)."GB";

           unset($stat['id']);
           unset($stat['zone_id']);
           unset($stat['created_at']);
           unset($stat['updated_at']);
           unset($stat['type']);
           unset($stat['cache_hit']);
           unset($stat['noncache_hit']);
           unset($stat['timestamp']);
        }

        $stats=array_values($stats);
        $status_codes=array_values($parsed_status);

       // echo json_encode($parsed_filetypes);
       // die();


        

        $latest=$zone->wafEvent->sortByDesc('ts')->first();
        if($latest)
        {
            $latest= $latest->ts;

        }
        else
        {
            $latest= Carbon::now()->timestamp;
        }
         // dd($minutes);

        // $latest= Carbon::now()->timestamp;
        $tsEvent = Carbon::createFromTimestamp((int)$latest)->subMinutes($minutes)->timestamp;

        //$tsEvent = Carbon::now('UTC')->subMinutes(14444)->timestamp;
    // echo($latest);

    // echo($tsEvent);
         $events = $zone->wafEvent->sortByDesc('ts')->where('ts','>',$tsEvent)->sortBy('ts')->groupBy(function($date) use($tsFormat) {
            return Carbon::createFromTimestamp($date->ts)->format($tsFormat);
});;
        
        $threats=array();
         foreach ($events as $key=>$event) {
             # code...
             
             // dd($event);
            $threats[$key]['period']=dateFormatting(Carbon::createFromTimestamp($event->first()['ts'])->toDateString(),$xlabel);
            $threats[$key]['blocked']=$event->sum('blocked');
            // $threats[$key]['SRE'] = $event->where('scope','SRE')->count();
         }
         $threats=array_values($threats);
        return ['0' => 'admin.spanalytics.index', '1' =>compact('zone','stats','minutes','timestamp','request_all','request_uncached','request_cached','bandwidth','status_codes','threats_all','threats')];
    }


    public function spLogs($zone, Request $request)
    {
// dd();
  $zone =  $zoneObj = Zone::where('name',$zone)->first();
  if($request->input('start') !==null)
        {

            $correction=0;
            if(Session::has('current_time_zone'))
            {
                $correction=Session::get('current_time_zone');
            }
            $start=Carbon::parse($request->input('start'),'UTC')->timestamp-$correction;
            
            $end=Carbon::parse($request->input('end'),'UTC')->timestamp-$correction;

            // dd(dateFormatting(Carbon::now('UTC'),"logsAnalysis"));
// dd($end);

            $convert=true;
        }
        else
        {
            $end=Carbon::now('UTC')->timestamp;   
            $start=Carbon::now('UTC')->subMinutes(1440)->timestamp;  

            $convert=true;

        }

// dd($start);

        $hosts = [
    'http://elasticsearch:ONiNeVB5NRDNo&F9@CgJAi7d@148.251.176.73:9201'       // HTTP Basic Authentication
   
];


// curl -XPUT http://<es node>:9200/.kibana/index-pattern/cloudflare -d '{"title" : "cloudflare",  "timeFieldName": "EdgeStartTimestamp"}'
$client = \Elasticsearch\ClientBuilder::create()
                    ->setHosts($hosts)->build();
  


 $body='{
   "_source": ["time","method","scheme","query_string","hostname","user_agent","client_country","client_ip","uri","cache_status","status"],
    "size":10,
    "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "range": {
            "time": {
              "gte": '.$start.',
              "lte": '.$end.',
              "format": "epoch_second"
            }
          }
        }
      ]
    }
    },
    "sort": [
    {
      "time": {
        "order": "asc"
      }
    }
]
    
}';

$params = [
    'index' => 'sp_687080',
    'type' => 'doc',
    'body' => $body
];


                    $results = $client->search($params);  

$requests=false;
if(isset($results['hits']['hits']))
{
    $requests=$results['hits']['hits'];
}

// dd($results);
      


 // \App\User::find(1)->allow(['splogs']);
        // die("sd");
      
       
        return view('admin.zones.spLogs', compact('zone', 'zoneSetting','requests','start','end','convert'));
    }

    public function index($sucuri_users, Request $request)
    {
        
        //$zone =  $zoneObj = Zone::where('name',$zone)->first();

      $id=auth()->user()->id;
       $sucuri_users;

         $sucuri_users;

if($id!=1){
         $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
         //dd($users);
}else{  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }
         foreach($users as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl = curl_init();
       $auth_data = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
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

        return view('admin.dns.show',['ok'=>$result ,'id'=>$sucuri_users]);

//dd($records);
//return view('admin.analytics.index', compact('zone','records'));
    }

    public function urlpaths($sucuri_users, Request $request)
    {
        
        //$zone =  $zoneObj = Zone::where('name',$zone)->first();

      $id=auth()->user()->id;
       $sucuri_users;

        //  $sucuri_users;

// if($id!=1){
  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get(); 
         //dd($users);
// }else{
    $whitepath  = DB::table('url_paths')->where('domain_id',$sucuri_users)->where('list','whitelist_dir')->get();
    $blackpath  = DB::table('url_paths')->where('domain_id',$sucuri_users)->where('list','blacklist_dir')->get();
    // }
  //  return dd($blackpath);

         foreach($users as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl = curl_init();
       $auth_data = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
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
       $result1 = utf8_encode($result);
       $result2 =json_decode($result1);
        // $result=array($result);
         $result;
        // $paths =  Dns::all();
        // return Dns::find($sucuri_users);
        return view('admin.dns.urlpaths',['ok'=>$result ,'id'=>$sucuri_users, 'blackpath'=>$blackpath])->with('whitepath', $whitepath);

//dd($records);
//return view('admin.analytics.index', compact('zone','records'));
    }

    public function noncache($sucuri_users, Request $request)
    {
        
        //$zone =  $zoneObj = Zone::where('name',$zone)->first();

      $id=auth()->user()->id;
       $sucuri_users;

        //  $sucuri_users;

// if($id!=1){
  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get(); 
         //dd($users);
// }else{
    $whitepath  = DB::table('noncache')->where('domain_id',$sucuri_users)->where('list','noncache_dir')->get();
    
    // }
  //  return dd($blackpath);

         foreach($users as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl = curl_init();
       $auth_data = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
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
       $result1 = utf8_encode($result);
       $result2 =json_decode($result1);
        // $result=array($result);
         $result;
        // $paths =  Dns::all();
        // return Dns::find($sucuri_users);
        return view('admin.dns.noncache',['ok'=>$result ,'id'=>$sucuri_users])->with('whitepath', $whitepath);

//dd($records);
//return view('admin.analytics.index', compact('zone','records'));
    }

    public function blockcookie($sucuri_users, Request $request)
    {
        
        //$zone =  $zoneObj = Zone::where('name',$zone)->first();

      $id=auth()->user()->id;
       $sucuri_users;

        //  $sucuri_users;

// if($id!=1){
  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get(); 
         //dd($users);
// }else{
    $whitepath  = DB::table('blockcookie')->where('domain_id',$sucuri_users)->get();
    
    // }
  //  return dd($blackpath);

         foreach($users as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl = curl_init();
       $auth_data = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
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
       $result1 = utf8_encode($result);
       $result2 =json_decode($result1);
        // $result=array($result);
         $result;
        // $paths =  Dns::all();
        // return Dns::find($sucuri_users);
        return view('admin.dns.blockcookie',['ok'=>$result ,'id'=>$sucuri_users])->with('whitepath', $whitepath);

//dd($records);
//return view('admin.analytics.index', compact('zone','records'));
    }

    

    public function ip($sucuri_users, Request $request)
    {

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
         'k' 		=> $a_key,
         's' 		=> $s_key,
         'a' 	=> 'show_settings',
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
       $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {	$index++;
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
    return view('admin.dns.show', ['ok'=>$result, 'message'=> $string]);
     
         return view('admin.dns.show',['ok'=>$result]);
     //echo  $request->route('id');
      
      
      // 
             //Dispatch Background processes.
            
     
             //return view('admin.zones.show');
                // return view('admin.zones.show', compact('zone', 'zoneSetting'));
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function whiteip($id,$ip, Request $request)
    {
      
      return view('admin.dns.whiteip', compact('id','ip'));
    
    }
	
	 public function removewhite($sucuri_users,Request $request)
    {
     $id = $request->id;
     $sucuri_users;
 
		$this->validate($request,[
            'remove' => 'required',
			'id' => 'required'
        ]);
       $sucuri_users;
                                //$users      = Sucuri::where('id',$sucuri_users)->get();
                                if($id!=1){
                                  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
                                  //dd($users);
                         }else{  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }
                               //  dd($users);
                                // echo "ok"; 
                                 //die();
                                foreach($users as $user){
                                // return "$user->name";
                                $s_key = "$user->s_key";
                                    $a_key= "$user->a_key";
                                } 


                                $curl = curl_init();
                                $auth_data = array(
                                'k' 		=> $a_key,
                                's' 		=> $s_key,
                                'a' 	=> 'delete_whitelist_ip',
                                'ip'    =>  $request->remove,
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
								 $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {	$index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ",$result['messages']);

//		Next Function
		

	
if($id!=1){
  $users1  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
  //dd($users);
}else{  $users1  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }

       // $users1  = DB::table('sucuri_user')->where('id',$id)->get();
        // dump($users->name); 
         foreach($users1 as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl1 = curl_init();
       $auth_data1 = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
           'format' =>  'json'
         
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
	//return $string;
//    return " $string  <br> <a  href = '/admin/$request->id/dns'><button>Return</button></a>";
    //$result->toJson();
    
    //return $result->toJson(JSON_PRETTY_PRINT);
    //$result=var_dump ($result); //return  view('admin.zones.show', ['sucuri_user'=>$result]);
    //view('admin.zones.show', compact('sucuri_user', 'result'));
    // view('admin.zones.show')->with('result', $result);
	//return Redirect::back()->with(['message', "Deleted"]);
	//return view('admin.zones.show',['ok'=>$result]);
    
	return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]);

								 
      // return view('admin.dns.ip', compact('id','ip'));
    
    }

    public function removewhitedir($sucuri_users,Request $request)
    {
     $id = $request->id;
     $sucuri_users;
 
		$this->validate($request,[
      'remove' => 'required',
			'id' => 'required'
        ]);
       $sucuri_users;
       DB::delete('delete from url_paths where domain_id = ? and url = ? and list = ?',[$sucuri_users, $request->remove, 'whitelist_dir']);
                                //$users      = Sucuri::where('id',$sucuri_users)->get();
                                if($id!=1){
                                  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
                                  //dd($users);
                         }else{  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }
                               //  dd($users);
                                // echo "ok"; 
                                 //die();
                                foreach($users as $user){
                                // return "$user->name";
                                $s_key = "$user->s_key";
                                    $a_key= "$user->a_key";
                                } 


                                $curl = curl_init();
                                $auth_data = array(
                                'k' 		=> $a_key,
                                's' 		=> $s_key,
                                'a' 	=> 'update_setting',
                                'remove_whitelist_dir[]'    =>  $request->remove,
                                // 'format' =>  'json'
                                
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
								 $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {	$index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ",$result['messages']);

//		Next Function
		


// if($id!=1){
  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get(); 
         //dd($users);
// }else{
    $whitepath  = DB::table('url_paths')->where('domain_id',$sucuri_users)->where('list','whitelist_dir')->get();
    $blackpath  = DB::table('url_paths')->where('domain_id',$sucuri_users)->where('list','blacklist_dir')->get();
    DB::delete('delete from url_paths where id = ?',[$sucuri_users]);
    // }
  //  return dd($blackpath);

         foreach($users as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl = curl_init();
       $auth_data = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
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
       $result1 = utf8_encode($result);
       $result2 =json_decode($result1);
        // $result=array($result);
         $result;
        // $paths =  Dns::all();
        // return Dns::find($sucuri_users);
        
        return view('admin.dns.urlpaths',['ok'=>$result ,'id'=>$sucuri_users, 'blackpath'=>$blackpath, 'message'=> $string])->with('whitepath', $whitepath);

    
	// return view('admin.dns.urlpaths', ['ok'=>$result1, 'message'=> $string]);

								 
      // return view('admin.dns.ip', compact('id','ip'));
    
    }

    public function removeblackdir($sucuri_users,Request $request)
    {
     $id = $request->id;
     $sucuri_users;
 
		$this->validate($request,[
      'remove' => 'required',
			'id' => 'required'
        ]);
       $sucuri_users;
       DB::delete('delete from url_paths where domain_id = ? and url = ? and list = ?',[$sucuri_users, $request->remove, 'blacklist_dir']);
                                //$users      = Sucuri::where('id',$sucuri_users)->get();
                                if($id!=1){
                                  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
                                  //dd($users);
                         }else{  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }
                               //  dd($users);
                                // echo "ok"; 
                                 //die();
                                foreach($users as $user){
                                // return "$user->name";
                                $s_key = "$user->s_key";
                                    $a_key= "$user->a_key";
                                } 


                                $curl = curl_init();
                                $auth_data = array(
                                'k' 		=> $a_key,
                                's' 		=> $s_key,
                                'a' 	=> 'update_setting',
                                'remove_blacklist_dir[]'    =>  $request->remove,
                                // 'format' =>  'json'
                                
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
								 $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {	$index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ",$result['messages']);

//		Next Function
		


// if($id!=1){
  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get(); 
         //dd($users);
// }else{
    $whitepath  = DB::table('url_paths')->where('domain_id',$sucuri_users)->where('list','whitelist_dir')->get();
    $blackpath  = DB::table('url_paths')->where('domain_id',$sucuri_users)->where('list','blacklist_dir')->get();
    DB::delete('delete from url_paths where id = ?',[$sucuri_users]);
    // }
  //  return dd($blackpath);

         foreach($users as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl = curl_init();
       $auth_data = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
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
       $result1 = utf8_encode($result);
       $result2 =json_decode($result1);
        // $result=array($result);
         $result;
        // $paths =  Dns::all();
        // return Dns::find($sucuri_users);
        
        return view('admin.dns.urlpaths',['ok'=>$result ,'id'=>$sucuri_users, 'blackpath'=>$blackpath, 'message'=> $string])->with('whitepath', $whitepath);

    
	// return view('admin.dns.urlpaths', ['ok'=>$result1, 'message'=> $string]);

								 
      // return view('admin.dns.ip', compact('id','ip'));
    
    }

    public function removenoncache($sucuri_users,Request $request)
    {
     $id = $request->id;
     $sucuri_users;
 
		$this->validate($request,[
      'remove' => 'required',
			'id' => 'required'
        ]);
       $sucuri_users;
       DB::delete('delete from noncache where domain_id = ? and url = ? ',[$sucuri_users, $request->remove]);
                                //$users      = Sucuri::where('id',$sucuri_users)->get();
                                if($id!=1){
                                  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
                                  //dd($users);
                         }else{  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }
                               //  dd($users);
                                // echo "ok"; 
                                 //die();
                                foreach($users as $user){
                                // return "$user->name";
                                $s_key = "$user->s_key";
                                    $a_key= "$user->a_key";
                                } 


                                $curl = curl_init();
                                $auth_data = array(
                                'k' 		=> $a_key,
                                's' 		=> $s_key,
                                'a' 	=> 'update_setting',
                                'remove_noncache_dir[]'    =>  $request->remove,
                                // 'format' =>  'json'
                                
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
								 $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {	$index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ",$result['messages']);

//		Next Function
		


// if($id!=1){
  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get(); 
         //dd($users);
// }else{
    $whitepath  = DB::table('noncache')->where('domain_id',$sucuri_users)->where('list','noncache_dir')->get();
    
    DB::delete('delete from noncache where id = ?',[$sucuri_users]);
    // }
  //  return dd($blackpath);

         foreach($users as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl = curl_init();
       $auth_data = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
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
       $result1 = utf8_encode($result);
       $result2 =json_decode($result1);
        // $result=array($result);
         $result;
        // $paths =  Dns::all();
        // return Dns::find($sucuri_users);
        
        return view('admin.dns.noncache',['ok'=>$result ,'id'=>$sucuri_users, 'message'=> $string])->with('whitepath', $whitepath);

    
	// return view('admin.dns.urlpaths', ['ok'=>$result1, 'message'=> $string]);

								 
      // return view('admin.dns.ip', compact('id','ip'));
    
    }

    public function removeblockcookie($sucuri_users,Request $request)
    {
     $id = $request->id;
     $sucuri_users;
 
		$this->validate($request,[
      'remove' => 'required',
			'id' => 'required'
        ]);
       $sucuri_users;
       DB::delete('delete from blockcookie where domain_id = ? and url = ? ',[$sucuri_users, $request->remove]);
                                //$users      = Sucuri::where('id',$sucuri_users)->get();
                                if($id!=1){
                                  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
                                  //dd($users);
                         }else{  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }
                               //  dd($users);
                                // echo "ok"; 
                                 //die();
                                foreach($users as $user){
                                // return "$user->name";
                                $s_key = "$user->s_key";
                                    $a_key= "$user->a_key";
                                } 


                                $curl = curl_init();
                                $auth_data = array(
                                'k' 		=> $a_key,
                                's' 		=> $s_key,
                                'a' 	=> 'update_setting',
                                'remove_block_cookie[]'    =>  $request->remove,
                                // 'format' =>  'json'
                                
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
								 $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {	$index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ",$result['messages']);

//		Next Function
		


// if($id!=1){
  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get(); 
         //dd($users);
// }else{
    $whitepath  = DB::table('blockcookie')->where('domain_id',$sucuri_users)->get();
    
    DB::delete('delete from blockcookie where id = ?',[$sucuri_users]);
    // }
  //  return dd($blackpath);

         foreach($users as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl = curl_init();
       $auth_data = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
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
       $result1 = utf8_encode($result);
       $result2 =json_decode($result1);
        // $result=array($result);
         $result;
        // $paths =  Dns::all();
        // return Dns::find($sucuri_users);
        
        return view('admin.dns.blockcookie',['ok'=>$result ,'id'=>$sucuri_users, 'message'=> $string])->with('whitepath', $whitepath);

    
	// return view('admin.dns.urlpaths', ['ok'=>$result1, 'message'=> $string]);

								 
      // return view('admin.dns.ip', compact('id','ip'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     **/


    public function blackip($id,$ip, Request $request)
    {
      
      return view('admin.dns.blackip', compact('id','ip'));
    
    }
	
	public function removeblack($sucuri_users,Request $request)
    {
		 $id = $request->id;
     $sucuri_users;
		$this->validate($request,[
            'remove' => 'required',
			'id' => 'required'
        ]);
       $sucuri_users;
                                //$users      = Sucuri::where('id',$sucuri_users)->get();
                                if($id!=1){
                                  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
                                  //dd($users);
                         }else{  $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }
                                // dump($users->name); 
                                foreach($users as $user){
                                // return "$user->name";
                                $s_key = "$user->s_key";
                                    $a_key= "$user->a_key";
                                } 


                                $curl = curl_init();
                                $auth_data = array(
                                'k' 		=> $a_key,
                                's' 		=> $s_key,
                                'a' 	=> 'delete_blacklist_ip',
                                'ip'    =>  $request->remove,
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
                                //$result1 =  utf8_encode($result);
                                //$result2 =json_decode($result1);
                                //$result=array($result);
                                 $result;
								 $result = json_decode($result , true);
    $message="";
    $index=0;
    foreach($result as $ok => $data)
    {	$index++;
        if($index == 3){
            foreach ($data as $message) {
                $this->message= $message;
            }
        }
    }

    $string = implode(" ",$result['messages']);

//		Next Function
		

	
if($id!=1){
  $users1  = DB::table('sucuri_user')->where('id',$sucuri_users)->where('user_id',$id)->get();
  //dd($users);
}else{  $users1  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();  }

        //$users1  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();
        // dump($users->name); 
         foreach($users1 as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        
       
       $curl1 = curl_init();
       $auth_data1 = array(
           'k' 		=> $a_key,
           's' 		=> $s_key,
           'a' 	=> 'show_settings',
           'format' =>  'json'
         
       );
       curl_setopt($curl1, CURLOPT_POST, 1);
       curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
       curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
       curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       $result1 = curl_exec($curl1);
       if(!$result1){die("Connection Failure");}
       curl_close($curl1);
      
	return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]);
  // return view('admin.dns.ip', compact('id','ip'));    
    }

   public function addIp(Request $req){

      $users1  = DB::table('sucuri_user')->where('id',$req->id)->get();
        // dump($users->name); 
         foreach($users1 as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        

       
       $curl1 = curl_init();
       $auth_data1 = array(
           'k'    => $a_key,
           's'    => $s_key,
           'a'  => $req->list,
           'ip' =>  $req->ip,
           // 'duration'=> $req->time,
       ); 

       curl_setopt($curl1, CURLOPT_POST, 1);
       curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
       curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
       curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       $result1 = curl_exec($curl1);
       if(!$result1){die("Connection Failure");}
       curl_close($curl1);

       
       $message = json_decode($result1);

     $message =$message->messages[0];
  // $result = json_decode($result1 , true);
  //   $message="";
  //   $index=0;
  //   foreach($result as $ok => $data)
  //   { $index++;
  //       if($index == 3){
  //           foreach ($data as $message) {
  //               $this->message= $message;
  //           }
  //       }
  //   }

  //   $string = implode(" ",$result['messages']);
       
  //      $curl1 = curl_init();
  //      $auth_data1 = array(
  //          'k'    => $a_key,
  //          's'    => $s_key,
  //          'a'  => 'show_settings',
  //          'format' =>  'json'
         
  //      ); 
  //      curl_setopt($curl1, CURLOPT_POST, 1);
  //      curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
  //      curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
  //      curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
  //      curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  //      $result1 = curl_exec($curl1);
  //      if(!$result1){die("Connection Failure");}
  //      curl_close($curl1);

      return back()->with('message',$message);
  // return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]);

        // return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]); 

    }

    public function insertWhiteURL(Request $req){

      
      $users1  = DB::table('sucuri_user')->where('id',$req->id)->get();
        // dump($users->name); 
         foreach($users1 as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
       
       
// return $req->ip;
// die();
       if($req->list == 'whitelist_dir'){
         $bhand = 'whitelist_dir_pattern';
       }else{
        $bhand = 'blacklist_dir_pattern';

       }
       $curl1 = curl_init();
       $auth_data1 = array(
           'k'    => $a_key,
           's'    => $s_key,
           'a'  => 'update_setting',
           $req->list =>  $req->ip,
           $bhand => $req->dir
           // 'duration'=> $req->time,
       ); 

       curl_setopt($curl1, CURLOPT_POST, 1);
       curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
       curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
       curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       $result1 = curl_exec($curl1);
       if(!$result1){die("Connection Failure");}
       curl_close($curl1);

       
       $message = json_decode($result1);

     $message =$message->messages[0];
  // $result = json_decode($result1 , true);
  //   $message="";
  //   $index=0;
  //   foreach($result as $ok => $data)
  //   { $index++;
  //       if($index == 3){
  //           foreach ($data as $message) {
  //               $this->message= $message;
  //           }
  //       }
  //   }

  //   $string = implode(" ",$result['messages']);
       
  //      $curl1 = curl_init();
  //      $auth_data1 = array(
  //          'k'    => $a_key,
  //          's'    => $s_key,
  //          'a'  => 'show_settings',
  //          'format' =>  'json'
         
  //      ); 
  //      curl_setopt($curl1, CURLOPT_POST, 1);
  //      curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
  //      curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
  //      curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
  //      curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  //      $result1 = curl_exec($curl1);
  //      if(!$result1){die("Connection Failure");}
  //      curl_close($curl1);
  if($req->dir == 'begins_with'){
    $req->ip = "^".$req->ip;
  }elseif($req->dir == 'ends_with'){
   $req->ip = $req->ip."$";
  }elseif($req->dir == 'equals'){
   $req->ip = "^".$req->ip."$";
  }
      $data=array('list'=>$req->list, 'url' => $req->ip, 'pattern' =>$req->dir, 'domain_id' => $req->id);
      DB::table('url_paths')->insert($data);
      
      return back()->with('message',$message);
  // return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]);

        // return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]); 
    }

    public function insertNonCache(Request $req){

      
      $users1  = DB::table('sucuri_user')->where('id',$req->id)->get();
        // dump($users->name); 
         foreach($users1 as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        

       
      
         $bhand = 'noncache_dir_pattern';
      //  
       $curl1 = curl_init();
       $auth_data1 = array(
           'k'    => $a_key,
           's'    => $s_key,
           'a'  => 'update_setting',
           $req->list =>  $req->ip,
           $bhand => $req->dir
           // 'duration'=> $req->time,
       ); 
       
      //  return $req_>ip;
       
       curl_setopt($curl1, CURLOPT_POST, 1);
       curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
       curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
       curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       $result1 = curl_exec($curl1);
       if(!$result1){die("Connection Failure");}
       curl_close($curl1);

       
       $message = json_decode($result1);

     $message =$message->messages[0];
  // $result = json_decode($result1 , true);
  //   $message="";
  //   $index=0;
  //   foreach($result as $ok => $data)
  //   { $index++;
  //       if($index == 3){
  //           foreach ($data as $message) {
  //               $this->message= $message;
  //           }
  //       }
  //   }

  //   $string = implode(" ",$result['messages']);
       
  //      $curl1 = curl_init();
  //      $auth_data1 = array(
  //          'k'    => $a_key,
  //          's'    => $s_key,
  //          'a'  => 'show_settings',
  //          'format' =>  'json'
         
  //      ); 
  //      curl_setopt($curl1, CURLOPT_POST, 1);
  //      curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
  //      curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
  //      curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
  //      curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  //      $result1 = curl_exec($curl1);
  //      if(!$result1){die("Connection Failure");}
  //      curl_close($curl1);
      
      if($req->dir == 'begins_with'){
        $req->ip = "^".$req->ip;
      }elseif($req->dir == 'ends_with'){
      $req->ip = $req->ip."$";
      }elseif($req->dir == 'equals'){
      $req->ip = "^".$req->ip."$";
      }

      $data=array('list'=>$req->list, 'url' => $req->ip, 'pattern' =>$req->dir, 'domain_id' => $req->id);
      DB::table('noncache')->insert($data);
      
      return back()->with('message', "Added");
  // return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]);

        // return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]); 
    }

    public function insertBlockCookie(Request $req){

      
      $users1  = DB::table('sucuri_user')->where('id',$req->id)->get();
        // dump($users->name); 
         foreach($users1 as $user){
          // return "$user->name";
           $s_key = "$user->s_key";
            $a_key= "$user->a_key";
       } 
        

       
      
         $bhand = 'noncache_dir_pattern';
      //  
       $curl1 = curl_init();
       $auth_data1 = array(
           'k'    => $a_key,
           's'    => $s_key,
           'a'  => 'update_setting',
           'block_cookie' =>  $req->ip,
           
           // 'duration'=> $req->time,
       ); 
       
      //  return $req_>ip;
       
       curl_setopt($curl1, CURLOPT_POST, 1);
       curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
       curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
       curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       $result1 = curl_exec($curl1);
       if(!$result1){die("Connection Failure");}
       curl_close($curl1);

       
       $message = json_decode($result1);

     $message =$message->messages[0];
  // $result = json_decode($result1 , true);
  //   $message="";
  //   $index=0;
  //   foreach($result as $ok => $data)
  //   { $index++;
  //       if($index == 3){
  //           foreach ($data as $message) {
  //               $this->message= $message;
  //           }
  //       }
  //   }

  //   $string = implode(" ",$result['messages']);
       
  //      $curl1 = curl_init();
  //      $auth_data1 = array(
  //          'k'    => $a_key,
  //          's'    => $s_key,
  //          'a'  => 'show_settings',
  //          'format' =>  'json'
         
  //      ); 
  //      curl_setopt($curl1, CURLOPT_POST, 1);
  //      curl_setopt($curl1, CURLOPT_POSTFIELDS, $auth_data1);
  //      curl_setopt($curl1, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
  //      curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
  //      curl_setopt($curl1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  //      $result1 = curl_exec($curl1);
  //      if(!$result1){die("Connection Failure");}
  //      curl_close($curl1);
      
      

      $data=array('url' => $req->ip, 'domain_id' => $req->id);
      DB::table('blockcookie')->insert($data);
      
      return back()->with('message', 'Added');
  // return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]);

        // return view('admin.dns.show', ['ok'=>$result1, 'message'=> $string]); 
    }
// public function insertIp(){
//   dd("ok ok o k"); 
// } 
 
    public function create()
    {
        //
    }


    public function ipDetails($zone,$minutes,$ipAddress)
    {
        //
  $zone =   Zone::where('name',$zone)->first();
            $current_time=Carbon::now('UTC');


if($zone->internalID=="")
{
    $internalID=0;
}
else{
    $internalID=$zone->internalID;
}
$body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "RayID.keyword",
        "size": 2000,
        "order": {
          "_term": "desc"
        }
      },
      "aggs": {
        "3": {
          "top_hits": {
            "docvalue_fields": [
              "EdgeStartTimestamp"
            ],
            "_source": "EdgeStartTimestamp",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "4": {
          "top_hits": {
            "docvalue_fields": [
              "ClientRequestHost.keyword"
            ],
            "_source": "ClientRequestHost.keyword",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "5": {
          "top_hits": {
            "docvalue_fields": [
              "ClientRequestURI.keyword"
            ],
            "_source": "ClientRequestURI.keyword",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "6": {
          "top_hits": {
            "docvalue_fields": [
              "ClientDeviceType.keyword"
            ],
            "_source": "ClientDeviceType.keyword",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "7": {
          "top_hits": {
            "docvalue_fields": [
              "ClientRequestMethod.keyword"
            ],
            "_source": "ClientRequestMethod.keyword",
            "size": 1,
            "sort": [
              {
                "ClientRequestMethod.keyword": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "8": {
          "top_hits": {
            "docvalue_fields": [
              "ClientRequestProtocol.keyword"
            ],
            "_source": "ClientRequestProtocol.keyword",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "asc"
                }
              }
            ]
          }
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query":'.$internalID.'
            }
          }
        },
        {
          "match_phrase": {
            "ClientIP.keyword": {
               "query":"'.$ipAddress.'"
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
              "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes($minutes)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';
$current_time->addMinutes($minutes);
// dd($body);

$hosts = [
    'http://elasticsearch:ONiNeVB5NRDNo&F9@CgJAi7d@148.251.176.73:9201'       // HTTP Basic Authentication
   
];


// curl -XPUT http://<es node>:9200/.kibana/index-pattern/cloudflare -d '{"title" : "cloudflare",  "timeFieldName": "EdgeStartTimestamp"}'
$client = \Elasticsearch\ClientBuilder::create()
                    ->setHosts($hosts)
                    ->build();

$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];


                    $results = $client->search($params);
                    $deviceType1=$results['aggregations'][2]['buckets'];
                    $deviceType=[];
                     // dd($deviceType1);
                    foreach ($deviceType1 as $key => $value) {
                        # code...
                         $deviceType[$key]['RayID']=$deviceType1[$key]['key'];
                        $deviceType[$key]['EdgeStartTimestamp']=$deviceType1[$key]['3']['hits']['hits'][0]['fields']['EdgeStartTimestamp'][0];
                        $deviceType[$key]['ClientRequestHost']=$deviceType1[$key]['4']['hits']['hits'][0]['fields']['ClientRequestHost.keyword'][0];

                        $deviceType[$key]['ClientRequestURI']=$deviceType1[$key]['5']['hits']['hits'][0]['fields']['ClientRequestURI.keyword'][0];

                        $deviceType[$key]['ClientDeviceType']=$deviceType1[$key]['6']['hits']['hits'][0]['fields']['ClientDeviceType.keyword'][0];

                        $deviceType[$key]['ClientRequestMethod']=$deviceType1[$key]['7']['hits']['hits'][0]['fields']['ClientRequestMethod.keyword'][0];

                        $deviceType[$key]['ClientRequestProtocol']=$deviceType1[$key]['8']['hits']['hits'][0]['fields']['ClientRequestProtocol.keyword'][0];

                    }

return view('admin.analytics.ipDetails', compact('deviceType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
         
  
  
        
  
  /*
          $zone = Zone::create([
              "name"         => $request->name,
              "zone_id"      => $request->name,
              
              "status"       => $request->name,
              "type"         => $request->name,
              "user_id"      => $request->user,
              "cfaccount_id" => $cf->id,
              "package_id"   => $request->package,
  
          ]);
  
          $sucuri_user = new sucuri_user;
          
          $sucuri_user->name = request('name');
          $sucuri_user->s_key = request('s_key');
          $sucuri_user->a_key = request('a_key');
          $sucuri_user->url = request('url');
          $sucuri_user->save();
  */
  
       //   Sucuri::create($request->all());
       //  $request->session()->flash('status', "Domian Name Added Successfully...");
        //  return redirect()->route('admin.zones.index')->with('success', 'Post Updated');
  
  
  
  
      }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Analytics  $analytics
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Analytics  $analytics
     * @return \Illuminate\Http\Response
     */
    public function edit(Analytics $analytics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Analytics  $analytics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Analytics $analytics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Analytics  $analytics
     * @return \Illuminate\Http\Response
     */
    public function destroy(Analytics $analytics)
    {
        //
    }


    public function countries($zone,$minutes) {
        

         $zone=Zone::where('name',$zone)->first();

         $names = json_decode('{"BD": "Bangladesh", "BE": "Belgium", "BF": "Burkina Faso", "BG": "Bulgaria", "BA": "Bosnia and Herzegovina", "BB": "Barbados", "WF": "Wallis and Futuna", "BL": "Saint Barthelemy", "BM": "Bermuda", "BN": "Brunei", "BO": "Bolivia", "BH": "Bahrain", "BI": "Burundi", "BJ": "Benin", "BT": "Bhutan", "JM": "Jamaica", "BV": "Bouvet Island", "BW": "Botswana", "WS": "Samoa", "BQ": "Bonaire, Saint Eustatius and Saba ", "BR": "Brazil", "BS": "Bahamas", "JE": "Jersey", "BY": "Belarus", "BZ": "Belize", "RU": "Russia", "RW": "Rwanda", "RS": "Serbia", "TL": "East Timor", "RE": "Reunion", "TM": "Turkmenistan", "TJ": "Tajikistan", "RO": "Romania", "TK": "Tokelau", "GW": "Guinea-Bissau", "GU": "Guam", "GT": "Guatemala", "GS": "South Georgia and the South Sandwich Islands", "GR": "Greece", "GQ": "Equatorial Guinea", "GP": "Guadeloupe", "JP": "Japan", "GY": "Guyana", "GG": "Guernsey", "GF": "French Guiana", "GE": "Georgia", "GD": "Grenada", "GB": "United Kingdom", "GA": "Gabon", "SV": "El Salvador", "GN": "Guinea", "GM": "Gambia", "GL": "Greenland", "GI": "Gibraltar", "GH": "Ghana", "OM": "Oman", "TN": "Tunisia", "JO": "Jordan", "HR": "Croatia", "HT": "Haiti", "HU": "Hungary", "HK": "Hong Kong", "HN": "Honduras", "HM": "Heard Island and McDonald Islands", "VE": "Venezuela", "PR": "Puerto Rico", "PS": "Palestinian Territory", "PW": "Palau", "PT": "Portugal", "SJ": "Svalbard and Jan Mayen", "PY": "Paraguay", "IQ": "Iraq", "PA": "Panama", "PF": "French Polynesia", "PG": "Papua New Guinea", "PE": "Peru", "PK": "Pakistan", "PH": "Philippines", "PN": "Pitcairn", "PL": "Poland", "PM": "Saint Pierre and Miquelon", "ZM": "Zambia", "EH": "Western Sahara", "EE": "Estonia", "EG": "Egypt", "ZA": "South Africa", "EC": "Ecuador", "IT": "Italy", "VN": "Vietnam", "SB": "Solomon Islands", "ET": "Ethiopia", "SO": "Somalia", "ZW": "Zimbabwe", "SA": "Saudi Arabia", "ES": "Spain", "ER": "Eritrea", "ME": "Montenegro", "MD": "Moldova", "MG": "Madagascar", "MF": "Saint Martin", "MA": "Morocco", "MC": "Monaco", "UZ": "Uzbekistan", "MM": "Myanmar", "ML": "Mali", "MO": "Macao", "MN": "Mongolia", "MH": "Marshall Islands", "MK": "Macedonia", "MU": "Mauritius", "MT": "Malta", "MW": "Malawi", "MV": "Maldives", "MQ": "Martinique", "MP": "Northern Mariana Islands", "MS": "Montserrat", "MR": "Mauritania", "IM": "Isle of Man", "UG": "Uganda", "TZ": "Tanzania", "MY": "Malaysia", "MX": "Mexico", "IL": "Israel", "FR": "France", "IO": "British Indian Ocean Territory", "SH": "Saint Helena", "FI": "Finland", "FJ": "Fiji", "FK": "Falkland Islands", "FM": "Micronesia", "FO": "Faroe Islands", "NI": "Nicaragua", "NL": "Netherlands", "NO": "Norway", "NA": "Namibia", "VU": "Vanuatu", "NC": "New Caledonia", "NE": "Niger", "NF": "Norfolk Island", "NG": "Nigeria", "NZ": "New Zealand", "NP": "Nepal", "NR": "Nauru", "NU": "Niue", "CK": "Cook Islands", "XK": "Kosovo", "CI": "Ivory Coast", "CH": "Switzerland", "CO": "Colombia", "CN": "China", "CM": "Cameroon", "CL": "Chile", "CC": "Cocos Islands", "CA": "Canada", "CG": "Republic of the Congo", "CF": "Central African Republic", "CD": "Democratic Republic of the Congo", "CZ": "Czech Republic", "CY": "Cyprus", "CX": "Christmas Island", "CR": "Costa Rica", "CW": "Curacao", "CV": "Cape Verde", "CU": "Cuba", "SZ": "Swaziland", "SY": "Syria", "SX": "Sint Maarten", "KG": "Kyrgyzstan", "KE": "Kenya", "SS": "South Sudan", "SR": "Suriname", "KI": "Kiribati", "KH": "Cambodia", "KN": "Saint Kitts and Nevis", "KM": "Comoros", "ST": "Sao Tome and Principe", "SK": "Slovakia", "KR": "South Korea", "SI": "Slovenia", "KP": "North Korea", "KW": "Kuwait", "SN": "Senegal", "SM": "San Marino", "SL": "Sierra Leone", "SC": "Seychelles", "KZ": "Kazakhstan", "KY": "Cayman Islands", "SG": "Singapore", "SE": "Sweden", "SD": "Sudan", "DO": "Dominican Republic", "DM": "Dominica", "DJ": "Djibouti", "DK": "Denmark", "VG": "British Virgin Islands", "DE": "Germany", "YE": "Yemen", "DZ": "Algeria", "US": "United States", "UY": "Uruguay", "YT": "Mayotte", "UM": "United States Minor Outlying Islands", "LB": "Lebanon", "LC": "Saint Lucia", "LA": "Laos", "TV": "Tuvalu", "TW": "Taiwan", "TT": "Trinidad and Tobago", "TR": "Turkey", "LK": "Sri Lanka", "LI": "Liechtenstein", "LV": "Latvia", "TO": "Tonga", "LT": "Lithuania", "LU": "Luxembourg", "LR": "Liberia", "LS": "Lesotho", "TH": "Thailand", "TF": "French Southern Territories", "TG": "Togo", "TD": "Chad", "TC": "Turks and Caicos Islands", "LY": "Libya", "VA": "Vatican", "VC": "Saint Vincent and the Grenadines", "AE": "United Arab Emirates", "AD": "Andorra", "AG": "Antigua and Barbuda", "AF": "Afghanistan", "AI": "Anguilla", "VI": "U.S. Virgin Islands", "IS": "Iceland", "IR": "Iran", "AM": "Armenia", "AL": "Albania", "AO": "Angola", "AQ": "Antarctica", "AS": "American Samoa", "AR": "Argentina", "AU": "Australia", "AT": "Austria", "AW": "Aruba", "IN": "India", "AX": "Aland Islands", "AZ": "Azerbaijan", "IE": "Ireland", "ID": "Indonesia", "UA": "Ukraine", "QA": "Qatar", "MZ": "Mozambique", "T1": "TOR"}');

         if($zone->cfaccount_id==0)
         {
            $latest=$zone->wafEvent->sortByDesc('ts')->first();
        if($latest)
        {
            $latest= $latest->ts;

        }
        else
        {
            $latest= Carbon::now()->timestamp;
        }
         // dd($minutes);

        // $latest= Carbon::now()->timestamp;
        $tsEvent = Carbon::createFromTimestamp((int)$latest)->subMinutes($minutes)->timestamp;

        //$tsEvent = Carbon::now('UTC')->subMinutes(14444)->timestamp;
    // echo($latest);

    
         $events = $zone->wafEvent->sortByDesc('ts')->where('ts','>',$tsEvent)->sortBy('ts')->groupBy('country');
       
        $threats=array();
         foreach ($events as $key=>$event) {
             # code...
             
             // dd($event);
            $threats[$key]['name']=$event->first()->country;
            $threats[$key]['code']=array_search($threats[$key]['name'], (array)$names);
            $threats[$key]['value']=$event->count();
            // $threats[$key]['SRE'] = $event->where('scope','SRE')->count();
         }
         $threats=array_values($threats);

         return response()->json($threats);
         die();
         }
        $results=unserialize($zone->analytics->where('minutes',$minutes)->first()->value);


        // $this->load->model('cloudflare_api');
        // $parameters['time_stamp'] = $this->session->userdata('time_stamp');
        // if (empty($parameters['time_stamp']))
        //     $parameters['time_stamp'] = 'last 24 hours';
        // $parameters['since'] = $this->session->userdata('since');
        // if (empty($parameters['since']))
        //     $parameters['since'] = '-1440';
        // $parameters['time_on_xaxis'] = $this->session->userdata('time_on_xaxis');
        // if (empty($parameters['time_on_xaxis']))
        //     $parameters['time_on_xaxis'] = "hour";
        // $results = $this->cloudflare_api->dashboard(array('since' => $parameters['since'] , 'until' => null, 'zone_id' => $user_id, 'email' => $this->session->userdata('user_cloudflare_api_email'), 'auth_key' => $this->session->userdata('user_cloudflare_api_key')));
        // 
        // 


        if (empty ($results)) {
            echo json_encode(false);
            exit;
        }
        

        $threats_country[] = $results->totals->threats->country;
        foreach (json_decode(json_encode($threats_country), true) as $key => $value) {
            $country_index = array_keys($value);
        }

        $country=null;
        foreach ($country_index as $key => $value) {
            $country[$key]['code'] = $value;
            $country[$key]['value'] = $results->totals->threats->country->$value;
            if ($value != "XX"  )
            $country[$key]['name'] = $names->$value;
        }



        if($country==null)
        {
            return response()->make('null');
        }
        return response()->json($country);
    }

    public function traffic($zone,$minutes) {
        

         $zone=Zone::where('name',$zone)->first();

         $country=null;

         if($zone->cfaccount_id==0)
         {
            die();
         }
        $results=unserialize($zone->analytics->where('minutes',$minutes)->first()->value);

        // $user_id = $this->session->userdata['zone_id'];
        // $this->load->model('cloudflare_api');
        // $parameters['time_stamp'] = $this->session->userdata('time_stamp');
        // if (empty($parameters['time_stamp']))
        //     $parameters['time_stamp'] = 'last 24 hours';
        // $parameters['since'] = $this->session->userdata('since');
        // if (empty($parameters['since']))
        //     $parameters['since'] = '-1440';
        // $parameters['time_on_xaxis'] = $this->session->userdata('time_on_xaxis');
        // if (empty($parameters['time_on_xaxis']))
        //     $parameters['time_on_xaxis'] = "hour";
        // $results = $this->cloudflare_api->dashboard(array('since' => $parameters['since'] , 'until' => null, 'zone_id' => $user_id, 'email' => $this->session->userdata('user_cloudflare_api_email'), 'auth_key' => $this->session->userdata('user_cloudflare_api_key')));
        // if (empty ($results)) {
        //     $data['error'] = "<a href=". site_url() . "analytics>Please refresh</a>";
        //     $data['template_file'] = 'analytics';
        //     $this->load->view('master_layout',$data);
        //     exit;
        // }
        $names = json_decode('{"BD": "Bangladesh", "BE": "Belgium", "BF": "Burkina Faso", "BG": "Bulgaria", "BA": "Bosnia and Herzegovina", "BB": "Barbados", "WF": "Wallis and Futuna", "BL": "Saint Barthelemy", "BM": "Bermuda", "BN": "Brunei", "BO": "Bolivia", "BH": "Bahrain", "BI": "Burundi", "BJ": "Benin", "BT": "Bhutan", "JM": "Jamaica", "BV": "Bouvet Island", "BW": "Botswana", "WS": "Samoa", "BQ": "Bonaire, Saint Eustatius and Saba ", "BR": "Brazil", "BS": "Bahamas", "JE": "Jersey", "BY": "Belarus", "BZ": "Belize", "RU": "Russia", "RW": "Rwanda", "RS": "Serbia", "TL": "East Timor", "RE": "Reunion", "TM": "Turkmenistan", "TJ": "Tajikistan", "RO": "Romania", "TK": "Tokelau", "GW": "Guinea-Bissau", "GU": "Guam", "GT": "Guatemala", "GS": "South Georgia and the South Sandwich Islands", "GR": "Greece", "GQ": "Equatorial Guinea", "GP": "Guadeloupe", "JP": "Japan", "GY": "Guyana", "GG": "Guernsey", "GF": "French Guiana", "GE": "Georgia", "GD": "Grenada", "GB": "United Kingdom", "GA": "Gabon", "SV": "El Salvador", "GN": "Guinea", "GM": "Gambia", "GL": "Greenland", "GI": "Gibraltar", "GH": "Ghana", "OM": "Oman", "TN": "Tunisia", "JO": "Jordan", "HR": "Croatia", "HT": "Haiti", "HU": "Hungary", "HK": "Hong Kong", "HN": "Honduras", "HM": "Heard Island and McDonald Islands", "VE": "Venezuela", "PR": "Puerto Rico", "PS": "Palestinian Territory", "PW": "Palau", "PT": "Portugal", "SJ": "Svalbard and Jan Mayen", "PY": "Paraguay", "IQ": "Iraq", "PA": "Panama", "PF": "French Polynesia", "PG": "Papua New Guinea", "PE": "Peru", "PK": "Pakistan", "PH": "Philippines", "PN": "Pitcairn", "PL": "Poland", "PM": "Saint Pierre and Miquelon", "ZM": "Zambia", "EH": "Western Sahara", "EE": "Estonia", "EG": "Egypt", "ZA": "South Africa", "EC": "Ecuador", "IT": "Italy", "VN": "Vietnam", "SB": "Solomon Islands", "ET": "Ethiopia", "SO": "Somalia", "ZW": "Zimbabwe", "SA": "Saudi Arabia", "ES": "Spain", "ER": "Eritrea", "ME": "Montenegro", "MD": "Moldova", "MG": "Madagascar", "MF": "Saint Martin", "MA": "Morocco", "MC": "Monaco", "UZ": "Uzbekistan", "MM": "Myanmar", "ML": "Mali", "MO": "Macao", "MN": "Mongolia", "MH": "Marshall Islands", "MK": "Macedonia", "MU": "Mauritius", "MT": "Malta", "MW": "Malawi", "MV": "Maldives", "MQ": "Martinique", "MP": "Northern Mariana Islands", "MS": "Montserrat", "MR": "Mauritania", "IM": "Isle of Man", "UG": "Uganda", "TZ": "Tanzania", "MY": "Malaysia", "MX": "Mexico", "IL": "Israel", "FR": "France", "IO": "British Indian Ocean Territory", "SH": "Saint Helena", "FI": "Finland", "FJ": "Fiji", "FK": "Falkland Islands", "FM": "Micronesia", "FO": "Faroe Islands", "NI": "Nicaragua", "NL": "Netherlands", "NO": "Norway", "NA": "Namibia", "VU": "Vanuatu", "NC": "New Caledonia", "NE": "Niger", "NF": "Norfolk Island", "NG": "Nigeria", "NZ": "New Zealand", "NP": "Nepal", "NR": "Nauru", "NU": "Niue", "CK": "Cook Islands", "XK": "Kosovo", "CI": "Ivory Coast", "CH": "Switzerland", "CO": "Colombia", "CN": "China", "CM": "Cameroon", "CL": "Chile", "CC": "Cocos Islands", "CA": "Canada", "CG": "Republic of the Congo", "CF": "Central African Republic", "CD": "Democratic Republic of the Congo", "CZ": "Czech Republic", "CY": "Cyprus", "CX": "Christmas Island", "CR": "Costa Rica", "CW": "Curacao", "CV": "Cape Verde", "CU": "Cuba", "SZ": "Swaziland", "SY": "Syria", "SX": "Sint Maarten", "KG": "Kyrgyzstan", "KE": "Kenya", "SS": "South Sudan", "SR": "Suriname", "KI": "Kiribati", "KH": "Cambodia", "KN": "Saint Kitts and Nevis", "KM": "Comoros", "ST": "Sao Tome and Principe", "SK": "Slovakia", "KR": "South Korea", "SI": "Slovenia", "KP": "North Korea", "KW": "Kuwait", "SN": "Senegal", "SM": "San Marino", "SL": "Sierra Leone", "SC": "Seychelles", "KZ": "Kazakhstan", "KY": "Cayman Islands", "SG": "Singapore", "SE": "Sweden", "SD": "Sudan", "DO": "Dominican Republic", "DM": "Dominica", "DJ": "Djibouti", "DK": "Denmark", "VG": "British Virgin Islands", "DE": "Germany", "YE": "Yemen", "DZ": "Algeria", "US": "United States", "UY": "Uruguay", "YT": "Mayotte", "UM": "United States Minor Outlying Islands", "LB": "Lebanon", "LC": "Saint Lucia", "LA": "Laos", "TV": "Tuvalu", "TW": "Taiwan", "TT": "Trinidad and Tobago", "TR": "Turkey", "LK": "Sri Lanka", "LI": "Liechtenstein", "LV": "Latvia", "TO": "Tonga", "LT": "Lithuania", "LU": "Luxembourg", "LR": "Liberia", "LS": "Lesotho", "TH": "Thailand", "TF": "French Southern Territories", "TG": "Togo", "TD": "Chad", "TC": "Turks and Caicos Islands", "LY": "Libya", "VA": "Vatican", "VC": "Saint Vincent and the Grenadines", "AE": "United Arab Emirates", "AD": "Andorra", "AG": "Antigua and Barbuda", "AF": "Afghanistan", "AI": "Anguilla", "VI": "U.S. Virgin Islands", "IS": "Iceland", "IR": "Iran", "AM": "Armenia", "AL": "Albania", "AO": "Angola", "AQ": "Antarctica", "AS": "American Samoa", "AR": "Argentina", "AU": "Australia", "AT": "Austria", "AW": "Aruba", "IN": "India", "AX": "Aland Islands", "AZ": "Azerbaijan", "IE": "Ireland", "ID": "Indonesia", "UA": "Ukraine", "QA": "Qatar", "MZ": "Mozambique", "T1": null}');
        $traffic_countries[] = $results->totals->requests->country;
        foreach (json_decode(json_encode($traffic_countries), true) as $key => $value) {
            $country_index = array_keys($value);
        }


        foreach ($country_index as $key => $value) {
            
            $country[$key]['code'] = $value;
            $country[$key]['value'] = $results->totals->requests->country->$value;
            if ($value != "XX"  )
                $country[$key]['name'] = $names->$value;
        
        }

        if($country==null)
        {
            return response()->make('null');
        }

        return response()->json($country);
    }

    
}
