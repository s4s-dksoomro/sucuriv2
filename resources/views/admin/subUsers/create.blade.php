@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="page-title">@lang('global.users.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.subUsers.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::label('email', 'Email*', ['class' => 'control-label']) !!}
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::label('password', 'Password*', ['class' => 'control-label']) !!}
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
            </div>

             <div  class="row">
                     <div class="col-xs-12 form-group">
<?php

 // <input style="min-width: 400px;" value="{{ $ability->name }}" name="abilities[]"  type="checkbox" data-onstyle="success" data-offstyle="default" checked data-toggle="toggle" data-on="{{ ucwords(str_replace("_", " ", $ability->name)) }}" data-off="{{ ucwords(str_replace("_", " ", $ability->name)) }}">

 ?>

                    @foreach($abilities as $ability)
                       

<div style="margin-top: 10px;" class="row">
    <div class="col-lg-2">
        {{ ucwords(str_replace("_", " ", $ability->name)) }}
    </div>
    <div class="col-lg-9">
        
  <div class="btn-group" data-toggle="buttons">

  <label class="btn btn-primary active">
    <input type="radio" name="abilities[{{ $ability->name }}]" value="0" autocomplete="off" checked> Not Allowed
  </label>
  <label class="btn btn-primary">
    <input type="radio" name="abilities[{{ $ability->name }}]" value="1" autocomplete="off"> Read Only
  </label>
  <label class="btn btn-primary">
    <input type="radio" name="abilities[{{ $ability->name }}]" value="2" autocomplete="off"> Can Modify
  </label>
</div>

    </div>
</div>

                    @endforeach


                    <p class="help-block"></p>
                    @if($errors->has('abilities'))
                        <p class="help-block">
                            {{ $errors->first('abilities') }}
                        </p>
                    @endif
                </div>
            </div>
      
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

    </div>
@stop

