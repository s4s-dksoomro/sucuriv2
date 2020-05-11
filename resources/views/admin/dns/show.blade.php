@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app1')

@section('content')

<style>
    .note{
        display: none;

    }
    .note .note-info{
        display: none;
    }
</style>
<?php

$results=json_decode($ok);
//$messages = $result->messages;
 //print_r($result->output);  ?>
 @if(isset($message))
    <div class="alert alert-danger" role="alert">
        {{  $message }}
		<meta http-equiv = "refresh" content = "1; url = {{action('Admin\DnsController@index',Request::segment(2))}}" />

    </div>
    @endif
            {{-- For Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <h2 style="font-size: 20px !important;">Whitelist/Blacklist IP </h2>

	<div class="row" style="background: white;">
                <div class="col-xs-12" style="width: 100%;">
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 10px;"><h3>{{$results->output->domain}}</h3></div>
                        <div class="panel-body" >
  					<h3>Add BlackList/Whitelist IP</h3>
     
  					<form method="get"  role="form" action="/insertIp" onsubmit="myFunction()"  >      
              <input type="hidden" name="id" value=" <?php if(isset($message)){ } else { echo $id; } ?>">
  						<span>Add IP</span><br>
  						<input type="text" name="ip" required class="form-control" /><br>
  						<span>Select Whitelist Or Blacklist IP</span><br>
  						<select name="list" required class="form-control">
  							<option value="">Select IP List</option>
  							<option value="whitelist_ip">Whitelist IP</option>
  							<option value="blacklist_ip">Blacklist IP</option>
  						</select><br> 

  					<!-- 	<span>Enter Duration In Second (<sub>When you not enter duration so minimum time is 3 hours</sub>)</span> 
  						<input type="text" name="time" class="form-control" required="" /> <br> -->
  						<input type="submit" value="ADD" class="btn btn-primary" /><br><br>
					</form>                          

                            <h4>IPs</h4>
                            <table class="table table-bordered">
            <tbody>
                <tr>
                    
                    <td><b>White Listed IP Address:</b></td>
                    
                    

                    <td>

                        @foreach ($results->output->whitelist_list as $result)   
                        <form method="get" action="removewhite/rr">
                                <table >
                                    <td style="border:none;width: 200px;">
                                    <b>{{ $result }}</b>
                           

                        </td>
                        <input type="hidden" value="<?php echo auth()->user()->id; ?>" name = "id">

                        <input type="hidden" name = "remove" value="{{ $result }}">
                        <td style="border:none;width: 200px;">

                        <button type="submit" class="btn btn-xs btn-danger" name="submit"><i class="dripicons-trash"></i></button>
                    </td>
                </table>
                    </form>
                        @endforeach
                        
                        
                    </td>   
                    <?php
                    if(isset($_GET['submit'])){
                        $id= $_GET['id'];
                        $remove = $_GET['remove'];
                    }
                    ?>
                </tr>

                <tr>
                
                    <td><b>Black Listed IP Address:</b> </td>

                    <td>
                        {{-- <td> --}}
    
                            @foreach ($results->output->blacklist_list as $result)   
                            <form method="get" action="black/removeblack/rr">
                                <table >
                                    <td style="border:none;width: 200px;">
                                    <b>{{ $result }}</b>
                                    </td>
                            <input type="hidden" value="<?php echo auth()->user()->id; ?>" name = "id">
    
                            <input type="hidden" name = "remove" value="{{ $result }}">
                            <td style="border:none;width: 200px;">
                            
                            <button type="submit" class="btn btn-xs btn-danger" name="submit"><i class="dripicons-trash"></i></button>
                            </td>
                                </table>
                        </form>
                            @endforeach
                            
                            
                        </td>   
                        <?php
                        if(isset($_GET['submit'])){
                            $id= $_GET['id'];
                            $remove = $_GET['remove'];
                        }
                        ?>

                        {{-- @foreach ($results->output->blacklist_list as $result) <a href="black/{{ $result }}"> <b>  {{ print_r($result)}}</b></a> <br> @endforeach  --}}
                   {{-- </td> --}}
                </tr>
                
            </tbody>
        </table>

        

                        </div>
                    </div>
                </div>
            </div>









@stop

@section('javascript') 
<script>
    function myFunction() {
        alert("New IP has been added");
    }
</script>
@endsection
