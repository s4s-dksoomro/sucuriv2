<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;


class DeleteFirewallRule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$rule_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, string $rule_id)
    {
        //
        $this->zone=$zone;
        $this->rule_id=$rule_id;
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
        $Zones   = new \Cloudflare\API\Endpoints\Zones($adapter);


        
      
      $Zones->deleteZoneAccessRule($this->zone->zone_id, $this->rule_id);


       


        
    }
}
