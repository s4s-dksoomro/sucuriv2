@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')
@section('content')


<?php
$result=json_decode($ok);
//$messages = $result->messages;
 //print_r($result->output);  ?>
<div class="row">
                <div class="col-xs-12">
                    <h2>Settings</h2>
                    <div class="panel panel-success">
                        <div class="panel-heading"><h3>{{$result->output->domain}}</h3></div>
                        <div class="panel-body">

                            
                            <h4>Details</h4>
                            <table class="table table-bordered">
            <tbody>
                <tr>
                    <td><b>Internal IP Address:</b> {{$result->output->internal_ip_main}}</td>
                    <td><b>Proxy :</b>{{$result->output->proxy_active}} </td>
                    
                    <td><b>White Listed Ip Address:</b>@foreach ($result->output->whitelist_list as $white) {{ print_r($white)}}  @endforeach</td>
                   

                </tr>
                <tr>
                
                    <td><b>Black Listed IP Address:</b> @foreach ($result->output->blacklist_list as $black) {{ print_r($black)}} @endforeach </td>
                    
                    <td><b>Security Level:</b>  {{$result->output->security_level}}</td>
                    <td><b>Cache Mode:</b>{{$result->output->cache_mode}} </td>
                </tr>

                <tr>
                
                    <td><b>Admin Access:</b>{{$result->output->admin_access}} </td>
                    
                    <td><b>Comment Access:</b>  {{$result->output->admin_access}}</td>
                    <td><b>Firewall IP:</b>{{$result->output->cache_mode}} </td>
                </tr>

                <tr>
                
                <td><b>Debug URL:</b>@foreach ($result->output->internal_domain_debug_list as $debug)
                
                {{ print_r($debug)}}
                @endforeach </td>
                
                <td><b>Advanced evasion :</b> {{$result->output->detect_adv_evasion}}</td>
                
                <td><b>Aggressive bot filter:</b>  {{$result->output->aggressive_bot_filter}}</td>
            </tr>

            <tr>
                
                <td><b>Compression:</b>{{$result->output->compression_mode}} </td>
                
                <td><b>Protocol Redirection:</b>  {{$result->output->force_https}}</td>
                <td><b>HTTP/2 Support:</b>{{$result->output->spdy_mode}} </td>
            </tr>


            <tr>
                
                <td><b>Maximum Upload Size:</b> {{$result->output->max_upload_size}}</td>
                
                <td><b>Additional Security:</b>  {{$result->output->force_sec_headers}}</td>
                <td><b>unfiltered HTML :</b>{{$result->output->unfiltered_html}} </td>
            </tr>

            <tr>
                
                <td><b>Stop upload of PHP:</b>{{$result->output->block_php_upload}} </td>
                
                <td><b>Site is behind CDN:</b>  {{$result->output->behind_cdn}}</td>
                <td><b>Flood protection :</b>{{$result->output->http_flood_protection}} </td>
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
