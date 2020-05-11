<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;



class DeleteDNS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$record_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, string $record_id)
    {
        //
        $this->zone=$zone;
        $this->record_id=$record_id;
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
        $DNS   = new \Cloudflare\API\Endpoints\DNS($adapter);


        
      
      $DNS->deleteRecord($this->zone->zone_id, $this->record_id);


       


        
    }
}
