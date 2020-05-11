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


use App\panelLog;



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

class PanelLogController extends Controller
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
            $zones = Zone::where('status','active')->get();
        }
        else
        {
          die();

        }
        

       
       
        return view('admin.panelLogs.index', compact('zones'));
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
        

        $logs=$zone->panelLog->sortByDesc("created_at");
        // dd($logs);
//                     $results = $client->search($params);

// dd($results['aggregations'][2]['buckets'][0]);



        return view('admin.panelLogs.show', compact('zone','logs'));
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
