<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;


class FetchSPZoneSetting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone)
    {
        //
        $this->zone=$zone;
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


        $settings = $stackPath->get('/sites/'.$this->zone->zone_id)->data->pullzone;

        //dd($settings);

        foreach ($settings as $key => $value) {
            $check['zone_id'] = $this->zone->id;
            $check['name']    = $key;
            //$values['modified_on'] =$setting->modified_on;
           	 $values['editable'] = 1;
            if (!is_object($value)) {
                $values['value'] = $value;
                ZoneSetting::updateOrCreate($check, $values);
            } 

            

        }




        $settings = $stackPath->get('/sites/'.$this->zone->zone_id."/zoneshields")->data->zoneshields;

        //dd($settings);
        if(count($settings)>0)
        {
        foreach ($settings as $zoneshield) {
                

                $check['zone_id'] =  $this->zone->id;
            $check['name']    = "zoneshield";
            //$values['modified_on'] =$setting->modified_on;
             $values['editable'] = 1;
            
                $values['value'] = $zoneshield->reporting_code;
               
                ZoneSetting::updateOrCreate($check, $values);
          

            break;

               
            

            

        }
    }
    else
    {
        //$this->zone->ZoneSetting->where('name','zoneshield')->first()->delete();
    }




    $settings = $stackPath->get('/sites/'.$this->zone->zone_id."/waf")->data;

         $check['zone_id'] =  $this->zone->id;
            $check['name']    = "waf";
            //$values['modified_on'] =$setting->modified_on;
             $values['editable'] = 1;
            
                $values['value'] = $settings->mode;
               
                ZoneSetting::updateOrCreate($check, $values);

    }
}
