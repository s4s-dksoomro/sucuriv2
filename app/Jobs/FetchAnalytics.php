<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\Analytics;


class FetchAnalytics implements ShouldQueue
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

        

       



        
        $this->fetchAndSave(1440);
        $this->fetchAndSave(10080);
        $this->fetchAndSave(43200);
         if($this->zone->plan=="enterprise" OR $this->zone->plan=="pro")
        {
        $this->fetchAndSave(720);
        $this->fetchAndSave(360);
        }
        if($this->zone->plan=="enterprise")
        {
            
            $this->fetchAndSave(30);
        }


         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-30); // last 30 minutes // Enterprise
         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-360); // last 6 hours // Pro plan
         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-720); // last 12 hours // Pro plan
         
         // last 24 hours (24 records)

        //$analytics = $zones->getZoneAnalytics($zone->zone_id,-43200); // last 30 days (30 records)
        //$analytics = $zones->getZoneAnalytics($zone->zone_id,-10080); // last 7 Days (7 records)

       


        //$analytics=

       
        

        
    }

    public function fetchAndSave($minutes)
    {

        

        $key     = new \Cloudflare\API\Auth\APIKey($this->zone->cfaccount->email, $this->zone->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);

        $analytics = $zones->getZoneAnalytics($this->zone->zone_id,"-".$minutes);

        $check['zone_id'] = $this->zone->id;
        
        $check['minutes']   = $minutes;
        $values['value'] = serialize($analytics);


        Analytics::updateOrCreate($check, $values);

    }
}
