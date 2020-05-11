@extends('layouts.app3')
@section('content')

    <h3 class="page-title">@lang('global.users.title')</h3>
	
    {!! Form::model($user, ['method' => 'POST','files'=> true , 'route' => ['admin.updates1', 'id='.$user[0]->id]]) !!}

    <div class="panel panel-default" style="background: white;">
        <div class="panel-heading">
            
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', $user[0]->name, ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::email('email', $user[0]->email , ['class' => 'form-control', 'placeholder' => '' , 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>
            </div>
        
            <!-- <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('password', 'Password', ['class' => 'control-label']) !!}
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
            </div> -->


        <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('Image', 'Logo', ['class' => 'control-label']) !!}
                    <input type="file" name="image" class="form-control" accept="image/*">
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
                    {!! Form::label('', 'Description', ['class' => 'control-label']) !!}
                    {!! Form::textarea('sp',  $user[0]->sp,['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
            </div>

            {!! Form::submit(trans('global.app_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

     <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
     <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

@stop

