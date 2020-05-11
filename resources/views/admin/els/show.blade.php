@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
   
<p> </p>
<div style="float: right; padding-top: 4px;" class="col-lg-6 text-right" >
 <form method="post">
 {{csrf_field()}}


                <select style="width:200px;" class="select2 changeableSetting" id="minutes" name="minutes">
                 
                <option {{ $minutes == "60" ? "selected":"" }} value="60">Last 1 Hour</option>
                <option {{ $minutes == "360" ? "selected":"" }} value="360">Last 6 Hours</option>
                <option {{ $minutes == "720" ? "selected":"" }} value="720">Last 12 Hours</option>
         
                <option {{ $minutes == "1440" ? "selected":"" }} value="1440">Last 24 Hours</option>
                <option {{ $minutes == "10080" ? "selected":"" }} value="10080">Last 7 Days</option>
                <option {{ $minutes == "43200" ? "selected":"" }} value="43200">Last Month</option>
                
               
            </select>
        </form>
                  </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Enterprise Log Share Analysis for {{ $zone->name }}
        </div>

        <div class="panel-body table-responsive">

            <a target="_new" class="btn btn-primary" href="https://elasticsearch:ONiNeVB5NRDNo&F9@CgJAi7d@elastic.blockdos.net/"> Authenticate Kibana</a>
           <a class="btn btn-primary" href="https://elastic.blockdos.net/app/kibana#/dashboard/41c001f0-1256-11e8-ba2c-07becfd19594?_g=(refreshInterval:(display:Off,pause:!f,value:0),time:({{ $time }}))&_a=(description:'',filters:!(('$state':(store:appState),meta:(alias:!n,disabled:!f,index:'41228d70-122a-11e8-ba2c-07becfd19594',key:ZoneID,negate:!f,params:(query:{{ $zone->internalID }},type:phrase),type:phrase,value:'15,086,863'),query:(match:(ZoneID:(query:{{ $zone->internalID }},type:phrase))))),fullScreenMode:!f,options:(darkTheme:!f,hidePanelTitles:!f,useMargins:!t),panels:!((gridData:(h:3,i:'1',w:6,x:0,y:0),id:ca7ed530-1255-11e8-ba2c-07becfd19594,panelIndex:'1',type:visualization,version:'6.2.1'),(gridData:(h:3,i:'2',w:6,x:6,y:0),id:'05c11ef0-1256-11e8-ba2c-07becfd19594',panelIndex:'2',type:visualization,version:'6.2.1'),(gridData:(h:3,i:'3',w:6,x:0,y:3),id:'29e4ad60-1256-11e8-ba2c-07becfd19594',panelIndex:'3',type:visualization,version:'6.2.1'),(gridData:(h:3,i:'4',w:6,x:6,y:3),id:f49ae470-129c-11e8-ba2c-07becfd19594,panelIndex:'4',type:visualization,version:'6.2.1'),(gridData:(h:3,i:'5',w:6,x:0,y:6),id:b7726bf0-1354-11e8-ba2c-07becfd19594,panelIndex:'5',type:visualization,version:'6.2.1'),(gridData:(h:3,i:'6',w:6,x:6,y:6),id:'95c00e30-129c-11e8-ba2c-07becfd19594',panelIndex:'6',type:visualization,version:'6.2.1'),(embeddableConfig:(spy:!n,vis:(params:(sort:(columnIndex:0,direction:!n)))),gridData:(h:4,i:'7',w:12,x:0,y:9),id:'5b021090-1355-11e8-ba2c-07becfd19594',panelIndex:'7',type:visualization,version:'6.2.1')),query:(language:lucene,query:''),timeRestore:!f,title:'Main+Dashboard',viewMode:view)" target="_new"> Open Logs in Kibana </a> 
            
        </div>
    </div>





