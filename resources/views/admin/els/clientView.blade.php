@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
   
<p> </p>
<div class="row">
<div style="float: right; padding-top: 4px; padding-bottom: 25px;" class="col-lg-6 text-right" >
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

              </div>




  <div class="row customAnalytics" >
  @if(count($deviceType)>0)
  <div class="col-sm-4">
<div class="panel panel-default">
    <div class="panel-heading"><strong>Device Type</strong><br>
       </div>
    <div class="panel-body">
        <div id="deviceType" style="height: 200px;"></div>
    </div>
</div>
</div>
@endif



<div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Top 5 Hosts</strong><br></div>
            <div class="panel-body">
                <table class="table table-responsive table-striped">
                @if(count($hosts)>0) 
                    <thead>
                        <th>Host</th>
                        <th>Requests</th>
                    </thead>
                    <tbody>
                        
                            @foreach ($hosts as $host)  
                      
                            <tr>
                                <td>{{ $host['key'] }}</td>
                                <td>{{ $host['doc_count'] }}</td>
                            </tr>
                        @endforeach
                        @else 
                       No Hosts Found
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Top 5 Referers</strong><br></div>
            <div class="panel-body">
                <table class="table table-responsive table-striped">
                @if(count($referers)>0) 
                    <thead>
                        <th>Referer</th>
                        <th>Requests</th>
                    </thead>
                    <tbody>
                        
                            @foreach ($referers as $referer)  
                      
                            <tr>
                                <td>{{ $referer['key']=="" ? "Direct Traffic" : $referer['key']  }}</td>
                                <td>{{ $referer['doc_count'] }}</td>
                            </tr>
                        @endforeach
                        @else 
                       No Hosts Found
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Top 5 Ip Addreses</strong><br></div>
            <div class="panel-body">
                <table class="table table-responsive table-striped">
                @if(count($clientsIP)>0) 
                    <thead>
                        <th>Client IP</th>
                        <th>Requests</th>
                    </thead>
                    <tbody>
                        
                            @foreach ($clientsIP as $clientip)  
                      
                            <tr>
                                <td>{{ $clientip['key'] }}</td>
                                <td>{{ $clientip['doc_count'] }}</td>
                            </tr>
                        @endforeach
                        @else 
                       No Hosts Found
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Most Requested URI</strong><br></div>
            <div class="panel-body">
                <table class="table table-responsive table-striped">
                @if(count($ClientRequestURI)>0) 
                    <thead>
                        <th>URI</th>
                        <th>Requests</th>
                    </thead>
                    <tbody>
                        
                            @foreach ($ClientRequestURI as $uri)  
                      
                            <tr>
                                <td>{{ $uri['key'] }}</td>
                                <td>{{ $uri['doc_count'] }}</td>
                            </tr>
                        @endforeach
                        @else 
                       No URI Found
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Most Used Request Method</strong><br></div>
            <div class="panel-body">
                <table class="table table-responsive table-striped">
                @if(count($ClientRequestMethod)>0) 
                    <thead>
                        <th>Request Method</th>
                        <th>Requests</th>
                    </thead>
                    <tbody>
                        
                            @foreach ($ClientRequestMethod as $uri)  
                      
                            <tr>
                                <td>{{ $uri['key'] }}</td>
                                <td>{{ $uri['doc_count'] }}</td>
                            </tr>
                        @endforeach
                        @else 
                       No Client Request Method Found
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style type="text/css">
    .customAnalytics .col-sm-4 .panel-body
    {
        min-height: 340px;
    }
</style>
@stop

@section('javascript') 
<script src="https://www.google.com/jsapi"></script>
<script src="{{ url('js/jquery.circliful.min.js') }}"></script>
<script src="{{ url('js/chartkick.js') }}"></script>
    <script type="text/javascript">
         new Chartkick.PieChart("deviceType", <?php echo json_encode($deviceType); ?>);
    </script>
@endsection
