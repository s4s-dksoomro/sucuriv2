@extends('layouts.app3')

@section('content')
    <h3 class="page-title">Edit Reseller</h3>
    
   <?php // echo // $user->id; ///$users11  = DB::table('sucuri_user')->where('user_id',$val->user_id)->where('active',1)->get();  ?>
 <form action="updates" method="POST">
 @csrf
    <div class="panel panel-default" style="background: white">
        <div class="panel-heading">
           
        </div> 

        <div class="panel-body" style="width: 100%;">
            <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>

                   <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('email', 'Email*', ['class' => 'control-label']) !!}
                    {!! Form::email('email', $user->email, ['class' => 'form-control', 'placeholder' => '', 'required' => '','readonly'=>'readonly']) !!}
                    <p class="help-block"></p>
                 
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('password', 'Password', ['class' => 'control-label']) !!}
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('Select Pckg', 'Select Pckg', ['class' => 'control-label']) !!}
                    <select name="pckg" id="pckg" class="form-control"  required>
                      <option value="">Select Pacakge</option>

                      @foreach ($pckg as $val)
                        <option value= "{{ $val->id }}" >{{$val->name}}</option>
                        @endforeach
                    </select>
                    
                </div>	
            </div>

     		<div class="row">  
     			@if($branding->logo!=null)
     			<div class="col-xs-12 form-group" style="width: 100%;">
     				<label class="control-label"><a href="{{ url('/user.defaultLogo.'.$branding->user_id)}}" >Default Logo</a></label> 
     				<div> 
     					<label class="control-label">Logo</label><br>
     					<img src="/images/{{$branding->logo}}" width="20%" height="30%" />
     				</div>
     			</div>
     			@else
     				<div class="col-xs-12 form-group" style="width: 100%;"><label class="control-label">used default logo</label></div>
     			@endif
     		</div>

            <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('', 'Description', ['class' => 'control-label']) !!}
                    {!! Form::textarea('sp',  $branding->sp,['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('sp') }}
                        </p>
                    @endif
                </div>
            </div>
           
</div>

</div>

{!! Form::submit(trans('global.app_update'), ['class' => 'btn btn-danger']) !!} 
{!! Form::close() !!}
</div>

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript">
    
    $('#pckg').val('{{$branding->pckg_detail}}');

</script>
<script>
   
</script>
@stop

