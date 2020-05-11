<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;


class UpdateSpSetting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$settingID;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $settingID)
    {
        //
        $this->zone=$zone;
        $this->settingID=$settingID;
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




        
      $setting=Zone::where('id',$this->zone->id)->first()->ZoneSetting->where('id',$this->settingID)->first();

      
      
      if($setting->name=="zoneshield")
      {     

            if($setting->value=="0")
            {

                $stackPath->delete('/sites/'.$this->zone->zone_id."/zoneshields");
            }
            else
            {

                $data['location']=$setting->value;

                $ret=$stackPath->put('/sites/'.$this->zone->zone_id."/zoneshields",$data);
                $ret=json_decode($ret);
                if($ret->code=="401")
                {
                  $ret=$stackPath->post('/sites/'.$this->zone->zone_id."/zoneshields",$data);
                }
                var_dump($ret);

            }
            
      }
      elseif($setting->name=="waf")
      {     

            if($setting->value=="disabled")
            {

                $stackPath->delete('/sites/'.$this->zone->zone_id."/waf");
            }
            else
            {

               // $data['location']=$setting->value;

                $stackPath->post('/sites/'.$this->zone->zone_id."/waf");

            }
            
      }
      else
      {
        $data[$setting->name]=$setting->value;
         $res=$stackPath->put('/sites/'.$this->zone->zone_id,$data);
      var_dump($res);
      }
     
     
      


       


        
    }
}
