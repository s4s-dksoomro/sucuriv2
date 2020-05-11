<?php

namespace App\Jobs\stackPath;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\customDomain;


class FetchCustomDomains implements ShouldQueue
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


        $customDomains = $stackPath->get('/sites/'.$this->zone->zone_id."/customdomains")->data->customdomains;

        $this->zone->customDomain->each(function($customDomain){
    $customDomain->delete();
});
     

        foreach ($customDomains as $customDomain) {
            $check['zone_id'] = $this->zone->id;
           
            $check['resource_id']    = $customDomain->id;
            //$values['modified_on'] =$setting->modified_on;
           	  $values['custom_domain']    = $customDomain->custom_domain;
           
                customDomain::updateOrCreate($check, $values);
            

            

        }




    






    }
}
