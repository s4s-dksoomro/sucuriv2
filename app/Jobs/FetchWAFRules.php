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
use App\wafRule;


class FetchWAFRules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$package;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, string $package,$user_id=false)
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



    



     $rulesObj=$WAF->getRules($this->zone->zone_id,$this->package);
     $rules=$rulesObj->result;
$total_pages=$rulesObj->result_info->total_pages;
       $page=1;
        if($total_pages>$page)
        {
            while($total_pages>$page)
            {
                $page++;
                    $rulesObj=$WAF->getRules($this->zone->zone_id,$this->package,$page);
                    $rules=array_merge($rules,$rulesObj->result);

            }
        
        }

//$rules=array();

  foreach ($rules as $rule) {

    
   
    $rule=json_decode(json_encode($rule),true);
    // dd($rule);
    // if(!isset($rule['package_id']) AND isset($rule['group']['id']))
    // {
    //     dd($rule);
    //     die();
    // }
    
    $group=$this->zone->wafPackage->where('record_id',$rule['package_id'])->first()->wafGroup->where('record_id',$rule['group']['id'])->first();
    
    if(!isset($group->id))
    {
        // dd($rule);
    }
    else
    {
        $check['group_id']=$group->id;
    
    
        // $check['zone_id']=$this->zone->id;
// dd($check['group_id']);
   
    $check['record_id']    = $rule['id'];
    $rule['allowed_modes']=serialize($rule['allowed_modes']);
    array_forget($rule,["id","zone_id","package_id","group",]);
   
            
       
   
         wafRule::updateOrCreate($check, $rule);
           
          }  

}


return true;
        
    }
}
