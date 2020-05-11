<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\wafPackage;

use App\Jobs\FetchWAFGroups;
use App\Jobs\FetchWAFRules;


class FetchWAFPackages implements ShouldQueue
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
        $WAF   = new \Cloudflare\API\Endpoints\WAF($adapter);



       $packages=$WAF->getPackages($this->zone->zone_id)->result;


        foreach ($packages as $package) {
    $package=json_decode(json_encode($package),true);
    //dd($package);
    $check['zone_id'] = $this->zone->id;
    $check['record_id']    = $package['id'];
    // if(isset($package["senstivity"]))
    // {
    //     $package["sensitivity"]=$package["senstivity"];
    // }
    
    array_forget($package,["id","zone_id"]);
    //dd($record);
            
       

           
         $package=wafPackage::updateOrCreate($check, $package);
           
        FetchWAFGroups::dispatch($this->zone,$package->record_id,$this->user_id)->onConnection('sync'); 
           

}
 foreach ($packages as $package) {
     $package=json_decode(json_encode($package),true);

     
  FetchWAFRules::dispatch($this->zone,$package['id'],$this->user_id)->onConnection('sync');
}
    }
}
