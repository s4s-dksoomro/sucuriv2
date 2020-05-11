<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\FirewallRule;
//use App\ZoneSetting;


class FetchFirewallRules implements ShouldQueue
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

        $key     = new \Cloudflare\API\Auth\APIKey($this->zone->cfaccount->email, $this->zone->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);



      $rules=$zones->getZoneAccessRules($this->zone->zone_id);
     // $rules=$zones->zoneSetting($this->zone->zone_id);


        // dd($rules);

        //dd($records);
		
		
            foreach ($rules as $rule) {

    $rule=json_decode(json_encode($rule),true);
    if($rule['scope']['type']=="zone")
    {
    $check['zone_id'] = $this->zone->id;
    $check['record_id']    = $rule['id'];

    $rule['value']=$rule['configuration']['value'];
    $rule['target']=$rule['configuration']['target'];
    $rule['scope']=$rule['scope']['type'];
    if($rule['paused'])
    {
            $rule['status']="paused";
    }
    else
    {
        $rule['status']="active";
    }
    
    array_forget($rule,["allowed_modes","created_on","modified_on","configuration","paused"]);
 
            
      

           
         FirewallRule::updateOrCreate($check, $rule);
        }   
            

}


    }
}
