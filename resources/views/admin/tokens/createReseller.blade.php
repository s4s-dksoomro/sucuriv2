@extends('layouts.app')

@section('content')
    <h3 class="page-title">Resellers</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.resellers.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create') reseller
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

            <div class="row">
                <div class="col-xs-8 form-group">
                <label for="cfaccount" class="control-label">Cloudflare Account*</label>
                <select class="form-control select2" required="" id="cfaccount" name="cfaccount">
                    @foreach($cfAccounts as $cfaccount)
                        <option value="{{$cfaccount->id}}">
                            {{$cfaccount->email}} ( {{ $cfaccount->zone->count() }} Zones )
                        </option>
                    @endforeach
                </select>
                   
                    <p class="help-block"></p>
                    @if($errors->has('cfaccount'))
                        <p class="help-block">
                            {{ $errors->first('cfaccount') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-4 form-group">
                <label for="cfaccount" class="control-label">CF Zones Allowed</label>
                <input type="number" name="cfAllowed" value="5" class="form-control">
                   
                    <p class="help-block"></p>
                    @if($errors->has('cfAllowed'))
                        <p class="help-block">
                            {{ $errors->first('cfAllowed') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-8 form-group">
                <label for="spaccount" class="control-label">SP Account*</label>
                <select class="form-control select2" required="" id="spaccount" name="spaccount">
                    @foreach($spAccounts as $spaccount)
                        <option value="{{$spaccount->id}}">
                            {{$spaccount->alias}} ( {{ $spaccount->zone->count() }} Zones )
                        </option>
                    @endforeach
                </select>
                   
                    <p class="help-block"></p>
                    @if($errors->has('spaccount'))
                        <p class="help-block">
                            {{ $errors->first('spaccount') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-4 form-group">
                <label for="cfaccount" class="control-label">SP Zones Allowed</label>
                <input type="number" name="spAllowed" value="0" class="form-control">
                   
                    <p class="help-block"></p>
                    @if($errors->has('spAllowed'))
                        <p class="help-block">
                            {{ $errors->first('spAllowed') }}
                        </p>
                    @endif
                </div>
            </div>

             <div  class="row">
                     <div class="col-xs-12 form-group">


                    @foreach($abilities as $ability)
                        <input style="min-width: 400px;" value="{{ $ability->name }}" name="abilities[]"  type="checkbox" data-onstyle="success" data-offstyle="default" checked data-toggle="toggle" data-on="{{ ucwords(str_replace("_", " ", $ability->name)) }}" data-off="{{ ucwords(str_replace("_", " ", $ability->name)) }}">

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
@stop

