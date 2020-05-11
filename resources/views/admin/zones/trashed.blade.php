@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app1')

@section('content')
    <style>
	i{
	margin-top: 12px !important;
  }
	</style>
<?php
function cleanString($string)
              {
                // allow only letters
                $res = preg_replace("/[^a-zA-Z]/", "", $string);

                
                return $res;
              }
$date = "";
?>


        @if (!empty($audit))
  <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$audit}} </strong>
  </div>
@endif
    
    <div class="panel panel-default" style="background: white;">
        <div class="panel-heading">
           <h2 style="font-size: 20px !important; padding: 10px;">AUDIT TRAILS</h2>
        </div>
		<div style = "width:100%;margin-top: 100px;">
		<div style = "float:left;padding: 50px; color: blue;"><h4 style="color: black;"><b>Current Monitoring View</b></h4> <hr>
		<br><br>
		<b>TOTAL REQUESTS</b> 
    <br>@for($i = 0 ; $i < count($result) ; $i++)
    <?php $counterBlue = 0;?>
                

          
  <?php 
  
  if($result[$i]['sucuri_block_reason'] == "DDOS attempt blocked" ){
    $counterBlue++;
    $color = "style = 'background-color: #0C99DD;'";
    }			
    elseif($result[$i]['sucuri_block_reason'] == "Access denied - Site in lock down") {
      $color = "style = 'background-color: #FA9839;'";
    }
    elseif($result[$i]['sucuri_block_reason'] == "Evasion attempt denied") {
      $color = "style = 'background-color: #8EF969;'";
    }
    elseif($result[$i]['sucuri_block_reason'] == "Request not authorized") {
      $color = "style = 'background-color: #9E62D9'";
    }
    elseif($result[$i]['sucuri_block_reason'] == "Directory listing not authorized") {
      $color = "style = 'background-color: #B18957;'";
    }
    elseif($result[$i]['sucuri_block_reason'] == "Bad bot access denied") {
      $color = "style = 'background-color: #CE97C3'";
    }
    else {
      $color = "style = 'background-color: red'";
    }
  
  
  $country_code = htmlentities($result[$i]['request_country_code']);
  $domain_id = $id;
  $event = $result[$i]['sucuri_block_reason'];
  $path = $result[$i]['resource_path'];
  $ip = $result[$i]['remote_addr'];
  $time = $result[$i]['request_time'];
  
  // $dk = cleanString($result[$i]['request_date']);
  // $d = strtotime($dk);
  // $date1 = date('Y/m/d', $d);
  $date = $result[$i]['request_date'];
  
  $timezone = $result[$i]['request_timezone'];
  // reverse_ip
  // $country_code

  $request_method = $result[$i]['request_method'];
  $http_protocol = $result[$i]['http_protocol'];
  $http_status = $result[$i]['http_status'];
  $http_referer = $result[$i]['http_referer'];
  $http_useragent = $result[$i]['http_user_agent'];
  


  

$servername = env('DB_HOST');
              $username = env('DB_USERNAME');
              $password = env('DB_PASSWORD');
              $dbname = env('DB_DATABASE');

              $conn = new mysqli($servername, $username, $password, $dbname);
    
              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
  $query1 = "SELECT * FROM audit_trails WHERE domain_id = $domain_id  and time = '$time' and ip = '$ip' ";
  $result1 = mysqli_query($conn, $query1);
  $check1 = mysqli_num_rows($result1);
  if($check1==1){
    echo "";
  }else{
    $data=array('domain_id'=>$domain_id,'event'=>$event,"path"=>$path,"ip"=>$ip,"date"=>$date,"time"=>$time, "timezone"=>$timezone, "request_method"=>$request_method, "http_protocol"=>$http_protocol, "http_status"=>$http_status, "http_referer"=>$http_referer, "http_useragent"=>$http_useragent, "country_code"=> $country_code);
    DB::table('audit_trails')->insert($data);
  }


// die();
  ?>
                @endfor
                <?php $counterBlue = 0;?>
    <?php 
    $servername = env('DB_HOST');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');
    $dbname = env('DB_DATABASE');

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
    $query2 = "SELECT * FROM audit_trails WHERE date = '$date'";
              $result2 = mysqli_query($conn, $query2);
              $counter2 = 0;
              if (mysqli_num_rows($result2) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result2)) {
      $counter2 = $counter2 + 1;
    }
    }
    echo $counter2;
    
    ?>
		{{-- {{count($result)}} --}}
		</div>
			<div id="donutchart" style="width: 600px; height: 250px;float: right; "></div>
		</div>
