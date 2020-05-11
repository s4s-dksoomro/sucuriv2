<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\wafGroup;



class UpdateSPWAF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     public $user_id, $zone,$groupID;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $groupID)
    {
        //
        $this->zone=$zone;
        $this->groupID=$groupID;
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

      $stackPath= new \MaxCDN($this->zone->spaccount->alias,$this->zone->spaccount->key,$this->zone->spaccount->secret);




        
      $wafPackage=wafGroup::where('id',$this->groupID)->first();

      if($wafPackage->mode=="on")
      {
        $mode= true;
      }
      else
      {
        $mode = false;
      }
      $data= ['policies' => json_encode([$wafPackage->record_id => $mode])];
      //var_dump(json_encode($update));
      //$data[$setting->name]=$setting->value;
      
      
      $res=$stackPath->put('/sites/'.$this->zone->zone_id.'/waf/policies',$data);
       var_dump($res);
     
      


       


        
    }
}
