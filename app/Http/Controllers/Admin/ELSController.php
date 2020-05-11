<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Cfaccount;
use App\Spaccount;
use App\Http\Controllers\Controller;
use App\User;
use App\Zone;
use App\ZoneSetting;
use App\Dns;
use App\Analytics;
use App\FirewallRule;
use App\wafPackage;
use App\wafGroup;
use App\SpRule;
use App\SpCondition;
use App\wafRule;
use App\PageRule;
use App\PageRuleAction;
use App\Jobs\UpdatePageRule;

use App\elsLog;
use SSH;
use \GuzzleHttp\Client;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Jobs\FetchZoneSetting;
use App\Jobs\FetchDns;
use App\Jobs\FetchAnalytics;
use App\Jobs\FetchSpAnalytics;

use App\Jobs\FetchFirewallRules;
use App\Jobs\FetchWAFPackages;
use App\Jobs\UpdateSetting;
use App\Jobs\UpdateSpSetting;
use App\Jobs\FetchZoneDetails;
use App\Jobs\PurgeCache;
use App\Jobs\FetchSpZoneSetting;
use App\Jobs\FetchZoneStatus;
use App\Jobs\FetchPageRules;
use App\Jobs\DeletePageRule;
use App\Jobs\UpdateSPWAF;
use App\Jobs\stackPath\FetchWAFPolicies;
use App\Jobs\stackPath\FetchWAFRules;
use App\Jobs\stackPath\UpdateWAFRule;
use App\Jobs\stackPath\DeleteWAFRule;

