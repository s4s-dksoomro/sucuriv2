<?php

namespace App\Console;

use App\wafEvent;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use SSH;
use App\Jobs\stackPath\FetchELSLogs;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            $zones = Zone::where('els', 1)->where('cfaccount_id','<>', 0)->get();
            $now   = Carbon::now('UTC')->timestamp;
            foreach ($zones as $zone) {

                $end = Carbon::createFromTimestamp($now)->subMinutes(20)->timestamp;

                $oldfile = $zone->name . "_" . md5($zone->els_ts) . ".log";
                if ($zone->els_ts != "") {
                    $start = $zone->els_ts + 1;
                } else {
                    $start = Carbon::createFromTimestamp($now)->subMinutes(1440)->timestamp;
                }



                 if($now-$start>= 604800)
                {

                    $start = $now - 604801;
                }



                if (($end - $start) > ($zone->els_bucket * 60)) // 20 min
                {
                    $nowOne = Carbon::createFromTimestamp($start + ($zone->els_bucket * 60) - 1);

                    // $end = $nowOne->subMinutes($zone->els_bucket)->timestamp; // 15 minutes

                    $end= $start + ($zone->els_bucket * 60);
                } else {
                    // $nowOne = $now;
                }

                
                if($end-$start>=180)
                {

                

                $filename = $zone->name . "_" . md5($end) . ".log";

                if ($zone->els == 1 and $zone->internalID != "" and $zone->internalID != "FALSE") {

                    SSH::run([

                        "~/filebeat_cleaner.rb",
                        "rm -Rf /opt/data/filebeat/done",
                        "curl -sv -X GET -H \"Accept-encoding: gzip\" -H \"X-Auth-Email: " . $zone->cfaccount->email . "\" -H \"X-Auth-Key: " . $zone->cfaccount->user_api_key . "\" \"https://api.cloudflare.com/client/v4/zones/" . $zone->zone_id . "/logs/received?fields=EdgeStartTimestamp,CacheCacheStatus,ClientDeviceType,ClientIP,ClientRequestBytes,ClientRequestHost,ClientRequestMethod,ClientRequestProtocol,ClientRequestReferer,ClientRequestURI,ClientRequestUserAgent,ClientDeviceType,EdgeResponseBytes,RayID,WAFRuleID,ZoneID&timestamps=unix&start=" . $start . "&end=" . $end . "\" | gunzip -c > logs/" . $filename,

                    ], function ($line) use ($end, $zone) {
                        // echo $line.PHP_EOL;

                        if (str_contains($line, "Server: cloudflare")) {
                            // if(!str_contains($line,"Retrieved 0 logs"))
                            // {
                            // echo "logs Retrieved, Saving TS";
                            $zone->els_ts = $end;
                            $zone->save();
                            // }
                        }
                    });

                 }

                }

            }

        })->everyMinute()->name("fetchlogs")->withoutOverlapping();



         $schedule->call(function () {
            $zones = Zone::where('els', 1)->where('cfaccount_id', 0)->get();
            $now   = Carbon::now('UTC')->timestamp;

            foreach ($zones as $zone) {

                $end = Carbon::createFromTimestamp($now)->subMinutes(1)->timestamp;

                // $oldfile = $zone->name . "_" . md5($zone->els_ts) . ".log";
                if ($zone->els_ts != "") {
                    $start = $zone->els_ts + 1;
                } else {
                    $start = Carbon::createFromTimestamp($now)->subMinutes(1440)->timestamp;
                }
                var_dump(str_replace("+00:00", "Z", Carbon::createFromTimestamp($start)->toIso8601String()));
                if (($end - $start) > ($zone->els_bucket * 60)) // 20 min
                {


                    $nowOne = Carbon::createFromTimestamp($start + ($zone->els_bucket * 60) - 1);

                    // $end = $nowOne->subMinutes($zone->els_bucket)->timestamp; // 15 minutes

                    $end = $start + ($zone->els_bucket * 60);
                } else {
                    // $nowOne = $now;
                }

               
                if($end-$start>=($zone->els_bucket*60))
                {

                

               

             
                     FetchELSLogs::dispatch($zone,$start,$end);
        
                     $zone->els_ts = $end;
                     $zone->save();


                

                }

            }

        })->everyMinute()->name("fetchSplogs")->withoutOverlapping();



        $schedule->call(function () {
            $zones = Zone::where('cfaccount_id', '!=', 0)->get();

            foreach ($zones as $zone) {

                $key     = new \Cloudflare\API\Auth\APIKey($zone->cfaccount->email, $zone->cfaccount->user_api_key);
                $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
                $waf     = new \Cloudflare\API\Endpoints\WAF($adapter);

                $events = $waf->getEvents($zone->zone_id);

                foreach ($events as $event) {
                    # code...
                    $event                = (array) $event;
                    $check['resource_id'] = $event['id'];
                    $check['zone_id']     = $zone->id;

                    $event['client_ip'] = $event['ip'];
                    $event['scheme']    = $event['protocol'];
                    $event['domain']    = $event['host'];
                    $event['rule_name'] = $event['rule_message'];
                    $event['timestamp'] = strtotime($event['occurred_at']);
                    $event['count']     = 0;
                    $event['ref_id']    = '';

                    unset($event['id'], $event['ip'], $event['protocol'], $event['host'], $event['rule_message'], $event['request_duration'], $event['triggered_rule_ids'], $event['cloudflare_location'], $event['occurred_at'], $event['rule_detail'], $event['type'], $event['rule_info']);

                    $insertedEvent = wafEvent::updateOrCreate($check, $event);

                }

            }

        })->everyFiveMinutes()->name("fetchCFWafEvents")->withoutOverlapping();




        //    $schedule->call(function () {

        //           SSH::run([

        //             "~/filebeat_cleaner.rb",
        //             "rm -Rf /opt/data/filebeat/done"

        //         ],function($line)
        //         {

        //         });

        // })->daily()->name("removeProcessedLogs")->withoutOverlapping();

        $schedule->call(function () {
            $zones = Zone::where('cfaccount_id', 0)->get();

            foreach ($zones as $zone) {

                $stackPath = new \MaxCDN($zone->spaccount->alias, $zone->spaccount->key, $zone->spaccount->secret);

                if (count($zone->wafEvent) > 0) {

                    $event = $zone->wafEvent->sortByDesc('ts')->first();
                    $start = Carbon::createFromTimestamp($event->ts + 1)->toIso8601String();

                    //  $start=Carbon::createFromTimestamp($zone->waf_ts+1)->toIso8601String();

                } else {

                    $start = Carbon::now('UTC')->subMinutes(10)->toIso8601String();

                }
                $start = urlencode($start);
                // echo '/sites/'.$zone->zone_id."/waf/events?start=".$now;

                $events = $stackPath->get('/sites/' . $zone->zone_id . "/waf/events?page_size=100&start=" . $start)->data->events;

                foreach ($events as $event) {
                    # code...
                    $event                = (array) $event;
                    $event['resource_id'] = $event['_id'];
                    $event['zone_id']     = $zone->id;
                    unset($event['_id']);
                    $insertedEvent = wafEvent::create($event);

                    $event = $stackPath->get('/sites/' . $zone->zone_id . "/waf/events/" . $event['resource_id'])->data;

                    $event = (array) $event;
                    unset($event['id']);
                    unset($event['country_code']);
                    $event['user_agent'] = json_encode($event['user_agent']);
                    $event['headers']    = json_encode($event['headers']);

                    $insertedEvent->update($event);
                    // dd($event);

                }

            }

        })->everyFiveMinutes()->name("fetchWafEvents")->withoutOverlapping();




      

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
