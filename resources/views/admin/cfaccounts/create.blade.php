@extends('layouts.app')

@section('content')
    <h3 class="page-title">Link Cloudflare account to this system</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.cfaccounts.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('email', 'Email*', ['class' => 'control-label']) !!}
                    {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('user_api_key', 'User API Key*', ['class' => 'control-label']) !!}
                    {!! Form::text('user_api_key', old('user_api_key'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('user_api_key'))
                        <p class="help-block">
                            {{ $errors->first('user_api_key') }}
                        </p>
                    @endif
                </div>
            </div>

              
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

