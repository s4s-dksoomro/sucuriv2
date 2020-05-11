<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ZoneSetting;


class UpdateDnsRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$record_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone, int $record_id)
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


        
      $dns=Zone::where('id',$this->zone->id)->first()->Dns->where('id',$this->record_id)->first();

      $dnsArray['type']=$dns->type;
      $dnsArray['name']=$dns->name;
      $dnsArray['content']=$dns->content;
      $dnsArray['ttl']=$dns->ttl;

      if($dns->proxiable==1)
      { 
        if($dns->proxied==1)
        {
            $dnsArray['proxied']=true;
        }
        else
        {
            $dnsArray['proxied']=false;
        }
        
      }


      if($dns->record_id=="PENDING")
      {
        if(!isset($dnsArray['proxied']))
        {
          $dnsArray['proxied']=false;
        }
        $DNS->addRecord($this->zone->zone_id, $dns->type,$dns->name,$dns->content,$dns->ttl,$dnsArray['proxied']);
        // dd($dns);
        $dns->delete();
        // FetchDns::dispatch($this->zone);
    
      }
      else
      {
        $DNS->updateRecordDetails($this->zone->zone_id, $dns->record_id,$dnsArray);
      }
      
      


       


        
    }
}
