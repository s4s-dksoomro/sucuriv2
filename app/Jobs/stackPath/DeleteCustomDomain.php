<?php

namespace App\Jobs\stackPath;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;



class DeleteCustomDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$record_id,$fetch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $record_id,$fetch=true)
    {
        //
        $this->zone=$zone;
         $this->fetch=$fetch;
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

       $stackPath= new \MaxCDN($this->zone->spaccount->alias,$this->zone->spaccount->key,$this->zone->spaccount->secret);


        
      

     


        $wafRule=$stackPath->delete('/sites/'.$this->zone->zone_id.'/customdomains/'.$this->record_id); 

       


        
    }
}
