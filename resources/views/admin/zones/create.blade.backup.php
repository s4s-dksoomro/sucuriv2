@extends('layouts.app')

@section('content')
    <h3 class="page-title">Add New Domain</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.zones.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Name (domain name)*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'id'=>"zName", 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('url', 'Domain URL*', ['class' => 'control-label']) !!}
                    {!! Form::text('url', old('url'), ['class' => 'form-control', 'id'=>"url", 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('url'))
                        <p class="help-block">
                            {{ $errors->first('url') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('s_key', 'Secret Key*', ['class' => 'control-label']) !!}
                    {!! Form::text('s_key', old('s_key'), ['class' => 'form-control', 'id'=>"s_key", 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('s_key'))
                        <p class="help-block">
                            {{ $errors->first('s_key') }}
                        </p>
                    @endif
                </div>
            </div>



            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('a_key', 'Api Key*', ['class' => 'control-label']) !!}
                    {!! Form::text('a_key', old('a_key'), ['class' => 'form-control', 'id'=>"a_key", 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('a_key'))
                        <p class="help-block">
                            {{ $errors->first('a_key') }}
                        </p>
                    @endif
                </div>
            </div>

           </div>
           </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

