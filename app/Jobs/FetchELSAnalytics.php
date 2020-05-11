<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\ElsAnalytics;

use Carbon\Carbon;


class FetchELSAnalytics implements ShouldQueue
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

        $this->fetchAndSave(1440);
        $this->fetchAndSave(10080);
        $this->fetchAndSave(43200);
         if($this->zone->plan=="enterprise" OR $this->zone->plan=="pro")
        {
        $this->fetchAndSave(720);
        $this->fetchAndSave(360);
        }
        if($this->zone->plan=="enterprise")
        {
            
            $this->fetchAndSave(30);
        }


         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-30); // last 30 minutes // Enterprise
         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-360); // last 6 hours // Pro plan
         //$analytics = $zones->getZoneAnalytics($zone->zone_id,-720); // last 12 hours // Pro plan
         
         // last 24 hours (24 records)

        //$analytics = $zones->getZoneAnalytics($zone->zone_id,-43200); // last 30 days (30 records)
        //$analytics = $zones->getZoneAnalytics($zone->zone_id,-10080); // last 7 Days (7 records)

       


        //$analytics=

       
        

        
    }

    public function fetchAndSave($minutes)
    {

        

         $current_time=Carbon::now('UTC');

        $check['zone_id'] = $this->zone->id;
        
        $check['minutes']   = $minutes;

if($this->zone->internalID=="")
{
    $internalID=0;
}
else{
    $internalID=$this->zone->internalID;
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


$check['type']   = 'deviceType';
        $values['value'] = serialize($deviceType);
        ElsAnalytics::updateOrCreate($check, $values);



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
   
$check['type']   = 'hosts';
        $values['value'] = serialize($hosts);
        ElsAnalytics::updateOrCreate($check, $values);

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

$check['type']   = 'referers';
        $values['value'] = serialize($referers);
        ElsAnalytics::updateOrCreate($check, $values);

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

$check['type']   = 'clientsIP';
        $values['value'] = serialize($clientsIP);
        ElsAnalytics::updateOrCreate($check, $values);



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



$check['type']   = 'ClientRequestURI';
        $values['value'] = serialize($ClientRequestURI);
        ElsAnalytics::updateOrCreate($check, $values);


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







        $check['type']   = 'ClientRequestMethod';
        $values['value'] = serialize($ClientRequestMethod);
        ElsAnalytics::updateOrCreate($check, $values);




        $body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "ClientRequestUserAgent.keyword",
        "size": 1000,
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
  $ClientRequestUserAgent=$results['aggregations'][2]['buckets'];
} catch (Exception $e) {
  $ClientRequestUserAgent=[];
}







        $check['type']   = 'ClientRequestUserAgent';
        $values['value'] = serialize($ClientRequestUserAgent);
        ElsAnalytics::updateOrCreate($check, $values);


                $body='{
  "size": 0,
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "terms": {
        "field": "WAFRuleID.keyword",
        "size": 10,
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
  $WAFRuleID=$results['aggregations'][2]['buckets'];
} catch (Exception $e) {
  $WAFRuleID=[];
}







        $check['type']   = 'WAFRuleID';
        $values['value'] = serialize($WAFRuleID);
        ElsAnalytics::updateOrCreate($check, $values);

    }
}
