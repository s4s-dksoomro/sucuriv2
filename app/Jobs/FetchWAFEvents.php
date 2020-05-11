<?php

namespace App\Jobs;

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

        

       



 $key     = new \Cloudflare\API\Auth\APIKey($this->zone->cfaccount->email, $this->zone->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $waf   = new \Cloudflare\API\Endpoints\WAF($adapter);



        $events=$waf->getEvents($this->zone->zone_id);
        


      // dd($events);


        foreach ($events as $event) {
            # code...
           $event=(array)$event;
            $check['resource_id']=$event['id'];
            $check['zone_id']=$this->zone->id;


            $event['client_ip']=$event['ip'];
            $event['scheme']=$event['protocol'];
            $event['domain']=$event['host'];
            $event['rule_name']=$event['rule_message'];
            $event['timestamp']=strtotime($event['occurred_at']);
            $event['count']=0;
            $event['ref_id']='';

            // if($event['rule_id']==null)
            // {   
            //     $event['rule_id']=0;
            //     var_dump($event['rule_name']);
            //      // $event['rule_info']=$event['rule_info']->value;
            // }

            // echo $check['resource_id']."<br>";
            unset($event['id'],$event['ip'],$event['protocol'],$event['host'],$event['rule_message'],$event['request_duration'],$event['triggered_rule_ids'],$event['cloudflare_location'],$event['occurred_at'],$event['rule_detail'],$event['type'],$event['rule_info']);
            //var_dump($event);
            $insertedEvent=wafEvent::updateOrCreate($check,$event);
            // dd($insertedEvent);


        }



        
    }

  
}