<div class="panel panel-default">
        <div class="panel-heading">
            View Request Details for RAYID
        </div>

        <div class="panel-body table-responsive">
            <form id="rayidForm">
                
            
            <input type="text" name="rayid" id="rayid">

            <input type="submit" name="" value="Open RayID in new tab">


            <input type="hidden" id="url" value="https://elastic.blockdos.net/app/kibana#/dashboard/bc4c4cb0-1564-11e8-ba2c-07becfd19594?_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-5y,mode:quick,to:now))&_a=(description:'',filters:!(('$state':(store:appState),meta:(alias:!n,disabled:!f,index:'41228d70-122a-11e8-ba2c-07becfd19594',key:RayID,negate:!f,params:(query:'RAYIDHERE',type:phrase),type:phrase,value:'RAYIDHERE'),query:(match:(RayID:(query:'RAYIDHERE',type:phrase))))),fullScreenMode:!f,options:(darkTheme:!f,hidePanelTitles:!f,useMargins:!t),panels:!((gridData:(h:5,i:'1',w:12,x:0,y:0),id:b8a56c20-1561-11e8-ba2c-07becfd19594,panelIndex:'1',type:visualization,version:'6.2.1')),query:(language:lucene,query:''),timeRestore:!f,title:RayID,viewMode:view)" name="">
            </form>

        </div>
    </div>




<div class="panel panel-default">
        <div class="panel-heading">
            View WAF Rule Details
        </div>

        <div class="panel-body table-responsive">
            <form id="wafruledetailsForm">
                
            
            <input type="text" name="rulid" id="rulid">

            <input type="submit" name="" value="View WAF Rule Details">


            
            </form>

            <div class="row">
                    <div class="col-lg-12" id="wafruldetails"></div>
            </div>
        </div>
    </div>
username:   elasticsearch
<br>
Password: ONiNeVB5NRDNo&F9@CgJAi7d
    



    <div class="panel panel-default">
        <div class="panel-heading">
           Custom Logs Processing
        </div>

        <div class="panel-body table-responsive">

            <table class="table table-bordered table-striped {{ count($zone->elsLog) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                      
                        <th>Time</th>
                        <th>IndexName</th>
                        

                    </tr>
                </thead>
                
                <tbody>

                   
                    @if (count($zone->elsLog) > 0)
                      @foreach($zone->elsLog as $elslog)

                        
                            <tr >

                                 <td>{{ $elslog->CreatedOn }}</td>
                                <td>

                                    <a target="_new" href="https://elastic.blockdos.net/app/kibana#/dashboard/8c8aa4e0-1ca8-11e8-bc70-db1e73b4258d?_g=(refreshInterval:('$$hashKey':'object:185',display:'10+seconds',pause:!f,section:1,value:10000),time:(from:now-7d,mode:quick,to:now))&_a=(description:'',filters:!(('$state':(store:appState),meta:(alias:!n,disabled:!f,index:ca515b60-1bff-11e8-bc70-db1e73b4258d,key:BucketFilter,negate:!f,params:(query:{{ $elslog->index }},type:phrase),type:phrase,value:{{ $elslog->index }}),query:(match:(BucketFilter:(query:{{ $elslog->index }},type:phrase))))),fullScreenMode:!f,options:(darkTheme:!f,hidePanelTitles:!f,useMargins:!t),panels:!((gridData:(h:3,i:'1',w:6,x:0,y:0),id:'775012e0-1ca8-11e8-bc70-db1e73b4258d',panelIndex:'1',type:visualization,version:'6.2.1')),query:(language:lucene,query:''),timeRestore:!f,title:'Custom+Analysis',viewMode:view)">{{ $elslog->index }}</a></td>



                            </tr>

                         
                        
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
           
                

            <form enctype="multipart/form-data" method="post" action="uploadCustomLog" id="customlogsform">
                
            {{ csrf_field() }}
            <input type="file" name="logs" id="logs">

            <input type="hidden" name="zid" id="zid" value="{{ $zone->id }}">

            <input type="submit" name="" value="Upload for Manual Processing">


            
            </form>

            <div class="row">
                    <div class="col-lg-12" id="wafruldetails"></div>
            </div>
        </div>
    </div>



                <form enctype="multipart/form-data" method="post" action="convertLogToApache" id="customlogsform">
                
            {{ csrf_field() }}
            <input type="file" name="logs" id="logs">

            <input type="hidden" name="zid" id="zid" value="{{ $zone->id }}">

            <input type="submit" name="" value="Convert to Apache Format">


            
            </form>

@stop

@section('javascript') 
    <script type="text/javascript">
        $(.sidebar-toggle).trigger('click');
    </script>
@endsection
