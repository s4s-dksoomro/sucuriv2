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


class UpdateWAFPackage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$groupId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $groupId)
    {
        //
        $this->zone=$zone;
        $this->groupId=$groupId;
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



      $wafPackage=wafPackage::where('id',$this->groupId)->first();



        $WAF->updatePackage(
        $this->zone->zone_id,
        $wafPackage->record_id,
        $wafPackage->sensitivity,
        $wafPackage->action_mode
        );


        
    }
}
