<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\wafPackage;
use App\PageRuleAction;
use App\PageRule;


class FetchPageRules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$delete;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone,$delete=false)
    {
        //
        $this->zone=$zone;
        $this->delete=$delete;
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



    



     $rules=$PageRules->listPageRules($this->zone->zone_id,'active','priority','desc','all');

     if($rules AND $this->delete)
     {
        foreach($this->zone->pageRule as $pagerule)
        {
            foreach($pagerule->pageruleaction as $pageruleaction)
            {
                $pageruleaction->delete();
            }

            $pagerule->delete();
        }

        //die();
     }

  foreach ($rules as $rule) {

    
    $check['record_id']=$rule->id;
    $check['zone_id']=$this->zone->id;


    $ruleInsert['value']=$rule->targets[0]->constraint->value;
    $ruleInsert['priority']= $rule->priority;
    $ruleInsert['status']= $rule->status;

   
         $pageRule=PageRule::updateOrCreate($check, $ruleInsert);
           
           foreach ($rule->actions as $action) {
               # code...
               
            $checkAction['pagerule_id']=$pageRule->id;
            $checkAction['action']=$action->id;
            if($checkAction['action']=="forwarding_url")
            {
                 $actionInsert['value'] = $action->value->status_code.",SPLIT,".$action->value->url;
            }
            elseif(isset($action->value))
            {
                $actionInsert['value'] = $action->value;
            }
            else
            {
                $actionInsert['value'] = NULL;
            }
           
            $pageRuleAction=PageRuleAction::updateOrCreate($checkAction, $actionInsert);
           }
            

}



 $rules=$PageRules->listPageRules($this->zone->zone_id,'disabled','priority','desc','all');



  foreach ($rules as $rule) {

    
    $check['record_id']=$rule->id;
    $check['zone_id']=$this->zone->id;


    $ruleInsert['value']=$rule->targets[0]->constraint->value;
    $ruleInsert['priority']= $rule->priority;
    $ruleInsert['status']= $rule->status;

   
         $pageRule=PageRule::updateOrCreate($check, $ruleInsert);
           
           foreach ($rule->actions as $action) {
               # code...
               
            $checkAction['pagerule_id']=$pageRule->id;
            $checkAction['action']=$action->id;

            if($checkAction['action']=="forwarding_url")
            {
                 $actionInsert['value'] = $action->value->status_code.",SPLIT,".$action->value->url;
            }
            elseif(isset($action->value))
            {
                $actionInsert['value'] = $action->value;
            }
            else
            {
                $actionInsert['value'] = NULL;
            }
           
            $pageRuleAction=PageRuleAction::updateOrCreate($checkAction, $actionInsert);
           }
            

}


return true;
        
    }
}
