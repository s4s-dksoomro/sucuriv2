<?php

namespace App\Http\Controllers\API;
use App\Dns;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\UpdateDnsRecord;
use App\Jobs\DeleteDNS;
use App\Jobs\FetchDns;
use Validator;

class DnsController extends Controller
{
    protected $request;
    protected $Dns;
    public function __construct(Request $request, Dns $dns) {
        $this->request = $request;
        $this->Dns = $dns;
    }
	
 	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index($zone=Null)
    {
    	$zone=Zone::where('name',$zone)->first();

		$records = Dns::where('zone_id', $zone->id)->get(['zone_id','type','name','content','proxiable','proxied','ttl']);

    //     if(!(auth()->user()->id == $zone->user->id OR \App\User::find(auth()->user()->id)->owner == $zone->user->id OR auth()->user()->id == 1))
    // {
            return response()->json(['data' => $records,
            'status' => 200]);
    // }
   
    }
    


   public function createDNS(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'name' => 'required',
            'content' => 'required',
            'ttl' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }


        $zone_id=$request->input('zid');

        $zone= Zone::find($zone_id);
        /*if(!(auth()->user()->id == $zone->user->id OR auth()->user()->id == $zone->cfaccount->reseller->id OR auth()->user()->id == 1))
    {
            return abort(401);
    }*/

        $type=$request->input('type');
        $name=$request->input('name');
        $content=$request->input('content');

        $ttl=$request->input('ttl');
        // echo($ttl);
        // die();

        // $existingRecord=$zone->Dns->where("name",$name)->first();
        // if($existingRecord)
        // {
        //     echo "Record for this hostname already exists";
        // }
        $data=[
            'record_ID'  =>  'PENDING',
            'type'  =>  $type,
            'name'  =>  $name,
            'content'   =>  $content,
            'locked'    => 0,
            'ttl' => $ttl,
            'zone_id'   => $zone_id,
        ];

        if($type=="A")
        {


            if($this->reserved_ip($content))
            {
                $data['proxiable']=0;
                $data['proxied']=0;
            }
            else
            {
                $data['proxiable']=1;
                $data['proxied']=1;
            }
            
        }
        else
        {
            $data['proxiable']=0;
            $data['proxied']=0;
        }
        $record=DNS::create($data);

        return response()->json(['status' => 201]);
    }

 public function reserved_ip($ip)
{
    $reserved_ips = array( // not an exhaustive list
    '167772160'  => 184549375,  /*    10.0.0.0 -  10.255.255.255 */
    '3232235520' => 3232301055, /* 192.168.0.0 - 192.168.255.255 */
    '2130706432' => 2147483647, /*   127.0.0.0 - 127.255.255.255 */
    '2851995648' => 2852061183, /* 169.254.0.0 - 169.254.255.255 */
    '2886729728' => 2887778303, /*  172.16.0.0 -  172.31.255.255 */
    '3758096384' => 4026531839, /*   224.0.0.0 - 239.255.255.255 */
    );

    $ip_long = sprintf('%u', ip2long($ip));

    foreach ($reserved_ips as $ip_start => $ip_end)
    {
        if (($ip_long >= $ip_start) && ($ip_long <= $ip_end))
        {
            return TRUE;
        }
    }
    return FALSE;
}
    public function store(Request $request)
    {
       
    }

   
    public function show(Package $package)
    {
        //
    }

   
    public function edit($id)
    {
       

    }

  
    public function update(Request $request,$id)
    {
        
       
    }

   
   public function destroy($id)
    {
        //
        $dns=Dns::find($id);
         $dns->delete();
         return response()->json(['status' => 200]);

    }

}
