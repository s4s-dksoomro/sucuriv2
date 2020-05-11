<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;


class UpdateSetting implements ShouldQueue
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

        $key     = new \Cloudflare\API\Auth\APIKey($this->zone->cfaccount->email, $this->zone->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);


        
      $setting=Zone::where('id',$this->zone->id)->first()->ZoneSetting->where('id',$this->settingID)->first();

      $name= $setting->name;
      $value= $setting->value;
      if(str_contains($setting->name,"security_header_strict_transport_security"))
      {
           $settings=ZoneSetting::where('name','LIKE','%security_header_%')->where('zone_id',$this->zone->id);
        
        if($settings->count()>0)
        {
            foreach($settings->get() as $setting1)
            {   
                  $name=str_replace("security_header_strict_transport_security_","",$setting1->name);
                 

                 if($name=="max_age")
                 {
             

                  $setting2[$name]=$setting1->value;
                    
                 }


                 else
                 {

                  if($setting1->value=="1")
                    {

                       $setting2[$name]=true;
                    }
                    else
                    {
                       $setting2[$name]=false;
                    }

                 }
                    
            }

             $value=array();
             $name="security_header";
              $value=["strict_transport_security" => $setting2];

              // var_dump($value);
            }
                 
        }


      if(str_contains($setting->name,"minify"))
      {
           $settings=ZoneSetting::where('name','LIKE','%minify%')->where('zone_id',$this->zone->id);
        
        if($settings->count()>0)
        {
            foreach($settings->get() as $setting1)
            {   
                  $name=str_replace("minify_","",$setting1->name);
                 

              

                  $setting2[$name]=$setting1->value;
                    
                
            }

            // var_dump($setting2);

              $name="minify";
              $value=$setting2;
            
            }
                 
        }

      if(str_contains($setting->name,"browser_cache_ttl"))
      {
          $value= intval($value);

      }
      var_dump($value);
      $res=$zones->updateZoneSetting($this->zone->zone_id, $name,$value);
      var_dump($res);


       


        
    }
}
