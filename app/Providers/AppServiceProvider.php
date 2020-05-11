<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use App\panelLog;
use App\Zone;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);



    view()->composer('admin*', function($view)
    {
        
        // dd(unserialize('{"displayName":"App\Jobs\FetchDns","job":"Illuminate\Queue\CallQueuedHandler@call","maxTries":null,"timeout":null,"data":{"commandName":"App\Jobs\FetchDns","command":"O:17:\"App\Jobs\FetchDns\":6:{s:7:\"\u0000*\u0000zone\";O:45:\"Illuminate\Contracts\Database\ModelIdentifier\":2:{s:5:\"class\";s:8:\"App\Zone\";s:2:\"id\";i:34;}s:9:\"\u0000*\u0000delete\";b:1;s:6:\"\u0000*\u0000job\";N;s:10:\"connection\";N;s:5:\"queue\";N;s:5:\"delay\";N;}"}}'));
        $url=$this->app->request->url();
        $data=$this->app->request->all();
        
         $route=\Route::getCurrentRoute();

         if(isset($route->parameterNames))
         {
        if(in_array('zone',$route->parameterNames))
        {
            // dd();
            $zone=$route->parameters['zone'];
            // dd();
            // dd('/admin/'.$zone.'/');
            $url=str_replace(url('/admin/'.$zone)."/", "", $url);

            $zoneName=$zone;
            $zone=Zone::where('name',$zone)->first();

            // dd($this->app->request);
            // dd($route->getActionName());
            // dd($url);
            if(isset($data['_token']))
            {
                unset($data['_token']);
            }

            // dd($data);
            $uid=auth()->user()->id;
            $name=str_replace("App\Http\Controllers\Admin\\","",$route->getActionName());

            if(strpos($name, "PanelLogController")===false AND strpos($name, "ELS")===false)
            {

                if($zone)
                {
            panelLog::create([
                'user_id' => $uid,
                'zone_id' => $zone->id,
                'name' => $name,
                'parameters' => json_encode($data),
                'type' => 0,
                'uri'   => $url,
                'payload' => ''
            ]);
        }

            }
            // die();
        }
    }
        // dd($url);
        
    });

        Queue::after(function (JobProcessed $event) {


            $data=unserialize($event->job->payload()['data']['command']);

           // dd($event->job->getJobId());
            // $event->connectionName
            // $event->job
            // $event->data
            if(isset($data->user_id))
            {
                $uid=$data->user_id;
            }
            else
            {
                $uid=1;
            }
            panelLog::create([
                'user_id' => $uid,
                'zone_id' => $data->zone->id,
                'name' => get_class($data),
                'type' => 1,
                'payload' => serialize($data)
            ]);
        });

        // Queue::before(function (JobProcessing $event) {

        //     dd($event);
        //     $data=unserialize($event->job->payload()['data']['command']);
        //    // dd()
        //     // $event->connectionName
        //     // $event->job
        //     // $event->data

        //     // panelLog::create([
        //     //     'user_id' => $data->user_id,
        //     //     'zone_id' => $data->zone->id,
        //     //     'name' => get_class($data)
        //     // ]);
        // });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        

    }
}
