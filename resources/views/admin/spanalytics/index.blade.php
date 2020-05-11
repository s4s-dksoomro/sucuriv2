@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
   

<div class="row">
                <div class="col-xs-12">
                    <h2>Analytics</h2>
                    <h2 class="subtitle">View performance and security statistics for {{ $zone->name }}</h2>
<div class="section-title">
                        <div class="row">
                            <div class="col-xs-12 col-md-9">
                                <h3>Web Traffic</h3>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <form method="post">
 {{csrf_field()}}


                <select  class="select2 form-control " id="minutes" name="minutes">
                    @if($zone->plan=="enterprise")
                <option {{ $minutes == "30" ? "selected":"" }} value="30">Last 30 Minutes</option>
                <option {{ $minutes == "360" ? "selected":"" }} value="360">Last 6 Hours</option>
                <option {{ $minutes == "720" ? "selected":"" }} value="720">Last 12 Hours</option>
                @endif
                <option {{ $minutes == "1440" ? "selected":"" }} value="1440">Last 24 Hours</option>
                <option {{ $minutes == "10080" ? "selected":"" }} value="10080">Last 7 Days</option>
                <option {{ $minutes == "43200" ? "selected":"" }} value="43200">Last Month</option>

                <option {{ $minutes == "259200" ? "selected":"" }} value="259200">Last 6 Months</option>
                
               
            </select>
                  </form>
                        </div>
                    </div>
</div>
<div class="panel panel-white">
                        <div class="tabbable full-width-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#requests" data-toggle="tab">Requests</a></li>
                                <li><a href="#bandwidth" data-toggle="tab">Bandwidth</a></li>
                               
                                <li><a href="#http_statuses" data-toggle="tab">Status</a></li>
                                <li><a href="#threats" data-toggle="tab">Threats</a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="requests">
                                     <h2>Requests Served</h2>
            <div class="row">
            <div class="col-lg-4 ">
            <div class="panel">
                <p class="boxheading"><b>Total Requests</b></p>
                <p><?=$timestamp;?></p>
                <p class="num"><strong><?=number_format($request_all); ?></strong></p>
                </div>
            </div>
            <div class="col-lg-4 ">
            <div class="panel">
                <p class="boxheading"><b>Cached Requests</b></p>
                <p><?=$timestamp;?></p>
                <p class="num"><strong><?=number_format($request_cached);?></strong></p>
            </div>
                        </div>

            <div class="col-lg-4 ">
            <div class="panel">
                <p class="boxheading"><b>Uncached Requests</b></p>
                <p><?=$timestamp;?></p>
                <p class="num"><strong><?=number_format($request_uncached);?></strong></p>
            </div>
                        </div>

        </div>




          <div id="request-graph" class="graphbox" style="height: 230px;"></div>
            




        
                                </div>


                                <div class="tab-pane" id="bandwidth">
                                  <h2>Bandwidth</h2>
            <div class="row">
             <div class="col-lg-4 ">
            <div class="panel">
                <p class="boxheading"><b>Bandwidth Used</b></p>
                <p><?=$timestamp;?></p>
                <p class="num"><strong><?=$bandwidth; ?> GB</strong></p>
                </div>
            </div>
            <div class="col-lg-4 ">
           
                        </div>

            <div class="col-lg-4 ">
            
                        </div>

        </div>

          <div id="bandwidth-graph" class="graphbox"  style="height: 230px;">
          </div>
                                </div>
                                
                                <div class="tab-pane" id="http_statuses">
                                    <h2>Status Codes</h2>
            <div class="row">
            

        </div>
           
            <div id="statuses-graph" class="graphbox" style="height: 230px;">@if(count($status_codes)==0) <span> No Status Codes data for the selected time period</span>@endif</div>

                                </div>
                                <div class="tab-pane" id="threats">
                                   
                                      <h2>Threats</h2>
            <div class="row">
            

        </div>
           
            <div id="threats-graph" class="graphbox" style="height: 230px;">@if(count($threats)==0) <span> No Threats for the selected time period</span>@endif</div>
                                </div>
                            </div>
                        </div>
                    </div>
  
       

<div class="clear-fix">&nbsp;</div>
<div class="panel panel-default">
    <div class="panel-heading"><h4>Threat Sources<i class="fa fa-line-chart pull-left"></i></h4>{{$timestamp}}</div>
    <div class="panel-body"><div id="countries_container" style="max-width:100%;"></div></div>
</div>
<div class="clear-fix">&nbsp;</div>


</div></div>



