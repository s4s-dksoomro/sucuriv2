<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\wafPackage;
use App\wafGroup;


class FetchWAFGroups implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$package;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, string $package,$user_id)
    {
        //
        $this->zone=$zone;
        $this->package=$package;
        if($user_id)
        {
            $this->user_id=$user_id;
        }
        else
        {
            $this->user_id=auth()->user()->id;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $key     = new \Cloudflare\API\Auth\APIKey($this->zone->cfaccount->email, $this->zone->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $WAF   = new \Cloudflare\API\Endpoints\WAF($adapter);



      $groupsObj=$WAF->getGroups($this->zone->zone_id,$this->package);


     
     $groups=$groupsObj->result;
$total_pages=$groupsObj->result_info->total_pages;
       $page=1;
        if($total_pages>$page)
        {
            while($total_pages>$page)
            {
                $page++;
                    $groupsObj=$WAF->getGroups($this->zone->zone_id,$this->package,$page);
                    $groups=array_merge($groups,$groupsObj->result);

            }
        
        }
        
    foreach ($groups as $group) {


    $group=json_decode(json_encode($group),true);
    
    $check['package_id']=wafPackage::where('record_id',$group['package_id'])->where('zone_id',$this->zone->id)->first()->id;
    
    $check['record_id']    = $group['id'];
    
    array_forget($group,["id","zone_id","package_id"]);
    //dd($check);
            
       

           
         wafGroup::updateOrCreate($check, $group);
           
        
        // FetchWAFRules::dispatch($this->zone,$this->package,$this->user_id)->onConnection('sync'); 

}


        
    }
}
