<?php

namespace App\Jobs\stackPath;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;

use App\Jobs\stackPath\FetchWAFRules;



class UpdateWAFRule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$record_id,$fetch;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $record_id,$fetch=true,$uid=false)
    {
        //
        $this->zone=$zone;
         $this->fetch=$fetch;
        $this->record_id=$record_id;

        if($uid!=false)
        {
          $this->user_id=$uid;
        }
        else
        {


        $this->user_id=auth()->user()->id;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

       $stackPath= new \MaxCDN($this->zone->spaccount->alias,$this->zone->spaccount->key,$this->zone->spaccount->secret);


        
      $sprule=Zone::where('id',$this->zone->id)->first()->sprule->where('id',$this->record_id)->first();

     


        $data=array();

    

        foreach($sprule->spcondition as $condition)
        {

            $arr=['scope' => $condition->scope, 'data' => $condition->data];

            if($condition->scope == "Url" OR $condition->scope == "UserAgent" OR $condition->scope == "Header")
            {

              if($condition->function!=NULL)
              {
                $arr['function']=$condition->function;
              }
              else
              {
                $arr['function'] = "Contains";
              }
              
            }
           array_push($data, $arr);
          
        }
      


      if($sprule->record_id=="PENDING")
      {
        

        
        $data= ['name' => $sprule->name,
        'action' => $sprule->action,
        'active' => $sprule->active,
        'conditions' => json_encode($data)];
      

        
        

        $wafRule=$stackPath->post('/sites/'.$this->zone->zone_id.'/waf/rules',$data); 

        // var_dump($wafRule);
       
        // $PageRules->createPageRule($this->zone->zone_id, $PageRulesTargets,$PageRulesActions);
        
         //$sprule->delete();

        FetchWAFRules::dispatch($this->zone,true,$this->user_id);
        
    
      }
      else
      {

        // if($pagerule->status=="active")
        // {
        //   $status=true;
        // }
        // else
        // {
        //   $status=false;
        // }

        $data= ['name' => $sprule->name,
        'action' => $sprule->action,
        'active' => $sprule->active,
        'conditions' => json_encode($data)];
        //[$wafPackage->record_id => $mode]

        //dd($data);
        $wafRule=$stackPath->put('/sites/'.$this->zone->zone_id.'/waf/rules/'.$sprule->record_id,$data); 

        // var_dump($wafRule);
        

        //$DNS->updateRecordDetails($this->zone->zone_id, $dns->record_id,$dnsArray);
      }
      
      
      // if($this->fetch)
      // {
      //   FetchPageRules::dispatch($this->zone);  
      // }
      
       


        
    }
}
