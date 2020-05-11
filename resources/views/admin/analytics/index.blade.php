@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app1')

@section('content')
   
@if (!empty($message))
  <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$message}} </strong>
  </div>
@endif
<?php
$result=json_decode($ok);


 $id;
//$messages = $result->messages;
 //print_r($result->output);
 
 
 
 
 ?>

<script type="text/javascript">
    $(function(){
        $("#sel1 option").each(function(i){
            if(i>=0){
                var title = "You have selected security level: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel2 option").each(function(i){
            if(i>=0){
                var title = "You have selected admin access: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel3 option").each(function(i){
            if(i>=0){
                var title = "You have selected comment access: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel4 option").each(function(i){
            if(i>=0){
                var title = "You have selected cache mode: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel5 option").each(function(i){
            if(i>=0){
                var title = "You have selected advanced envasion: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel6 option").each(function(i){
            if(i>=0){
                var title = "You have selected aggressive bot filter: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel7 option").each(function(i){
            if(i>=0){
                var title = "You have selected compression: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel8 option").each(function(i){
            if(i>=0){
                var title = "You have selected protocol redirection: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel9 option").each(function(i){
            if(i>=0){
                var title = "You have selected HTTP/2 Support: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel10 option").each(function(i){
            if(i>=0){
                var title = "You have selected maximum upload size: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel11 option").each(function(i){
            if(i>=0){
                var title = "You have selected additional security: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel12 option").each(function(i){
            if(i>=0){
                var title = "You have selected unfiltered HTML: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel13 option").each(function(i){
            if(i>=0){
                var title = "You have selected stop upload of PHP: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel14 option").each(function(i){
            if(i>=0){
                var title = "You have selected site is behind cdn: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    $(function(){
        $("#sel15 option").each(function(i){
            if(i>=0){
                var title = "You have selected flood protections: "+$(this).val();
                $(this).attr("title",title);
            }
        });
    });
    
</script>

<div class="row">
                <div class="col-xs-12"  style="background: white; width: 100%;">
                    
                    <div class="panel panel-success">
                        <div class="panel-heading" style="font-size: 20px !important; padding: 10px;"><h3><b>{{$result->output->domain}}</b></h3></div>
                        <div class="panel-body">

                            
                            <h4>Details</h4>
                            <table class="table table-bordered">
            <tbody>
    

            {!! Form::open(['method' => 'POST', 'route' => ['admin.zones.spstore']]) !!}

<div class="panel panel-default">
    <div class="panel-heading">
        {{-- @lang('global.app_create') --}}
    </div>
    
    <div class="panel-body" >
        <div class="row" >
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('internal_ip_main', 'Internal IP Address*', ['class' => 'control-label']) !!}
                {!! Form::text('internal_ip_main',  " ", ['class' => 'form-control', 'placeholder' => '']) !!}
                {{-- $result->output->internal_ip_main, --}}
                <p class="help-block"></p>
                @if($errors->has('internal_ip_main'))
                    <p class="help-block">
                        {{ $errors->first('internal_ip_main') }}
                    </p>
                @endif
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('security_level', 'Security Level*', ['class' => 'control-label']) !!}
                {!! Form::select('security_level', array($result->output->security_level => $result->output->security_level, 'high' => 'high', 'paranoid' => 'paranoid'), 'null' ,['class' => 'form-control', 'id' => 'sel1']); !!}
                <p class="help-block"></p>
                @if($errors->has('security_level'))
                    <p class="help-block">
                        {{ $errors->first('security_level') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('admin_access', 'Admin Access*', ['class' => 'control-label']) !!}
                {!! Form::select('admin_access', array($result->output->admin_access => $result->output->admin_access, 'open' => 'open', 'restricted' => 'restricted'), 'null' ,['class' => 'form-control', 'id' => 'sel2']); !!}
                <p class="help-block"></p>
                @if($errors->has('admin_access'))
                    <p class="help-block">
                        {{ $errors->first('admin_access') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('comment_access', 'Comment Access*', ['class' => 'control-label']) !!}
                {!! Form::select('comment_access', array($result->output->comment_access => $result->output->comment_access, 'open' => 'open', 'restricted' => 'restricted'), 'null' ,['class' => 'form-control', 'id' => 'sel3']); !!}
                <p class="help-block"></p>
                @if($errors->has('comment_access'))
                    <p class="help-block">
                        {{ $errors->first('comment_access') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('cache_mode', 'Cache Mode*', ['class' => 'control-label']) !!}
                {!! Form::select('cache_mode', array($result->output->cache_mode => $result->output->cache_mode, 'docache' => 'docache', 'nocache' => 'nocache', 'sitecache' => 'sitecache', 'nocacheatall' => 'nocacheatall'), 'null' ,['class' => 'form-control', 'id' => 'sel4']); !!}
                <p class="help-block"></p>
                @if($errors->has('cache_mode'))
                    <p class="help-block">
                        {{ $errors->first('cache_mode') }}
                    </p>
                @endif
            </div>
        </div>



        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('detect_adv_evasion', 'Advanced evasion*', ['class' => 'control-label']) !!}
                {!! Form::select('detect_adv_evasion', array($result->output->detect_adv_evasion => $result->output->detect_adv_evasion, 'enabled' => 'enabled', 'disabled' => 'disabled'), 'null' ,['class' => 'form-control', 'id' => 'sel5']); !!}
                <p class="help-block"></p>
                @if($errors->has('detect_adv_evasion'))
                    <p class="help-block">
                        {{ $errors->first('detect_adv_evasion') }}
                    </p>
                @endif
            </div>
        </div>



        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('aggressive_bot_filter', 'Aggressive bot filter*', ['class' => 'control-label']) !!}
                {!! Form::select('aggressive_bot_filter', array($result->output->aggressive_bot_filter => $result->output->aggressive_bot_filter, 'enabled' => 'enabled', 'disabled' => 'disabled'), 'null' ,['class' => 'form-control', 'id' => 'sel6']); !!}
                <p class="help-block"></p>
                @if($errors->has('cache_mode'))
                    <p class="help-block">
                        {{ $errors->first('cache_mode') }}
                    </p>
                @endif
            </div>
        </div>






        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('compression_mode', 'Compression*', ['class' => 'control-label']) !!}
                {!! Form::select('compression_mode', array($result->output->compression_mode => $result->output->compression_mode, 'enabled' => 'enabled', 'disabled' => 'disabled'), 'null' ,['class' => 'form-control', 'id' => 'sel7']); !!}
                <p class="help-block"></p>
                @if($errors->has('compression_mode'))
                    <p class="help-block">
                        {{ $errors->first('compression_mode') }}
                    </p>
                @endif
            </div>
        </div>




        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('force_https', 'Protocol Redirection*', ['class' => 'control-label']) !!}
                {!! Form::select('force_https', array($result->output->force_https => $result->output->force_https , 'http' => 'http', 'https' => 'https'), 'null' ,['class' => 'form-control', 'id' => 'sel8']); !!}
                <p class="help-block"></p>
                @if($errors->has('force_https'))
                    <p class="help-block">
                        {{ $errors->first('force_https') }}
                    </p>
                @endif
            </div>
        </div>




        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('spdy_mode', 'HTTP/2 Support*', ['class' => 'control-label']) !!}
                {!! Form::select('spdy_mode', array($result->output->spdy_mode => $result->output->spdy_mode, 'enabled' => 'enabled', 'disabled' => 'disabled'), 'null' ,['class' => 'form-control', 'id' => 'sel9']); !!}
                <p class="help-block"></p>
                @if($errors->has('spdy_mode'))
                    <p class="help-block">
                        {{ $errors->first('spdy_mode') }}
                    </p>
                @endif
            </div>
        </div>




        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('max_upload_size', 'Maximum Upload Size*', ['class' => 'control-label']) !!}
                {!! Form::select('max_upload_size', array($result->output->max_upload_size => $result->output->max_upload_size, '5m' => '5m', '10m' => '10m', '50m' => '50m', '100m' => '100m', '200m' => '200m', '400m' => '400m'), 'null' ,['class' => 'form-control', 'id' => 'sel10']); !!}
                <p class="help-block"></p>
                @if($errors->has('max_upload_size'))
                    <p class="help-block">
                        {{ $errors->first('max_upload_size') }}
                    </p>
                @endif
            </div>
        </div>




        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('force_sec_headers', 'Additional Security*', ['class' => 'control-label']) !!}
                {!! Form::select('force_sec_headers', array($result->output->force_sec_headers => $result->output->force_sec_headers, 'enabled' => 'enabled', 'disabled' => 'disabled'), 'null' ,['class' => 'form-control', 'id' => 'sel11']); !!}
                <p class="help-block"></p>
                @if($errors->has('force_sec_headers'))
                    <p class="help-block">
                        {{ $errors->first('force_sec_headers') }}
                    </p>
                @endif
            </div>
        </div>




        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('unfiltered_html', 'unfiltered HTML*', ['class' => 'control-label']) !!}
                {!! Form::select('unfiltered_html', array($result->output->unfiltered_html => $result->output->unfiltered_html, 'allow_unfilter' => 'allow_unfilter', 'block_unfilter' => 'block_unfilter'), 'null' ,['class' => 'form-control', 'id' => 'sel12']); !!}
                <p class="help-block"></p>
                @if($errors->has('unfiltered_html'))
                    <p class="help-block">
                        {{ $errors->first('unfiltered_html') }}
                    </p>
                @endif
            </div>
        </div>




        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('block_php_upload', 'Stop upload of PHP*', ['class' => 'control-label']) !!}
                {!! Form::select('block_php_upload', array($result->output->block_php_upload => $result->output->block_php_upload, 'allow_uploads' => 'allow_uploads', 'block_uploads' => 'block_uploads'), 'null' ,['class' => 'form-control', 'id' => 'sel13']); !!}
                <p class="help-block"></p>
                @if($errors->has('block_php_upload'))
                    <p class="help-block">
                        {{ $errors->first('block_php_upload') }}
                    </p>
                @endif
            </div>
        </div>





        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('behind_cdn', 'Site is behind CDN*', ['class' => 'control-label']) !!}
                {!! Form::select('behind_cdn', array($result->output->behind_cdn => $result->output->behind_cdn, 'none' => 'none', 'behind_akamai' => 'behind_akamai', 'behind_cloudflare' => 'behind_cloudflare', 'behind_maxcdn' => 'behind_maxcdn', 'behind_cdn' => 'behind_cdn'), 'null' ,['class' => 'form-control', 'id' => 'sel14']); !!}
                <p class="help-block"></p>
                @if($errors->has('behind_cdn'))
                    <p class="help-block">
                        {{ $errors->first('behind_cdn') }}
                    </p>
                @endif
            </div>
        </div>



        <div class="row">
            <div class="col-xs-12 form-group" style="width: 100%;">
                {!! Form::label('http_flood_protection', 'Flood protection*', ['class' => 'control-label']) !!}
                {!! Form::select('http_flood_protection', array($result->output->http_flood_protection => $result->output->http_flood_protection, 'none' => 'none', 'behind_akamai' => 'behind_akamai', 'behind_cloudflare' => 'behind_cloudflare', 'behind_maxcdn' => 'behind_maxcdn', 'behind_cdn' => 'behind_cdn'), 'null' ,['class' => 'form-control', 'id' => 'sel15']); !!}
                <p class="help-block"></p>
                @if($errors->has('http_flood_protection'))
                    <p class="help-block">
                        {{ $errors->first('http_flood_protection') }}
                    </p>
                @endif




                {!! Form::hidden('user_id',$id ,['class' => 'form-control']); !!}

            </div>
        </div>





        



        
      
        
    </div>
</div>

{!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger', 'onclick' => 'myFunction()']) !!}
{!! Form::close() !!}

           
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
    <script>
        function myFunction() {
          alert("Changes have been saved");
        }
        </script>
@endsection
