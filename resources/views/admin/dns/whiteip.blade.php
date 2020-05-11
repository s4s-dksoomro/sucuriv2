@inject('request', 'Illuminate\Http\Request')

@extends('layouts.app1')
@section('content')


<?php

//echo $sucuri_users;
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
<div class="row" style="background: white; width: 100%">
                <div class="col-xs-12" style="background: white; width: 100%">
                    <h2 style="padding-left: 10px;">White List IP</h2>
                    <div class="panel panel-success" style="width: 100%">
                        {{-- <div class="panel-heading"><h3>{{$result->output->domain}}</h3></div> --}}
                        <div class="panel-body" style="width: 100%">

                            
                            <h4>Remove Whitlisted IP</h4>
                            <table class="table table-bordered" style="width: 100%">
            <tbody>
                <tr>
                
                    <td><b>White Listed IP Address:</b> </td>

                    <td><b> {{ $ip }} </b></td>
                    <td>
                            <center>
                            <form method="get" action="removewhite/rr">
								<input type="hidden" value="{{ $ip }}" name = "remove">
								<input type="hidden" value="{{ $id }}" name = "id">
                                <button type="submit" class="btn btn-danger" name="submit">Remove From Whitelisted IPs</button>
								
                            </form>
                            </center>

                    </td>

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
