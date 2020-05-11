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
// $path=json_decode($path);
// echo $path;

//  print_r($path->id);
//  die();  ?>
 @if(isset($message))
    <div class="alert alert-danger" role="alert">
        {{  $message }}
		<meta http-equiv = "refresh" content = "1; url = {{action('Admin\DnsController@blockcookie',Request::segment(2))}}" />

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
                <h2 style="font-size: 20px !important;">Block Cookie </h2>

	<div class="row" style="background: white;">
                <div class="col-xs-12" style="width: 100%;">
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 10px;">
                            <h3>{{$results->output->domain}}</h3></div>
                        <div class="panel-body" >
                            <h3>Add Cookie</h3>
     
                            <form method="get"  class="form-inline" role="form" action="/insertBlockCookie"  >      
                                <input type="hidden" name="id" value=" <?php if(isset($message)){ } else { echo $id; } ?>">
                                {{-- <span>Add Path</span><br> --}}
                                <div class="form-group" style="width: 94%">

                                <input type="text" name="ip" style="width: 100%" required class="form-control" />
                                </div>
                                {{-- <input type="hidden" name="list" value="noncache_dir">  --}}
                                {{-- <span>Select Pattern</span><br> --}}
                                {{-- <input type="hidden" name="dir"> --}}
                                
      
                            <!-- 	<span>Enter Duration In Second (<sub>When you not enter duration so minimum time is 3 hours</sub>)</span> 
                                <input type="text" name="time" class="form-control" required="" /> <br> -->
                                {{-- <div class="form-group"> --}}
                                <input type="submit" value="ADD" class="btn btn-primary" />
                                {{-- </div> --}}
                          </form>                    
                          <br><br><br>      
      
                                  <h4>Delete Blocked Cookies</h4>
                                  <table class="table table-bordered">
                  <tbody>
                      <tr>
                          
                          <td><b>Blocked Cookies:</b></td>
                          
                          
      
                          <td>
      
                              @foreach ($whitepath as $wpaths)   
                              <form method="get" action="removeblockcookie/rr">
                                      <table >
                                          <td style="border:none;width: 200px;">
                                          <b>{{ $wpaths->url }}</b>
                                 
      
                                        </td>
                                        <input type="hidden" value="<?php echo auth()->user()->id; ?>" name = "id">
                
                                        <input type="hidden" name = "remove" 
                                        value="{{ $wpaths->url }}"
                                        >
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
        alert("New URL Path has been added");
    }
</script>
@endsection
