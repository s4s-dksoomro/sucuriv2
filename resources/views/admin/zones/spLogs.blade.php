@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    



<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<div class="row">
                <div class="col-xs-12">
                    <h2>Logs Analysis</h2>
                    <h2 class="subtitle">View performance and security statistics for {{ $zone->name }}</h2>

                    <div class="section-title">
                        <div class="row">
                            <div class="col-xs-12 col-md-9">
                                <h3>Web Traffic</h3>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
                            </div>
                        </div>
                    </div>
                    


<script>
 // alert('{{ dateFormatting(\Carbon\Carbon::createFromTimestamp($start)->format("m/d/Y h:i A"),"logsAnalysis") }}');
$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left',
    timePicker: true,
     
    showDropdowns: true,
    @if($convert)
     startDate: '{{  dateFormatting(\Carbon\Carbon::createFromTimestamp($start)->format("m/d/Y h:i A"),"logsAnalysis") }}',
    endDate: '{{  dateFormatting(\Carbon\Carbon::createFromTimestamp($end)->format("m/d/Y h:i A"),"logsAnalysis") }}',
    @else

       startDate: '{{ \Carbon\Carbon::createFromTimestamp($start)->format("m/d/Y h:i A") }}',
    endDate: '{{  \Carbon\Carbon::createFromTimestamp($end)->format("m/d/Y h:i A") }}',

    @endif
    locale: {
      format: 'M/DD/YYYY hh:mm A'
}
  }, function(start, end, label) {

// alert(start.format('YYYY-MM-DD hh:mm'));

window.location.replace("logs?start="+start.format('YYYY-MM-DD hh:mmA')+"&end="+end.format('YYYY-MM-DD hh:mmA'));

 //    $.post( window.location.href, { }, function( data ) {
 
 // alert("sd")
 //    });
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>


                </div>
            </div>


  <div class="panel panel-default panel-main">
      <div class="panel-heading"><h2 style="display: inline">Access Logs </h2>
    
  </div>


      <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >

        <div class="panel-body table-responsive">
      

            <table class="table table-striped table-condensed firewallEventsSp">

                <thead>
                    <tr>
                        <th> Scheme</th>

                        <th>Method</th>
                        <th >URI</th>
                        <th>IP</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>

                <tbody>
                    @if (count($requests) > 0)
                        @foreach ($requests as $request)
                            <tr id="record_{{ $request['_id'] }}" data-entry-id="{{ $request['_id'] }}">
                                <td>{{ $request['_source']['scheme'] }}</td>

                                <td>
                                  {{ $request['_source']['method'] }}
                                </td>

                                <td>
                                  {{ $request['_source']['uri'] }}
                                </td>

                                <td>
                                  {{ $request['_source']['client_ip'] }}
                                </td>
                                <td>
                                  {{ $request['_source']['status'] }}
                                </td>
                                
                                <td>
                                   {{ dateFormatting( \Carbon\Carbon::parse($request['_source']['time'])->format("Y-m-d H:i:s"),"Formated") }}
                                </td>
                                <td>
                                  {{$request['_source']['client_ip'] }}
                                </td>
                                <td>
                                    
                                    <button data-rulename="{{ $request['_source']['client_ip'] }}" 
                                       data-date="{{$request['_source']['time'] }}" 
                                        data-action="{{ $request['_source']['client_ip'] }}" 
                                         data-schememethod="{{ $request['_source']['scheme'] }} {{ $request['_source']['method'] }}" 
                                          data-uri="{{ $request['_source']['uri'] }}" 
                                           data-querystring="{{$request['_source']['query_string'] }}" 
                                            data-domain="{{ $request['_source']['hostname'] }}" 
                                             data-clientip="{{ $request['_source']['client_ip'] }}" 
                                              data-country="{{ $request['_source']['client_country'] }}" 
                                               data-useragent="@if($request['_source']['user_agent']!="") {{ $request['_source']['user_agent'] }} @endif" 
                                         
                                              class="btn btn-info eventDetail">Details</button>

                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">No Access logs found for selected date range</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>


<div class="modal" id="eventModal" data-reveal>

   <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Event Details</h4>
      </div>
      <div class="modal-body">
      <div class="row">
        
       
      

      </div>

      <div style="margin-top: 10px" class="row">
        
        <div class="col-lg-5">
          <label id="schememethod"></label>
         <div id="domain"></div>
        </div>
        <div class="col-lg-4">
          <label>URI</label>
         <div id="uri"></div>
        </div>
        <div class="col-lg-3">
           <label>Query String</label>
         <div id="querystring"></div>

        </div>

      </div>


      <div style="margin-top: 10px" class="row">
        
        <div class="col-lg-5">
          <label>Client IP</label>
         <div id="clientip"></div>
        </div>
        <div class="col-lg-4">
          <label>Country</label>
         <div id="country"></div>
        </div>
          <div class="col-lg-3">
          <label>Date</label>
         <div id="date"></div>
        </div>
        
       

      </div>
  <div style="margin-top: 10px" class="row">
       <div class="col-lg-12">
           <label>User Agent</label>
         <div id="useragent"></div>

        </div>
      </div>

      <div style="padding-top: 20px;" class="row">
    
        </div>
</div></div>

</div>
</div>



@stop

@section('javascript') 
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
