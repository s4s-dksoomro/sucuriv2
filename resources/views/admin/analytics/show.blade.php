@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')


<?php
$result=json_decode($ok);
//$messages = $result->messages;
 //print_r($result->output);  ?>
<div class="row">
                <div class="col-xs-12">
                    <h2>Whitelist/Blacklist IP </h2>
                    <div class="panel panel-success">
                        <div class="panel-heading"><h3>{{$result->output->domain}}</h3></div>
                        <div class="panel-body">

                            
                            <h4>IPs</h4>
                            <table class="table table-bordered">
            <tbody>
                <tr>
                    
                    <td><b>White Listed Ip Address:</b></td>

                    <td>
                        @foreach ($result->output->whitelist_list as $white) <a href="white/{{ $white }}"> <b>  {{ print_r($white)}}</b></a> <br>  @endforeach
                    </td>   
                </tr>

                <tr>
                
                    <td><b>Black Listed IP Address:</b> </td>

                    <td>
                        @foreach ($result->output->blacklist_list as $black) <a href="black/{{ $black }}"> <b>  {{ print_r($black)}}</b></a> <br> @endforeach 
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