<script src="https://www.google.com/jsapi"></script>
<script src="{{ url('js/jquery.circliful.min.js') }}"></script>
<script src="{{ url('js/chartkick.js') }}"></script>
   <script src="https://code.highcharts.com/maps/highmaps.js"></script>
        <script src="https://code.highcharts.com/maps/modules/data.js"></script>
        <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/mapdata/custom/world.js"></script>
    <script src="{{ url('js/raphael-min.js') }}"></script>
    <script src="{{ url('morris/morris.min.js') }}"></script>

    <script src="{{ url('easypiechart/jquery.easy-pie-chart.js') }}"></script>


     
        <!-- jquery ui -->
    <script type="text/javascript">

    $(document).ready(function(){
    requests = Morris.Area({
        element: 'request-graph',
        data: <?php echo json_encode($stats); ?>,
        xkey: 'period',
        parseTime: false,
        xLabels: "<?php echo $timestamp; ?>",
        ykeys: ['cached', 'uncached'],
        labels: ['Cached', 'Un-Cached'],
        lineColors: ['#12A6DA','#222D32'],
        
        resize: true
    });

     bandwidth = Morris.Area({
        element: 'bandwidth-graph',
        data: <?php echo json_encode($stats); ?>,
        xkey: 'period',
        parseTime: false,
        xLabels: "<?php echo $timestamp; ?>",
        ykeys: ['size'],
        labels: ['Bandwidth'],
        lineColors: ['#12A6DA'],
        resize: true
    });   


        statuses=Morris.Area({
        element: 'statuses-graph',
        data: <?php echo json_encode($status_codes); ?>,
        xkey: 'timestamp',
        parseTime: false,
        xLabels: "<?php echo $timestamp; ?>",
        ykeys: ['2xx','3xx','4xx','5xx'],
        labels: ['2xx','3xx','4xx','5xx'],
        
        resize: true
      
    });

 @if(count($threats)>0)
    threats=Morris.Area({
        element: 'threats-graph',
        data: <?php echo json_encode($threats); ?>,
        xkey: 'period',
        parseTime: false,
        xLabels: "<?php echo $timestamp; ?>",
        ykeys: ['blocked'],
        labels: ['Threats Blocked']
    });

   @endif
    });





// $('a[data-toggle="tab"]').on('click',function(){


//     $(this).children(".loader:first").show();
//     alert($(this).children(".loader:first").html());

// })













$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
  var target = $(e.target).attr("href") // activated tab
  //alert($(this).children(".loader:first").css("display"));
  // $(this).children(".loader:first").show().delay(10000,function(){
  //     });
    //alert($(this).children(".loader:first").css("display"));
  //alert($(target).find('.graphbox').css('height'));
  if($(target).find('.graphbox').css('height')!="230px")
  {

    $(".loader").hide();
    return false;

  }
  
  // 


//   $('.graphbox').css('height','230px');

// bandwidth.redraw();
// visitors.redraw();
//   threats.redraw();
//   $(window).trigger('resize');

//     $('.graphbox').css('height','auto');
//     $(target).find('svg').attr('height','230');
//   return true;
  switch (target) {
    case "#requests":

      $(target).find('.graphbox').css('height','230px');
      requests.redraw();
      $(window).trigger('resize');
      $(target).find('.graphbox').css('height','auto');
       $(target).find('svg').attr('height','230');

      break;
    case "#bandwidth":

       $(target).find('.graphbox').css('height','230px');
      bandwidth.redraw();
        $(window).trigger('resize');

       $(target).find('.graphbox').css('height','auto');
       $(target).find('svg').attr('height','230');

      
      
      break;

      case "#visitors":

       $(target).find('.graphbox').css('height','230px');
      visitors.redraw();
      $(window).trigger('resize');

       $(target).find('.graphbox').css('height','auto');
       $(target).find('svg').attr('height','230');
      break;

      case "#threats":

       $(target).find('.graphbox').css('height','230px');
      threats.redraw();
      $(window).trigger('resize');

       $(target).find('.graphbox').css('height','auto');
       $(target).find('svg').attr('height','230');
      break;

       case "#http_statuses":

       $(target).find('.graphbox').css('height','230px');
      statuses.redraw();
      $(window).trigger('resize');

       $(target).find('.graphbox').css('height','auto');
       $(target).find('svg').attr('height','230');
      break;

  }


  $(".loader").hide();
});







