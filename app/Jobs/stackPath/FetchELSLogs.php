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


class FetchELSLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone,$start,$end,$next_page_key;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone,$start=false,$end=false,$next_page_key=false)
    {
        //
        $this->zone=$zone;
        $this->start=$start;
        $this->end=$end;
        $this->next_page_key=$next_page_key;
        // $this->user_id=auth()->user()->id;
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



            $hosts = [
    'http://elasticsearch:ONiNeVB5NRDNo&F9@CgJAi7d@148.251.176.73:9201'       // HTTP Basic Authentication
   
];


$client = \Elasticsearch\ClientBuilder::create()
                    ->setHosts($hosts)
                    ->build();
             $indexName  = 'sp_'.$this->zone->zone_id;
             $params = ['body' => []];

           
// &start_key=1533658850000

if($this->next_page_key)
{

$logs=$stackPath->get("/logs?start=".$this->start."&end=".$this->end."&limit=1000&zones=".$this->zone->zone_id."&start_key=".$this->next_page_key);

// var_dump("/logs?start=".$this->start."&end=".$this->end."&limit=1000&zones=".$this->zone->zone_id."&start_key=".$this->next_page_key);
// var_dump($logs);
     // $logs=$stackPath->get("/logs?start=$start&end=$end&limit=1000&zones=687080");
}
else
{


            if ($this->start) {
                    

                     $this->start = Carbon::createFromTimestamp($this->start);

                     $this->end = Carbon::createFromTimestamp($this->end);

                } else {
                   

                     $this->start=Carbon::now();
                     $this->end=Carbon::now();

                      $this->start = $this->start->subMinutes(20);

                      $this->end = $this->end->subMinutes(10);

                }



                // Carbon::createFromTimestamp($this->zone->els_ts)->subMinutes(20)->timestamp;

            
             // $end=$end->subMinutes(20);
            
             // dd($end->toIso8601String());
             // dd($end."sdf".$start);
            $this->end=str_replace("+00:00", "Z", $this->end->toIso8601String());
            $this->start=str_replace("+00:00", "Z", $this->start->toIso8601String());

// dd($end."sdf".$start);

             // $logs=$stackPath->get("/logs?start=$start&end=$end&limit=1000&zones=687080&start_key=1534714793000");

     $logs=$stackPath->get("/logs?start=".$this->start."&end=".$this->end."&limit=1000&zones=".$this->zone->zone_id);
// dd("/logs?start=".$this->start."&end=".$this->end."&limit=1000&zones=".$this->zone->zone_id);

}
// $logs=$stackPath->get("/logs?limit=1000&zones=687080");

// $logs=$stackPath->get("/logs?start_key=1534714102000");

            $this->next_page_key=$logs->next_page_key;
             // var_dump($logs);
             if(count($logs->records)!=0)
             {

                $i=0;
                foreach ($logs->records as $data) {



                    // dd($line);

                        $i++;
                        
                        // echo $line;

                        $params['body'][] = [
                                'index' => [
                                    '_index' => $indexName,
                                    '_type' => 'doc',
                                    
                                ]
                            ];
                            


                            // $data=$line;

                            // $data->{"BucketFilter"} = $indexName;

                            $params['body'][] = json_decode(json_encode($data), TRUE);

                            // dd($params);
                            unset($data);
                              if ($i %1000 == 0) {

                                // dd($params);
                                $responses = $client->bulk($params);

                                // erase the old bulk request
                                $params = ['body' => []];
                                //echo "inserting 1000 lines";
                                // unset the bulk response when you are done to save memory
                                
                                // dd($responses);
                                // // die();
                                unset($responses);
                            }


                }

if ($i %1000 != 0 AND $i < 1000) {


// dd($params);
                    $responses = $client->bulk($params);

        // erase the old bulk request
       
        //echo "inserting remaining lines";
        // unset the bulk response when you are done to save memory
        
        // dd($responses);
        // die();
        unset($responses);
    }
 $params = ['body' => []];

 if($this->next_page_key!=null AND $this->next_page_key!= "null" AND count($logs->records)==1000)
 {
        FetchELSLogs::dispatch($this->zone,$this->start,$this->end,$this->next_page_key);


}
else
{
    echo "processed all records";
}
        }
             







        
    }

  
}
