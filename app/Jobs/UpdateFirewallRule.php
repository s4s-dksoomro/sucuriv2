<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\FirewallRule;


class UpdateFirewallRule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$ruleID;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $ruleID)
    {
        //
        $this->zone=$zone;
        $this->ruleID=$ruleID;
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


        
      $FirewallRule=FirewallRule::where('id',$this->ruleID)->first();

      if($FirewallRule->notes==NULL)
      {
        $notes="";
      }
      else
      {
        $notes=$FirewallRule->notes;
      }



      if($FirewallRule->record_id=="PENDING")
      {
        

        $target=$FirewallRule->target;
        $value=$FirewallRule->value;
        $mode=$FirewallRule->mode;

        $FirewallRule->delete();
        $zones->addZoneAccessRule($this->zone->zone_id, $target, $value,$mode,$notes);
        // dd($dns);
        // $FirewallRule->delete();
        // FetchDns::dispatch($this->zone);
    
      }
      else
      {
        $zones->updateZoneAccessRule($this->zone->zone_id, $FirewallRule->record_id,$FirewallRule->mode,$notes);
      }
      


      


       


        
    }
}