$(document).ready(function(){
    $(function () {

    $.getJSON('{{ route('admin.analytics.countries', ['zone' => $zone->name, 'minutes' => $minutes]) }}', function (data) {

            // Initiate the chart
            $('#countries_container').highcharts('Map', {

                title : {
                    text : 'Threat Sources'
                },

                mapNavigation: {
                    enabled: true,
                    enableDoubleClickZoomTo: true
                },

                colorAxis: {
                    min: 1,
                    max: 1000,
                    type: 'logarithmic'
                },

                series : [{
                    data : data,
                    mapData: Highcharts.maps['custom/world'],
                    joinBy: ['iso-a2', 'code'],
                    name: 'Threats',
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    tooltip: {
                        valueSuffix: ' threats'
                    }
                }]
            });
        });
    });
    $(function () {
        $.getJSON('{{ route('admin.analytics.traffic', ['zone' => $zone->name, 'minutes' =>$minutes]) }}', function (data) {

            // Initiate the chart
            $('#traffic_container').highcharts('Map', {

                title : {
                    text : 'Traffic'
                },

                mapNavigation: {
                    enabled: true,
                    enableDoubleClickZoomTo: true
                },

                colorAxis: {
                    min: 1,
                    max: 100000,
                    type: 'logarithmic'
                },

                series : [{
                    data : data,
                    mapData: Highcharts.maps['custom/world'],
                    joinBy: ['iso-a2', 'code'],
                    name: 'Traffic',
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    tooltip: {
                        valueSuffix: ' visitors'
                    }
                }]
            });
        });
/**
 * Dark theme for Highcharts JS
 * @author Torstein Honsi
 */

// Load the fonts
Highcharts.createElement('link', {
   href: '//fonts.googleapis.com/css?family=Unica+One',
   rel: 'stylesheet',
   type: 'text/css'
}, null, document.getElementsByTagName('head')[0]);

Highcharts.theme = {
   colors: ["#2b908f", "#90ee7e", "#f45b5b", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
      "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
   chart: {
      backgroundColor: {
         linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
         stops: [
            [0, '#2a2a2b'],
            [1, '#3e3e40']
         ]
      },
      style: {
         fontFamily: "'Unica One', sans-serif"
      },
      plotBorderColor: '#606063'
   },
   title: {
      style: {
         color: '#E0E0E3',
         textTransform: 'uppercase',
         fontSize: '20px'
      }
   },
   subtitle: {
      style: {
         color: '#E0E0E3',
         textTransform: 'uppercase'
      }
   },
   xAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#E0E0E3'
         }
      },
      lineColor: '#707073',
      minorGridLineColor: '#505053',
      tickColor: '#707073',
      title: {
         style: {
            color: '#A0A0A3'

         }
      }
   },
   yAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#E0E0E3'
         }
      },
      lineColor: '#707073',
      minorGridLineColor: '#505053',
      tickColor: '#707073',
      tickWidth: 1,
      title: {
         style: {
            color: '#A0A0A3'
         }
      }
   },
   tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.85)',
      style: {
         color: '#F0F0F0'
      }
   },
   plotOptions: {
      series: {
         dataLabels: {
            color: '#B0B0B3'
         },
         marker: {
            lineColor: '#333'
         }
      },
      boxplot: {
         fillColor: '#505053'
      },
      candlestick: {
         lineColor: 'white'
      },
      errorbar: {
         color: 'white'
      }
   },
   legend: {
      itemStyle: {
         color: '#E0E0E3'
      },
      itemHoverStyle: {
         color: '#FFF'
      },
      itemHiddenStyle: {
         color: '#606063'
      }
   },
   credits: {
      style: {
         color: '#666'
      }
   },
   labels: {
      style: {
         color: '#707073'
      }
   },

   drilldown: {
      activeAxisLabelStyle: {
         color: '#F0F0F3'
      },
      activeDataLabelStyle: {
         color: '#F0F0F3'
      }
   },

   navigation: {
      buttonOptions: {
         symbolStroke: '#DDDDDD',
         theme: {
            fill: '#505053'
         }
      }
   },

   // scroll charts
   rangeSelector: {
      buttonTheme: {
         fill: '#505053',
         stroke: '#000000',
         style: {
            color: '#CCC'
         },
         states: {
            hover: {
               fill: '#707073',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            },
            select: {
               fill: '#000003',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            }
         }
      },
      inputBoxBorderColor: '#505053',
      inputStyle: {
         backgroundColor: '#333',
         color: 'silver'
      },
      labelStyle: {
         color: 'silver'
      }
   },

   navigator: {
      handles: {
         backgroundColor: '#666',
         borderColor: '#AAA'
      },
      outlineColor: '#CCC',
      maskFill: 'rgba(255,255,255,0.1)',
      series: {
         color: '#7798BF',
         lineColor: '#A6C7ED'
      },
      xAxis: {
         gridLineColor: '#505053'
      }
   },

   scrollbar: {
      barBackgroundColor: '#808083',
      barBorderColor: '#808083',
      buttonArrowColor: '#CCC',
      buttonBackgroundColor: '#606063',
      buttonBorderColor: '#606063',
      rifleColor: '#FFF',
      trackBackgroundColor: '#404043',
      trackBorderColor: '#404043'
   },

   // special colors for some of the
   legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
   background2: '#505053',
   dataLabelsColor: '#B0B0B3',
   textColor: '#C0C0C0',
   contrastTextColor: '#F0F0F3',
   maskColor: 'rgba(255,255,255,0.3)'
};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);
    });
