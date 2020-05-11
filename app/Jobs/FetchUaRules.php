<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\UaRule;


class FetchUaRules implements ShouldQueue
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
        $UARules   = new \Cloudflare\API\Endpoints\UARules($adapter);



       $rules=$UARules->listRules($this->zone->zone_id,1,100);


         // dd($rules);

        //
       // $rules=json_decode(json_encode($rules),true);
       // dd($rules);
            foreach ($rules->result as $rule) {
    $rule=json_decode(json_encode($rule),true);
   // dd($rule);
    $check['zone_id'] = $this->zone->id;
    $check['record_id']    = $rule['id'];

    $rule['value']=$rule['configuration']['value'];
    $rule['target']=$rule['configuration']['target'];
    $rule['mode']=$rule['mode'];
    $rule['paused']=$rule['paused'];
    $rule['description']=$rule['description'];

    
    array_forget($rule,["id","configuration"]);

    // dd($rule);
    //dd($rule);
            
      

           
         UaRule::updateOrCreate($check, $rule);
          
            

}
    }
}
