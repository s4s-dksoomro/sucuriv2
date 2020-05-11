<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\CustomCertificate;


use App\Jobs\FetchCustomCertificates;

class DeleteCustomCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$certificate;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone,$certificate)
    {
        //
        $this->zone=$zone;
        $this->certificate=$certificate;
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



      
$CustomCertificate=$zones->deleteZoneCustomCertificate($this->zone->zone_id,$this->certificate);
     


       
//FetchCustomCertificates::dispatch($this->zone)->onConnection('sync');

    //echo "SSL certificate Added";    
    }
}
