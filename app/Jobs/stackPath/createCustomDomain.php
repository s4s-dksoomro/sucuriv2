<?php

namespace App\Jobs\stackPath;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;

use \App\Jobs\stackPath\FetchCustomDomains;



class createCustomDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$customDomain;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, $customDomain)
    {
        //
        $this->zone=$zone;
         $this->customDomain=$customDomain;
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

 $data= ['custom_domain' => $this->customDomain];
   
        $customDomains = $stackPath->post('/sites/'.$this->zone->zone_id."/customdomains",$data);

        
      
FetchCustomDomains::dispatch($this->zone);
     

       
       
        

       


        
    }
}
