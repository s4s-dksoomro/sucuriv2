<?php

namespace App\Jobs\stackPath;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\SpAnalytics;
use Carbon\Carbon;
use App\wafEvent;


class FetchWAFEvents implements ShouldQueue
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






if(count($this->zone->wafEvent)>0)
{

    $event=$this->zone->wafEvent->sortByDesc('ts')->first();
    $start=Carbon::createFromTimestamp($event->ts+1)->toIso8601String();

    //  $start=Carbon::createFromTimestamp($this->zone->waf_ts+1)->toIso8601String();
       
}
else
{

    
   $start=Carbon::now('UTC')->subMinutes(10)->toIso8601String(); 

}
$start=urlencode($start);
       // echo '/sites/'.$this->zone->zone_id."/waf/events?start=".$now;

        $events=$stackPath->get('/sites/'.$this->zone->zone_id."/waf/events?page_size=100&start=".$start)->data->events;


        foreach ($events as $event) {
            # code...
            $event=(array)$event;
            $event['resource_id']=$event['_id'];
            $event['zone_id']=$this->zone->id;
            unset($event['_id']);
            $insertedEvent=wafEvent::create($event);

            $event=$stackPath->get('/sites/'.$this->zone->zone_id."/waf/events/".$event['resource_id'])->data;

            $event=(array)$event;
            unset($event['id']);
            unset($event['country_code']);
            $event['user_agent']=json_encode($event['user_agent']);
            $event['headers']=json_encode($event['headers']);

            $insertedEvent->update($event);
           // dd($event);


        }



        
    }

  
}
