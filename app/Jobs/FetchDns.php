<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\Dns;


class FetchDns implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $zone,$delete;

    public $user_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone,$delete=false)
    {
        //

        $this->zone=$zone;
        $this->delete=$delete;
        // $job=get_class($this);
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
        $dns   = new \Cloudflare\API\Endpoints\DNS($adapter);



    $records = $dns->listRecords($this->zone->zone_id)->result;

    if($records AND $this->delete)
     {
        foreach($this->zone->dns as $recordDel)
        {
            
                $recordDel->delete();
           
        }

        //die();
     }

       // echo $this->zone->name;
       // dd($records);

        //dd($records);
        foreach ($records as $record) {
            $record=json_decode(json_encode($record),true);

            $check['zone_id'] = $this->zone->id;
            $check['record_id']    = $record['id'];

            @array_forget($record,["meta","created_on","modified_on","id","zone_id","zone_name","data"]);
            //dd($record);
                    
       

           if($record!==true AND $record!==false)
           {
            @Dns::updateOrCreate($check, $record);   
           }
         
           
            

        }
    }
}
