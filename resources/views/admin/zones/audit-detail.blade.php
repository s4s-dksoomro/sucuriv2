@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    

        @if (!empty($audit))
  <div class="alert alert-success alert-dismissable custom-success-box" style="">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$audit}} </strong>
  </div>
@endif
    
    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

<!-- Modal -->
<div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Request Summary</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 
        <p><b>Blocking Reason: </b> <span id="blocking" class="btn btn-primary"></span></p> <hr>
       <div class="row">
            <div class="col-sm-4">
                <p><b>Resource Path: </b></p>
            </div>
            <div class="col-sm-8"> 
                 <button style="border-top-left-radius:180px; border-bottom-left-radius:180px; background-color: green; color: white; border: none !important;">Whitelist</button><button style="background-color: red; color: white; border-top-right-radius:180px; border-bottom-right-radius:180px; border: none !important;">Blacklist</button>
                   <span id="path"></span>
            </div>
        </div>
<hr>
<div class="row">
            <div class="col-sm-4">
                <p><b>IP Address :</b></p>
            </div>
            <div class="col-sm-8"> 
                 <button style="border-top-left-radius:180px; border-bottom-left-radius:180px; background-color: green; color: white; border: none !important;">Whitelist</button><button style="background-color: red; color: white; border-top-right-radius:180px; border-bottom-right-radius:180px; border: none !important;">Blacklist</button>
                   <span id="ip_a"></span>
            </div>
        </div> <hr>
        <!-- <p><b>IP Address: </b><span class="btn btn-success">Whitelist</span><span class="btn btn-danger">Blacklist</span> <span id="ip_a"></span></p> <hr> -->
        <p><b>Reverse IP: </b> <span id="ip_r"></span></p> <hr>
        <p><b>Request Method: </b> <span id="request"></span></p> <hr>
        <p><b>HTTP Protocol: </b> <span id="http_p"></span></p> <hr>
        <p><b>HTTP Status: </b> <span id="http_s"></span></p> <hr>
        <p><b>HTTP Referer: </b> <span id="http_r"></span></p> <hr>
        <p><b>HTTP User-Agent: </b> <span id="http_u"></span></p> <hr>
        <p><b>Date/Time (GMT): </b> <span id="date"></span></p> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($result) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        
                        <th>Event</th>
                        <th>Path</th>
                        <th>IP</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                
                <tbody>

                @for($i = 0 ; $i < count($result) ; $i++)
     
     
                            <tr>

                                <td onclick="popupData('<?=$result[$i]['sucuri_block_reason']?>','<?=$result[$i]['resource_path']?>','<?=$result[$i]['remote_addr']?>','','<?=$result[$i]['request_method']?>','<?=$result[$i]['http_protocol']?>','<?=$result[$i]['http_status']?>','<?=$result[$i]['http_referer']?>','<?=$result[$i]['http_user_agent']?>','<?= $result[$i]['request_date'] ?> <?=$result[$i]['request_time'];?>');">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#basicExampleModal"> <?php echo "<img src=\'\/static\/images\/blank.png\'>" ?> {{ $result[$i]['sucuri_block_reason'] }}</button></td>
                                <td>{{ $result[$i]['resource_path'] }}</td>
                                <td> <p data-toggle="modal" data-target="#basicExampleModal" style="cursor: pointer;">{{ $result[$i]['remote_addr'] }} </p></td>
                                <td><p data-toggle="modal" data-target="#basicExampleModal" style="cursor: pointer;"> {{ $result[$i]['request_date'] }} {{  $result[$i]['request_time'] }}  {{ $result[$i]['request_timezone']}}</p></td>

                            </tr>
                            @endfor

                       
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('javascript') 

@endsection
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    
    function popupData(blocking, path , ip , reserve , request , http_p,http_s ,http_r , http_u,date ) {
        // body...
        
        $('#blocking').text(blocking);
        $('#path').text(path);
        $('#ip_a').text(ip);
        $('#ip_r').text(reserve);
        $('#request').text(request);
        $('#http_p').text(http_p);
        $('#http_s').text(http_s);
        $('#http_r').text(http_r);
        $('#http_u').text(http_u);
        $('#date').text(date);
        
    }

</script>
