<?php

namespace App\Jobs\stackPath;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;
use App\SpCondition;
use App\SpRule;


class FetchWAFRules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$delete;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone,$delete=false,$uid=false)
    {
        //
        $this->zone=$zone;
        $this->delete=$delete;
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

$wafRules=$stackPath->get('/sites/'.$this->zone->zone_id.'/waf/rules')->data->rules; 


if($wafRules AND $this->delete)
     {
        foreach($this->zone->sprule as $sprule)
        { 

              foreach($sprule->spcondition as $SpCondition)
              {
                  
                      $SpCondition->delete();
                 
              }
            
                $sprule->delete();
           
        }

        //die();
     }

//$wafRules=[];
foreach ($wafRules as $rule) {
  # code...
  $check=[];
$rule_insert=$rule=json_decode(json_encode($rule),true);
  

    $check['zone_id'] = $this->zone->id;
    $check['record_id']    = $rule['id'];


  array_forget($rule_insert,["deployed","id","conditions","user_defined","domain","deleted"]);

  $rule_insert=SpRule::updateOrCreate($check, $rule_insert);
  //dd($rule_insert);
  SpCondition::where('sprule_id',$rule_insert->id)->delete();
  foreach ($rule['conditions'] as $condition) {
        
        $condition['sprule_id']=$rule_insert->id;

        $inserted=SpCondition::create($condition);

       // dd($inserted);
   
    }

}

    }
}
