<?php

namespace App\Jobs;


use App\Zone;


class AddDNS 
{


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
        $dns   = new \Cloudflare\API\Endpoints\DNS($adapter);



      

        
      
      
            $zones=$dns->addRecord($this->zone->zone_id); //page=1 (50 per page)
            

    


                return $zones;

            


       


        
    }
}
