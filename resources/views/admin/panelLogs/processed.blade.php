@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
   
<p> </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            Custom Log File Processing
        </div>

        <div class="panel-body table-responsive">

           <a target="_blank" href="https://elastic.blockdos.net/app/kibana#/dashboard/8c8aa4e0-1ca8-11e8-bc70-db1e73b4258d?_g=(refreshInterval:('$$hashKey':'object:185',display:'10+seconds',pause:!f,section:1,value:10000),time:(from:now-7d,mode:quick,to:now))&_a=(description:'',filters:!(('$state':(store:appState),meta:(alias:!n,disabled:!f,index:ca515b60-1bff-11e8-bc70-db1e73b4258d,key:BucketFilter,negate:!f,params:(query:{{ $elsLog->index }},type:phrase),type:phrase,value:{{ $elsLog->index }}),query:(match:(BucketFilter:(query:{{ $elsLog->index }},type:phrase))))),fullScreenMode:!f,options:(darkTheme:!f,hidePanelTitles:!f,useMargins:!t),panels:!((gridData:(h:3,i:'1',w:6,x:0,y:0),id:'775012e0-1ca8-11e8-bc70-db1e73b4258d',panelIndex:'1',type:visualization,version:'6.2.1')),query:(language:lucene,query:''),timeRestore:!f,title:'Custom+Analysis',viewMode:view)">Log File Processed, Click here to open it in Kibana</a>
            
        </div>
    </div>







   
@stop

@section('javascript') 
    <script type="text/javascript">
        $(.sidebar-toggle).trigger('click');
    </script>
@endsection
