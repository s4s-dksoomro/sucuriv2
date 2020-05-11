<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;
use Cloudflare\API\Configurations\PageRulesActions;
use Cloudflare\API\Configurations\PageRulesTargets;


class UpdatePageRule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$record_id,$fetch;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $record_id,$fetch=true)
    {
        //
        $this->zone=$zone;
         $this->fetch=$fetch;
        $this->record_id=$record_id;
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
        $PageRules   = new \Cloudflare\API\Endpoints\PageRules($adapter);


        
      $pagerule=Zone::where('id',$this->zone->id)->first()->pagerule->where('id',$this->record_id)->first();

     


      $PageRulesTargets = new PageRulesTargets($pagerule->value);
        $PageRulesActions = new PageRulesActions();

        foreach($pagerule->PageRuleAction as $action)
        {

          if($action->action=="browser_cache_ttl")
          {
             $PageRulesActions->setBrowserCacheTTL($action->value);
          }
          elseif($action->action=="edge_cache_ttl")
          {
             $PageRulesActions->setEdgeCacheTTL($action->value);
          }
          elseif($action->action=="forwarding_url")
          {

            $val=explode(',SPLIT,', $action->value);
           
             $PageRulesActions->setForwardingURL((int)$val[0],$val[1]);
          }

          
          else
          {
            $PageRulesActions->addConfigurationOption($action->action, [
            'value' => $action->value ]);
          }
          
        }
      


      if($pagerule->record_id=="PENDING")
      {
        

        
        
       
        $PageRules->createPageRule($this->zone->zone_id, $PageRulesTargets,$PageRulesActions);
        
        $pagerule->delete();
        
    
      }
      else
      {

        if($pagerule->status=="active")
        {
          $status=true;
        }
        else
        {
          $status=false;
        }

        $res=$PageRules->updatePageRule($this->zone->zone_id,$pagerule->record_id,$PageRulesTargets,$PageRulesActions,$status,$pagerule->priority);

        

        //$DNS->updateRecordDetails($this->zone->zone_id, $dns->record_id,$dnsArray);
      }
      
      
      if($this->fetch)
      {
        FetchPageRules::dispatch($this->zone);  
      }
      
       


        
    }
}