/**
 * Dark theme for Highcharts JS
 * @author Torstein Honsi
 */

// Load the fonts
Highcharts.createElement('link', {
   href: '//fonts.googleapis.com/css?family=Unica+One',
   rel: 'stylesheet',
   type: 'text/css'
}, null, document.getElementsByTagName('head')[0]);

Highcharts.theme = {
   colors: ["#2b908f", "#90ee7e", "#f45b5b", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
      "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
   chart: {
      backgroundColor: {
         linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
         stops: [
            [0, '#2a2a2b'],
            [1, '#3e3e40']
         ]
      },
      style: {
         fontFamily: "'Unica One', sans-serif"
      },
      plotBorderColor: '#606063'
   },
   title: {
      style: {
         color: '#E0E0E3',
         textTransform: 'uppercase',
         fontSize: '20px'
      }
   },
   subtitle: {
      style: {
         color: '#E0E0E3',
         textTransform: 'uppercase'
      }
   },
   xAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#E0E0E3'
         }
      },
      lineColor: '#707073',
      minorGridLineColor: '#505053',
      tickColor: '#707073',
      title: {
         style: {
            color: '#A0A0A3'

         }
      }
   },
   yAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#E0E0E3'
         }
      },
      lineColor: '#707073',
      minorGridLineColor: '#505053',
      tickColor: '#707073',
      tickWidth: 1,
      title: {
         style: {
            color: '#A0A0A3'
         }
      }
   },
   tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.85)',
      style: {
         color: '#F0F0F0'
      }
   },
   plotOptions: {
      series: {
         dataLabels: {
            color: '#B0B0B3'
         },
         marker: {
            lineColor: '#333'
         }
      },
      boxplot: {
         fillColor: '#505053'
      },
      candlestick: {
         lineColor: 'white'
      },
      errorbar: {
         color: 'white'
      }
   },
   legend: {
      itemStyle: {
         color: '#E0E0E3'
      },
      itemHoverStyle: {
         color: '#FFF'
      },
      itemHiddenStyle: {
         color: '#606063'
      }
   },
   credits: {
      style: {
         color: '#666'
      }
   },
   labels: {
      style: {
         color: '#707073'
      }
   },

   drilldown: {
      activeAxisLabelStyle: {
         color: '#F0F0F3'
      },
      activeDataLabelStyle: {
         color: '#F0F0F3'
      }
   },

   navigation: {
      buttonOptions: {
         symbolStroke: '#DDDDDD',
         theme: {
            fill: '#505053'
         }
      }
   },

   // scroll charts
   rangeSelector: {
      buttonTheme: {
         fill: '#505053',
         stroke: '#000000',
         style: {
            color: '#CCC'
         },
         states: {
            hover: {
               fill: '#707073',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            },
            select: {
               fill: '#000003',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            }
         }
      },
      inputBoxBorderColor: '#505053',
      inputStyle: {
         backgroundColor: '#333',
         color: 'silver'
      },
      labelStyle: {
         color: 'silver'
      }
   },

   navigator: {
      handles: {
         backgroundColor: '#666',
         borderColor: '#AAA'
      },
      outlineColor: '#CCC',
      maskFill: 'rgba(255,255,255,0.1)',
      series: {
         color: '#7798BF',
         lineColor: '#A6C7ED'
      },
      xAxis: {
         gridLineColor: '#505053'
      }
   },

   scrollbar: {
      barBackgroundColor: '#808083',
      barBorderColor: '#808083',
      buttonArrowColor: '#CCC',
      buttonBackgroundColor: '#606063',
      buttonBorderColor: '#606063',
      rifleColor: '#FFF',
      trackBackgroundColor: '#404043',
      trackBorderColor: '#404043'
   },

   // special colors for some of the
   legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
   background2: '#505053',
   dataLabelsColor: '#B0B0B3',
   textColor: '#C0C0C0',
   contrastTextColor: '#F0F0F3',
   maskColor: 'rgba(255,255,255,0.3)'
};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);

});



    </script>

    


    
@stop

@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
