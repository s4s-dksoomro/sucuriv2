<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\UaRule;

use \Cloudflare\API\Configurations\UARules as Conf;


class UpdateUaRule implements ShouldQueue
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
        $UARules   = new \Cloudflare\API\Endpoints\UARules($adapter);


        
      $UaRule=UaRule::where('id',$this->ruleID)->first();





      if($UaRule->record_id=="PENDING")
      {
        

        $conf=new Conf();
         $conf->addUA($UaRule->value);

        $mode=$UaRule->mode;
        $paused=$UaRule->paused;
        $description=$UaRule->description;

        $UaRule->delete();
        $UARules->createRule($this->zone->zone_id, $mode, $conf,$paused,$description);
        // dd($dns);
        // $FirewallRule->delete();
        // FetchDns::dispatch($this->zone);
    
      }
      else
      {

         $conf=new Conf();
         $conf->addUA($UaRule->value);
        $UARules->updateRule($this->zone->zone_id, $UaRule->record_id,$UaRule->mode,$conf,$UaRule->description,(bool)$UaRule->paused);
      }
      


      


       


        
    }
}
