<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;


class PurgeCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$all,$files, $tags;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone,  $all=true, $files, $tags)
    {
        //
        $this->zone=$zone;
        $this->all=$all;
        $this->files=$files;
        $this->tags=$tags;
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


        if($this->zone->cfaccount_id!=0)
        {



        $key     = new \Cloudflare\API\Auth\APIKey($this->zone->cfaccount->email, $this->zone->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);



      if($this->all)
      {
        $res=$zones->cachePurgeEverything($this->zone->zone_id);  
      }
      else
      { 

        $res=$zones->cachePurge($this->zone->zone_id, $this->files, $this->tags);  
      }
      
      // var_dump($res);


       
    }
    else
    {
        $stackPath= new \MaxCDN($this->zone->spaccount->alias,$this->zone->spaccount->key,$this->zone->spaccount->secret);
        $data="";
        if($this->files)
        {
             $data= [
        'files' => $this->files];
        }
        $ret=$stackPath->delete('/sites/'.$this->zone->zone_id.'/cache',$data); 

        // var_dump($ret);

        
    }

        
    }
}