class ELSController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        
        
        if(auth()->user()->id==1)
        {
            $zones = Zone::where('plan','enterprise')->where('status','active')->get();
        }
        else
        {
          die();

        }
        

       
       
        return view('admin.els.index', compact('zones'));
    }



    public function spindex()
    {
        //

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        
        
        if(auth()->user()->id==1)
        {
            $zones = Zone::where('cfaccount_id','0')->where('status','active')->get();
        }
        else
        {
          die();

        }
        

       
       
        return view('admin.els.index', compact('zones'));
    }

    public function uploadCustomLog(Request $request)
    {
        //

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

 $elsLog="";
        $zid=$request->input('zid');

        $zone=Zone::findOrFail($zid);
        
        if(auth()->user()->id==1)
        {
            

            $hosts = [
    'http://elasticsearch:ONiNeVB5NRDNo&F9@CgJAi7d@148.251.176.73:9201'       // HTTP Basic Authentication
   
];

// dd($body);

// curl -XPUT http://<es node>:9200/.kibana/index-pattern/cloudflare -d '{"title" : "cloudflare",  "timeFieldName": "EdgeStartTimestamp"}'
$client = \Elasticsearch\ClientBuilder::create()
                    ->setHosts($hosts)
                    ->build();

if($_FILES['logs']['error']==0)
{


$indexName= "custom_".$zone->name."_".$current_time=Carbon::now('UTC')->timestamp;

$params = [
    'index' => $indexName,
    'body' => [
        'settings' => [
            'number_of_shards' => 1,
            'number_of_replicas' => 0
        ],
        'mappings' => [
            'doc' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'EdgeStartTimestamp' => [
                        'type' => 'date',
                        "format" => "epoch_second"
                    ]
                ]
            ]
        ]
    ]
];


// Create the index with mappings and settings now
$response = $client->indices()->create($params);

$params = ['body' => []];
if($response['acknowledged']==true)
{
      
      $elsLog=elsLog::create(
        [
          'index' => $indexName,
          'zone_id' => $zone->id
        ]);


  $source_file = fopen($_FILES['logs']['tmp_name'], "r" ) or die("Couldn't open $filename");
// while (!feof($source_file)) {
//     $buffer = fread($source_file, 4096);  // use a buffer of 4KB
  
//     echo $buffer;

//     die();
// }

  if ($source_file) {

    $i=0;
    while (($line = fgets($source_file)) !== false) {
       
       $i++;
      // echo $line;
$params['body'][] = [
        'index' => [
            '_index' => $indexName,
            '_type' => 'doc',
            
        ]
    ];
    


     $data=json_decode($line);

     $data->{"BucketFilter"} = $indexName;

    $params['body'][] = $data;

    unset($data);
      if ($i %1000 == 0) {

       // var_dump($params);
        $responses = $client->bulk($params);

        // erase the old bulk request
        $params = ['body' => []];
        //echo "inserting 1000 lines";
        // unset the bulk response when you are done to save memory
        
        // dd($responses);
        // die();
        unset($responses);
    }




    }

    $responses = $client->bulk($params);

        // erase the old bulk request
        $params = ['body' => []];
        //echo "inserting remaining lines";
        // unset the bulk response when you are done to save memory
        
        // dd($responses);
        // die();
        unset($responses);



    fclose($source_file);
} else {
    // error opening the file.
} 

}
        

       }
        }
     
        

      
        return view('admin.els.processed', compact('elsLog'));
    }




    public function convertLogToApache(Request $request)
    {
        //

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

 $elsLog="";

        
        if(auth()->user()->id==1)
        {
            


if($_FILES['logs']['error']==0)
{





      
    

  $source_file = fopen($_FILES['logs']['tmp_name'], "r" ) or die("Couldn't open $filename");


  if ($source_file) {

    $i=0;

    $headers = [
        'Content-type'        => 'text/x-log',
        'Content-Disposition' => 'attachment; filename="test.log"',
    ];

    // return \Response::make($content, 200, $headers);
    // 
    
    header('Content-type: text/x-log');
    header('Content-Disposition: attachment; filename="apache_'.$_FILES['logs']['name'].'"');

    while (($line = fgets($source_file)) !== false) {
       
       $i++;

    


     $data=json_decode($line);
// 19/May/2015:23:05:55 +0000

     $t=Carbon::createFromTimestamp($data->EdgeStartTimestamp);

     // dd($t->offsetHours);

     $time=$t->format('d/M/y:h:i:s +0000');
     //$time=$t->tz;

     echo $data->ClientIP." - - [".$time."] \"".$data->ClientRequestMethod." ".$data->ClientRequestURI." ".$data->ClientRequestProtocol."\" - ".$data->EdgeResponseBytes." \"".$data->ClientRequestReferer." \"".$data->ClientRequestUserAgent."\" \n\r";



    }

   

    fclose($source_file);
} else {
    // error opening the file.
} 


        

       }
        }
     
        

      
       // return view('admin.els.processed', compact('elsLog'));
    }
    public function show($zone,Request $request)
    {
        //

      $zone=Zone::where('name',$zone)->first();
     
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        
        
        if(auth()->user()->id==1)
        {
            
        }
        else
        {
          die();

        }
        

       
       if($request->input('minutes') !==null)
{
    $minutes=$request->input('minutes');
}
else
{
    $minutes=60;
}


 switch ($minutes) {
            case 1440:
                $timestamp = 'Last 24 Hours';
                $xlabel= 'hour';
                $time= "from:now-24h,mode:quick,to:now";
                break;
             case 10080:
                $timestamp = 'Last 7 Days';
                $xlabel= 'day';
                $time= "from:now-7d,mode:quick,to:now";
                break;
             case 43200:
                $timestamp = 'Last 30 Days';
                $xlabel= 'day';
                $time= "from:now-30d,mode:quick,to:now";
                break;
            case 720:
                $timestamp = 'Last 12 Hours';
                $xlabel= '30m';
                 $time= "from:now-12h,mode:quick,to:now";
                break;
            case 360:
                $timestamp = 'Last 6 Hours';
                $xlabel= '15m';
                 $time= "from:now-6h,mode:quick,to:now";
                break;
            case 60:
                $timestamp = 'Last 1 Hour';
                $xlabel= 'minute';
                $time= "from:now-1h,mode:quick,to:now";
                break;
            
            default:
                $timestamp = 'Last 24 Hours';
                 $time= "from:now-24h,mode:quick,to:now";
                $xlabel= 'hour';
                break;
        }




       

$internalID= $zone->internalID;
$current_time=Carbon::now('UTC');

  $body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "EdgeStartTimestamp",
        "size": 1,
        "order": {
          "2-orderAgg": "desc"
        }
      },
      "aggs": {
        "2-orderAgg": {
          "max": {
            "field": "EdgeStartTimestamp"
          }
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query": '.$internalID.'
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
             "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes(4320)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';


$hosts = [
    'http://elasticsearch:ONiNeVB5NRDNo&F9@CgJAi7d@148.251.176.73:9201'       // HTTP Basic Authentication
   
];

// dd($body);

// curl -XPUT http://<es node>:9200/.kibana/index-pattern/cloudflare -d '{"title" : "cloudflare",  "timeFieldName": "EdgeStartTimestamp"}'
$client = \Elasticsearch\ClientBuilder::create()
                    ->setHosts($hosts)
                    ->build();

$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];


                    $results = $client->search($params);

                    if(isset($results['aggregations'][2]['buckets'][0]))
                    {
                        $latestLog=$results['aggregations'][2]['buckets'][0]['key_as_string'];
                   

                    $timeDiff=Carbon::createFromTimestamp($latestLog)->diffForHumans(null, true);
                    //dd($deviceType1);
                    $latestLoginfo=Carbon::createFromTimestamp($latestLog)->toRfc822String();
                    }
                    else
                    {
                       $latestLoginfo = " No Logs";
                    }
                    


$current_time=Carbon::now('UTC');

  $body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "EdgeStartTimestamp",
        "size": 1,
        "order": {
          "2-orderAgg": "asc"
        }
      },
      "aggs": {
        "2-orderAgg": {
          "max": {
            "field": "EdgeStartTimestamp"
          }
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query": '.$internalID.'
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
             "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes(4320)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';


$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];

  
                    $results = $client->search($params);
                    if(isset($results['aggregations'][2]['buckets'][0]))
                    {
                    $firstLog=$results['aggregations'][2]['buckets'][0]['key_as_string'];
Carbon::createFromTimestamp($latestLog)->diff(Carbon::createFromTimestamp($firstLog))->format(' %m months, %d days, %h hours and %i minutes');
//die();

}


$body ='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "EdgeStartTimestamp",
        "size": 1,
        "order": {
          "_count": "desc"
        }
      },
      "aggs": {
        "3": {
          "top_hits": {
            "_source": "CacheCacheStatus",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "4": {
          "top_hits": {
            "_source": "ClientDeviceType",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "5": {
          "top_hits": {
            "docvalue_fields": [
              "ClientIP.keyword"
            ],
            "_source": "ClientIP.keyword",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "6": {
          "top_hits": {
            "_source": "ClientRequestHost",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "7": {
          "top_hits": {
            "_source": "ClientRequestMethod",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "8": {
          "top_hits": {
            "_source": "ClientRequestProtocol",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "9": {
          "top_hits": {
            "_source": "ClientRequestReferer",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "10": {
          "top_hits": {
            "_source": "ClientRequestURI",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        },
        "11": {
          "top_hits": {
            "_source": "ClientRequestUserAgent",
            "size": 1,
            "sort": [
              {
                "EdgeStartTimestamp": {
                  "order": "desc"
                }
              }
            ]
          }
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "RayID": {
              "query": "3ef866b62cf430b4"
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
              "gte": 1519032983418,
              "lte": 1519036583418,
              "format": "epoch_millis"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';

$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];


//                     $results = $client->search($params);

// dd($results['aggregations'][2]['buckets'][0]);



        return view('admin.els.show', compact('zone','minutes','time'));
    }



    public function showClientView($zone,Request $request)
    {
        //

      $zone=Zone::where('name',$zone)->first();
     
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        
        
        if(auth()->user()->id==1)
        {
            
        }
        else
        {
          die();

        }
        

       
if($request->input('minutes') !==null)
{
    $minutes=$request->input('minutes');
}
else
{
    $minutes=60;
}

$current_time=Carbon::now('UTC');

 







if($zone->internalID=="")
{
    $internalID=0;
}
else{
    $internalID=$zone->internalID;
}
$body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "ClientDeviceType.keyword",
        "size": 5,
        "order": {
          "_count": "desc"
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query": '.$internalID.'
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
              "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes($minutes)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';
$current_time->addMinutes($minutes);
// dd($body);

$hosts = [
    'http://elasticsearch:ONiNeVB5NRDNo&F9@CgJAi7d@148.251.176.73:9201'       // HTTP Basic Authentication
   
];


// curl -XPUT http://<es node>:9200/.kibana/index-pattern/cloudflare -d '{"title" : "cloudflare",  "timeFieldName": "EdgeStartTimestamp"}'
$client = \Elasticsearch\ClientBuilder::create()
                    ->setHosts($hosts)
                    ->build();

$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];


                    $results = $client->search($params);
                    $deviceType1=$results['aggregations'][2]['buckets'];
                    $deviceType=[];
                     // dd($deviceType);
                    foreach ($deviceType1 as $key => $value) {
                        # code...
                         $deviceType[$key][0]=$deviceType1[$key]['key'];
                        $deviceType[$key][1]=$deviceType1[$key]['doc_count'];

                    }


$body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "ClientRequestHost.keyword",
        "size": 5,
        "order": {
          "_count": "desc"
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query": '.$internalID.'
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
              "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes($minutes)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';
   $current_time->addMinutes($minutes);   



$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];


                    $results = $client->search($params);  
try {
  $hosts=$results['aggregations'][2]['buckets'];
} catch (Exception $e) {
  $hosts=[];
}
   


   $body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "ClientRequestReferer.keyword",
        "size": 5,
        "order": {
          "_count": "desc"
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query": '.$internalID.'
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
              "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes($minutes)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';
      

$current_time->addMinutes($minutes);

$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];


                    $results = $client->search($params);  
try {
  $referers=$results['aggregations'][2]['buckets'];
} catch (Exception $e) {
  $referers=[];
}



  $body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "ClientIP.keyword",
        "size": 5,
        "order": {
          "_count": "desc"
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query": '.$internalID.'
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
              "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes($minutes)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';

 $current_time->addMinutes($minutes);     



$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];

// dd($body);

                    $results = $client->search($params);  

                  //  dd($results);
try {
  $clientsIP=$results['aggregations'][2]['buckets'];
} catch (Exception $e) {
  $clientsIP=[];
}





$body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "ClientRequestURI.keyword",
        "size": 5,
        "order": {
          "_count": "desc"
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query": '.$internalID.'
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
              "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes($minutes)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';

 $current_time->addMinutes($minutes);     



$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];

// dd($body);

                    $results = $client->search($params);  

                  //  dd($results);
try {
  $ClientRequestURI=$results['aggregations'][2]['buckets'];
} catch (Exception $e) {
  $ClientRequestURI=[];
}




$body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "ClientRequestMethod.keyword",
        "size": 5,
        "order": {
          "_count": "desc"
        }
      }
    }
  },
  "stored_fields": [
    "*"
  ],
  "script_fields": {},
  "docvalue_fields": [
    "@timestamp",
    "EdgeStartTimestamp"
  ],
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "ZoneID": {
              "query": '.$internalID.'
            }
          }
        },
        {
          "range": {
            "EdgeStartTimestamp": {
              "lte": '.$current_time->timestamp.',
              "gte": '.$current_time->subMinutes($minutes)->timestamp.',
              "format": "epoch_second"
            }
          }
        }
      ],
      "filter": [],
      "should": [],
      "must_not": []
    }
  }
}';

 $current_time->addMinutes($minutes);     



$params = [
    'index' => 'cloudflare',
    'type' => 'doc',
    'body' => $body
];

// dd($body);

                    $results = $client->search($params);  

                  //  dd($results);
try {
  $ClientRequestMethod=$results['aggregations'][2]['buckets'];
} catch (Exception $e) {
  $ClientRequestMethod=[];
}


     //dd($deviceType);
        return view('admin.els.clientView', compact('zone','minutes','deviceType','hosts','referers','clientsIP','ClientRequestURI','ClientRequestMethod'));
    }

  

}
