<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;


class FetchZoneSetting implements ShouldQueue
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

        $key     = new \Cloudflare\API\Auth\APIKey($this->zone->cfaccount->email, $this->zone->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);



        $settings = $zones->getZoneSettings($this->zone->zone_id);

        //dd($settings);

        foreach ($settings as $setting) {
            $check['zone_id'] = $this->zone->id;
            $check['name']    = $setting->id;
            //$values['modified_on'] =$setting->modified_on;
            $values['editable'] = $setting->editable;
            if (!is_object($setting->value)) {

                if(is_array($setting->value))
                {
                    $values['value'] = json_encode($setting->value);
                }
                else
                {
                    $values['value'] = $setting->value;
                }
               
                ZoneSetting::updateOrCreate($check, $values);
            } else {
                //dd($setting->value);
                foreach ($setting->value as $key => $value) {
                   

                    if (!is_object($value)) {
                        $check['name']   = $setting->id . "_" . $key;
                        $values['value'] = $value;
                        ZoneSetting::updateOrCreate($check, $values);
                    } else {
                        //dd($setting->value);
                        foreach ($value as $k => $val) {
                            # code...
                            $check['name']   = $setting->id. "_" .$key . "_" . $k;
                            $values['value'] = $val;
                            ZoneSetting::updateOrCreate($check, $values);
                        }
                    }

                }
            }

            

        }
    }
}
