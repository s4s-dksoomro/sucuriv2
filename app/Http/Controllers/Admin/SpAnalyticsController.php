<?php

namespace App\Http\Controllers\Admin;

use App\SpAnalytics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Zone;
use Carbon\Carbon;
use App\Jobs\FetchSpAnalytics;

class SpAnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *toDateString
     * @return \Illuminate\Http\Response
     */
    public function index($zone, Request $request)
    {

       
        
        $zone =   Zone::where('name',$zone)->first();

        if($request->input('minutes') !==null)
        {
            $minutes=$request->input('minutes');
        }
        else
        {
            $minutes=43200;    
        }


         switch ($minutes) {
            case 1440:
                $timestamp = 'Last 24 Hours';
                $period="hourly";
                $limit=24;
                break;
             case 10080:
                $timestamp = 'Last 7 Days';
                $period="daily";
                $limit=7;
                break;
             case 43200:
                $timestamp = 'Last Month';
                $period="daily";
                $limit=30;
                break;
            
            default:
                $timestamp = 'Last 24 Hours';
                break;
        }


        $ts = Carbon::now()->subMinutes($minutes)->format('Y-m-d');
        //dd($timestamp);
        ////->where('timestamp',">=",$timestamp)
        $stats = $zone->SpAnalytics->where('type','stats')->where('period',$period)->take($limit)->all();

        $request_all = $zone->SpAnalytics->where('type','stats')->where('period',$period)->take($limit)->sum("hit");
        $request_cached = $zone->SpAnalytics->where('type','stats')->where('period',$period)->take($limit)->sum("cache_hit");
        $request_uncached = $zone->SpAnalytics->where('type','stats')->where('period',$period)->take($limit)->sum("noncache_hit");
        


        $bandwidth = $zone->SpAnalytics->where('type','stats')->where('period',$period)->take($limit)->sum("size");

        $bandwidth=number_format($bandwidth / (1024 * 1024 * 1024) , 2);


        $status_codes = $zone->SpAnalytics->where('type','statuscodes')->where('period',$period)->groupBy('timestamp')->take($limit)->all();


        $filetypes = $zone->SpAnalytics->where('type','filetypes')->where('period',$period)->groupBy('timestamp')->take($limit)->all();
        
        $parsed_status=[];
        foreach ($status_codes as $key=>$status_period) {
            # code...
            $parsed_status[$key]['timestamp']=$status_period[0]['timestamp'];
            foreach ($status_period as $status) {
                # code...
                if(starts_with($status['status_code'],'2'))
                {
                    $parsed_status[$key]['2xx']=$status['hit'];
                }elseif(starts_with($status['status_code'],'3'))
                {
                    $parsed_status[$key]['3xx']=$status['hit'];
                }elseif(starts_with($status['status_code'],'4'))
                {
                    $parsed_status[$key]['4xx']=$status['hit'];
                }elseif(starts_with($status['status_code'],'5'))
                {
                    $parsed_status[$key]['5xx']=$status['hit'];
                }
                

            }
        }


$parsed_filetypes=[];


    
      

        foreach ($filetypes as $key=>$status_period) {
            # code...
            $parsed_filetypes[$key]['timestamp']=$status_period[0]['timestamp'];
            foreach ($status_period as $filetype) {
                # code...
               
                
                $parsed_filetypes[$key][$filetype['file_type']]=$filetype['hit'];
            }
        }

       // $stats_json=array();
        foreach ($stats as $stat) {
            # code...

            $stat['period']=$stat['timestamp'];
            $stat['cached']=$stat['cache_hit'];
            $stat['uncached']=$stat['noncache_hit'];
            $stat['size']= number_format($stat['size'] / (1024 * 1024 * 1024) , 2)."GB";

           unset($stat['id']);
           unset($stat['zone_id']);
           unset($stat['created_at']);
           unset($stat['updated_at']);
           unset($stat['type']);
           unset($stat['cache_hit']);
           unset($stat['noncache_hit']);
           unset($stat['timestamp']);
        }

        $stats=array_values($stats);
        $status_codes=array_values($parsed_status);

       // echo json_encode($parsed_filetypes);
       // die();

        return view('admin.spanalytics.index', compact('zone','stats','minutes','timestamp','request_all','request_uncached','request_cached','bandwidth','status_codes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SpAnalytics  $spAnalytics
     * @return \Illuminate\Http\Response
     */
    public function show(SpAnalytics $spAnalytics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SpAnalytics  $spAnalytics
     * @return \Illuminate\Http\Response
     */
    public function edit(SpAnalytics $spAnalytics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SpAnalytics  $spAnalytics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SpAnalytics $spAnalytics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SpAnalytics  $spAnalytics
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpAnalytics $spAnalytics)
    {
        //
    }
}
