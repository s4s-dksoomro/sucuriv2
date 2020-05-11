<?php

namespace App\Jobs;


use App\Cfaccount;


class FetchZones 
{


    public $user_id, $cfaccount,$page;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cfaccount $cfaccount, $page)
    {
        //
        $this->cfaccount=$cfaccount;
        $this->page=$page;
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

        $key     = new \Cloudflare\API\Auth\APIKey($this->cfaccount->email, $this->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);


      
            $zones=$zones->listZones("","",$this->page); //page=1 (50 per page)
            


                return $zones;

            


       


        
    }
}