<div style = "clear: both;"></div>
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
<hr>

        <div class="panel-body">
            <center>
           {!! Form::open(['action' => 'Admin\SucuriController@ok']) !!}
            
             <input type="hidden" name="id" value="<?=$id?>" />   
           
            <b>Choose Date: </b> <input style = "height: 30px;" type="date" name="date" class = "" />
                 <input type="submit" value="Search" class="btn btn-primary"  />
                 
           </form>
			<div style = "width: 90%;">
			
            <table id = "example" class="table table-bordered table-striped {{ count($result) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>  
                        <th>Event</th>
                        <th>Path</th>
                        <th>IP</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                
                
				
				
				
							
        
          <tbody>
            <tr>
          <?php
                        $query2 = "SELECT * FROM audit_trails WHERE date = '$date'";
              $result2 = mysqli_query($conn, $query2);
              
              if (mysqli_num_rows($result2) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result2)) {
      if($row['event'] == "DDOS attempt blocked" ){
								$counterBlue++;
								$color = "style = 'background-color: #0C99DD;'";
								}			
								elseif($row['event'] == "Access denied - Site in lock down") {
									$color = "style = 'background-color: #FA9839;'";
								}
								elseif($row['event'] == "Evasion attempt denied") {
									$color = "style = 'background-color: #8EF969;'";
								}
								elseif($row['event'] == "Request not authorized") {
									$color = "style = 'background-color: #9E62D9'";
								}
								elseif($row['event'] == "Directory listing not authorized") {
									$color = "style = 'background-color: #B18957;'";
								}
								elseif($row['event'] == "Bad bot access denied") {
									$color = "style = 'background-color: #CE97C3'";
								}
								else {
									$color = "style = 'background-color: red'";
								}
              
              
              // $blocked = count($result)- $counterBlue;
      ?>
      {{-- echo "id: " . $row["id"]."<br>"; --}}
        
      <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.3.1/css/flag-icon.min.css" rel="stylesheet"/>
      
              <td onclick="popupData('<?=$row['event']?>','<?=$row['path']?>','<?=$row['ip']?>','','<?=$row['request_method']?>','<?=$row['http_protocol']?>','<?=$row['http_status']?>','<?=$row['http_referer']?>','<?=$row['http_useragent']?>','<?= $row['date'] ?> <?=$row['time'];?>');">
              <button <?php echo $color; ?> type="button" class="btn btn-primary" data-toggle="modal" data-target="#basicExampleModal"> <?php echo "<span class='flag-icon flag-icon-".$row['country_code']."'></span>"; ?> {{ $row['event'] }}</button></td>
              <?php 
                $str = ""; 
                if(strlen($row['path']) > 31 ){
                  $str = 'style = "font-size: 10px !important;"';
                }else{
                  $str = "";
                }
							?>
              <td <?php echo $str; ?> > <?php  echo $row['path']; ?> </td>
              <td> <p data-toggle="modal" data-target="#basicExampleModal" style="cursor: pointer;">{{ $row['ip'] }} </p></td>
              <td><p data-toggle="modal" data-target="#basicExampleModal" style="cursor: pointer;"> {{ $row['date'] }} {{  $row['time'] }}  {{ $row['timezone']}}</p></td>
            </tr>
            <?php
    }
} else {
    echo "0 results";
}


$blocked = $counter2 - $counterBlue;
//$blocked = $counter2 ;

?>

                       
            </tbody>
        </table>
  </center>
  </div></center>
    </div>
</div>

@stop

@section('javascript') 

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    
    function popupData(blocking, path, ip, reserve, request, http_p, http_s, http_r, http_u, date ) {
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
    $(document).ready(function() {
    $('#example').DataTable( {
        "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]]
    } );
} );
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Allowed',     {{$counterBlue}}],
          ['Blocked',      {{$blocked}}],
          ['IPs',      {{ $counter2 }}],
          ['Events',      {{$counter2}}],
          ['Request Method',      {{$counter2}}],
          ['HTTP Protocol',      {{$counter2}}],
          ['HTTP 403',      {{$counter2}}],
          ['HTTP Referer',      {{$counter2}}],
          ['HTTP User-Agent',      {{$counter2}}],
        ]);

        var options = {
          title: '',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
