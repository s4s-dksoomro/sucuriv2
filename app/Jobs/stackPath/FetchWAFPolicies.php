<?php

namespace App\Jobs\stackPath;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;
use App\wafGroup;
use App\wafPackage;


class FetchWAFPolicies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone)
    {
        //
        $this->zone=$zone;
        $this->user_id=auth()->user()->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $stackPath= new \MaxCDN($this->zone->spaccount->alias,$this->zone->spaccount->key,$this->zone->spaccount->secret);

$wafPolicies=$stackPath->get('/sites/'.$this->zone->zone_id.'/waf/policies')->data->policy_groups; 

//$wafRules=[];
   foreach ($wafPolicies as $package) {
    $check=[];
$package_insert=$package=json_decode(json_encode($package),true);
  

    $check['zone_id'] = $this->zone->id;
    $check['record_id']    = $this->zone->zone_id."_".$package['name'];

    $package_insert['detection_mode']="";
    array_forget($package_insert,["long_description","policies"]);
   // dd($package_insert);
            
       

           
    $package_insert=wafPackage::updateOrCreate($check, $package_insert);

    foreach ($package['policies'] as $group) {
      # code...
      //dd($group);
    //$group=json_decode(json_encode($group),true);
    $check=[];
    $rec=$this->zone->zone_id."_".$package['name'];
    $check['package_id']=wafPackage::where('record_id',$rec)->where('zone_id',$this->zone->id)->first()->id;
    //dd($check);
    $check['name']    = $group['name'];
     
     if($group['mode'])
     {
        $group['mode']= 'on';
     }
     else
     {
      $group['mode']= 'off';
     }
    

    $group['record_id']=$group['id'];
    array_forget($group,["id","group","name","rule_set_id"]);

    wafGroup::updateOrCreate($check, $group);
    //dd($group);

    }
    
   // dd($group);
            
       

           
       //  
           
            

}

    }
}
