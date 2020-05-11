@extends('layouts.app')

@section('content')
    <h3 class="page-title">Link SP account to this system</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.spaccounts.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('alias', 'Company Alias*', ['class' => 'control-label']) !!}
                    {!! Form::text('alias', old('alias'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('alias'))
                        <p class="help-block">
                            {{ $errors->first('alias') }}
                        </p>
                    @endif
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('key', 'Consumer Key*', ['class' => 'control-label']) !!}
                    {!! Form::text('key', old('key'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('key'))
                        <p class="help-block">
                            {{ $errors->first('key') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('secret', 'Consumer secret*', ['class' => 'control-label']) !!}
                    {!! Form::text('secret', old('secret'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('secret'))
                        <p class="help-block">
                            {{ $errors->first('secret') }}
                        </p>
                    @endif
                </div>
            </div>

              
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

