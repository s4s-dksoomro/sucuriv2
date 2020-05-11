<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\SpAnalytics;


class FetchSpAnalytics implements ShouldQueue
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

        

       



        
        $this->fetchAndSave('stats','hourly');
        $this->fetchAndSave('stats','daily');

        $this->fetchAndSave('statuscodes','hourly');
        $this->fetchAndSave('statuscodes','daily');

        $this->fetchAndSave('filetypes','hourly');
        $this->fetchAndSave('filetypes','daily');


        
        // $this->fetchAndSave('stats','43200');
        // $this->fetchAndSave(10080);
        // $this->fetchAndSave(43200);
        // if($this->zone->plan=="enterprise")
        // {
        //     $this->fetchAndSave(720);
        //     $this->fetchAndSave(360);
        //     $this->fetchAndSave(30);
        // }


         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-30); // last 30 minutes // Enterprise
         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-360); // last 6 hours // Pro plan
         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-720); // last 12 hours // Pro plan
         
         // last 24 hours (24 records)

        //$analytics = $zones->getZoneAnalytics($zone->zone_id,-43200); // last 30 days (30 records)
        //$analytics = $zones->getZoneAnalytics($zone->zone_id,-10080); // last 7 Days (7 records)

       


        //$analytics=

       
        

        
    }

    public function fetchAndSave($type,$period)
    {

        

        $stackPath= new \MaxCDN($this->zone->spaccount->alias,$this->zone->spaccount->key,$this->zone->spaccount->secret);




        
      
    

       // $analytics=$stackPath->get('/reports/'.$this->zone->zone_id."/stats/daily?page_size=1000")->data->stats;
        $analytics=$stackPath->get('/reports/'.$this->zone->zone_id."/".$type."/".$period."?page_size=100000")->data->$type;
         var_dump($analytics);

        foreach ($analytics as $stat) {
            # code...

        $check['zone_id'] = $this->zone->id;
        
        $check['type']   = $type;
        $check['period']   = $period;
        if(isset($stat->timestamp)){ $check['timestamp']   = $stat->timestamp;}
        if(isset($stat->status_code)){ $check['status_code'] = $stat->status_code; }

        
        if(isset($stat->size)){ $values['size'] = $stat->size; }
        $values['hit'] = $stat->hit;
        if(isset($stat->noncache_hit)){ $values['noncache_hit'] = $stat->noncache_hit; }
        if(isset($stat->cache_hit)){ $values['cache_hit'] = $stat->cache_hit; }
        if(isset($stat->bots)){ $values['bots'] = $stat->bots; }
        if(isset($stat->blocked)){ $values['blocked'] = $stat->blocked; }
        if(isset($stat->custom)){ $values['custom'] = $stat->custom; }
        if(isset($stat->file_type)){ $values['file_type'] = $stat->file_type; }
        
        if(isset($stat->definition)){ $values['definition'] = $stat->definition; }

        SpAnalytics::updateOrCreate($check, $values);

        }

       

        //

    }
}
