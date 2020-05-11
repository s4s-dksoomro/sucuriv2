<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\wafPackage;
use App\wafRule;


class UpdateWAFRule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$ruleId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $ruleId)
    {
        //
        $this->zone=$zone;
        $this->ruleId=$ruleId;
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



      $wafRule=wafRule::where('id',$this->ruleId)->first();



        $WAF->updateRule(
        $this->zone->zone_id,
        $wafRule->wafGroup->wafPackage->record_id,
        $wafRule->record_id,
        $wafRule->mode
        );


        
    }
}
