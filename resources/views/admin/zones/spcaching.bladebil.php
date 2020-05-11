@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')


<!-- <?php
//$result=json_decode($ok);
  ?> -->




<div class="row">
                <div class="col-xs-12">
                    <h2>Data</h2>
                    <div class="panel panel-success">
                        <div class="panel-heading"><h3>AuditTrails</h3></div>
                        <div class="panel-body">

                            
                            <h4>Details</h4>
                            <table class="table table-bordered">
            <tbody>
                <tr>
                    <td><b>http_referer :</b> {{$result['http_referer']}}</td>
                    <td><b>request_date :</b>{{$result['request_date']}} </td>
                    
                    <td><b>request_time :</b> {{$result['request_time']}} </td>
                   

                </tr>
                <tr>
                
                    <td><b>sucuri_block_code:</b>{{ $result['sucuri_block_code'] }} </td>
                    
                    <td><b>sucuri_block_reason:</b>  {{$result['sucuri_block_reason']}}</td>
                    <td><b>request_country_name:</b>{{$result['request_country_name']}} </td>
                </tr>

                <tr>
                
                    <td><b>request_country_code:</b>{{$result['request_country_code']}} </td>
                    
                    <td><b>country_name :</b>  {{$result['country_name']}}</td>
                    <td><b>city:</b>{{$result['city']}} </td>
                </tr>

                <tr>
                
                <td><b>postal_code:</b>{{$result['postal_code']}}</td>
                
                <td><b>area_code :</b>{{$result['area_code']}}</td>
                
                <td><b>sucuri_is_allowed :</b>  {{$result['sucuri_is_allowed ']}}</td>
            </tr>

            <tr>
                
                <td><b>sucuri_block_title:</b>{{$result['sucuri_block_title']}} </td>
                
                <td><b>sucuri_block_code:</b> {{$result['sucuri_block_code']}}</td>
                <td><b>sucuri_block_reason:</b> {{$result['sucuri_block_reason']}}</td>
           
            </tr>

            <tr>
                
                <td><b>is_usable:</b>{{$result['is_usable']}} </td>
                
                <td><b>checksum:</b> {{$result['checksum']}}</td>
                <td><b>remote_addr:</b> {{$result['remote_addr']}}</td>
           
            </tr>
            <tr>
                
                <td><b>remote_hostname:</b>{{$result['remote_hostname']}} </td>
                
                <td><b>remote_logname:</b> {{$result['remote_logname']}}</td>
                <td><b>remote_user:</b> {{$result['remote_user']}}</td>
           
            </tr>
            <tr>
                
                <td><b>request_timezone:</b>{{$result['request_timezone']}} </td>
                
                <td><b>request_timestamp:</b> {{$result['request_timestamp']}}</td>
                <td><b>request_method:</b> {{$result['request_method']}}</td>
           
            </tr>
            
            <tr>
                
                <td><b>http_status:</b>{{$result['http_status']}} </td>
                
                <td><b>http_status_title:</b> {{$result['http_status_title']}}</td>
                <td><b>http_bytes_sent:</b> {{$result['http_bytes_sent']}}</td>
           
            </tr>
            <tr>
                
                <td><b>resource_path:</b>{{$result['resource_path']}} </td>
                
                <td><b>http_protocol:</b> {{$result['http_protocol']}}</td>
                <td><b>http_user_agent:</b> {{$result['http_user_agent']}}</td>
           
            </tr>
            <tr><td><b>sucuri_is_allowed</b> {{$result['sucuri_is_allowed']}}</td></tr>
           
            </tbody>
        </table>

        

                        </div>
                    </div>
                </div>
            </div>









@stop

@section('javascript') 
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
