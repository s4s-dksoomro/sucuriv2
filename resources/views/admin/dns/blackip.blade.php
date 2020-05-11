@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app1')
@section('content')


<?php
// $result=json_decode($ok);
//$messages = $result->messages;
 //print_r($result->output);  ?>
  @if(isset($message))
<div class="alert alert-success" role="alert">
    {{  $message }}
</div>
@endif
        {{-- For Errors --}}
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
         </div>
        @endif
<div class="row">
                <div class="col-xs-12">
                    <h2>Black Listed IPs</h2>
                    <div class="panel panel-success">
                        {{-- <div class="panel-heading"><h3>{{$result->output->domain}}</h3></div> --}}
                        <div class="panel-body">

                            
                            <h4>Remove Black Listed IP</h4>
                            <table class="table table-bordered">
            <tbody>
                <tr>
                
                    <td><b>Black Listed IP Address:</b> </td>

                    <td><b> {{ $ip }} </b></td>
                    <td>
                            <center>
                            <center>
                            <form method="get" action="removeblack/rr">
								<input type="hidden" value="{{ $ip }}" name = "remove">
								<input type="hidden" value="{{ $id }}" name = "id">
                                <button type="submit" class="btn btn-danger" name="submit">Remove From Blacklisted IPs</button>
								
                            </form>
                            </center>
                            </center>

                                
                    </td>
                </tr>

          

           
            </tbody>
        </table>

        

                        </div>
                    </div>
                </div>
            </div>









@stop

@section('javascript') 
    
@endsection
