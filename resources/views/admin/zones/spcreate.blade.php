@extends('layouts.app')

@section('content')
    <h3 class="page-title">Add New Domain</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.zones.spstore']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Name (domain name)*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>

            <div style="display: none;" class="row">
                <div class="col-xs-12 form-group">
                 <label for="type" class="control-label">Zone Setup Type*</label>
                 <div>
                     <input name="type" id="type" type="checkbox" data-onstyle="success" data-offstyle="info" { data-toggle="toggle" data-on="<i class='fa fa-cloud'></i> Full Zone" data-off="<i class='fa fa-cloud'></i> CNAME">
                     </div>
                </div>

                </div>

                <div style="display: none;" id="cnameBased">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('resolveTo', 'resolveTo*', ['class' => 'control-label']) !!}
                    {!! Form::text('resolveTo', old('resolveTo'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('resolveTo'))
                        <p class="help-block">
                            {{ $errors->first('resolveTo') }}
                        </p>
                    @endif
                </div>
            </div>

            </div>

            <div id="full">



            </div>
            
            <div class="row">
                <div class="col-xs-12 form-group">
                <label for="user" class="control-label">User Account*</label>
                <select class="form-control select2" required="" id="user" name="user">
                    @foreach($users as $user)


                    @if($user->id != 1)

                        <option value="{{$user->id}}">
                            {{$user->email}} ( {{ $user->zone->count() }} Zones )
                        </option>

                        @endif
                    @endforeach
                </select>
                   
                    <p class="help-block"></p>
                    @if($errors->has('roles'))
                        <p class="help-block">
                            {{ $errors->first('roles') }}
                        </p>
                    @endif
                </div>
            </div>


            @if(auth()->user()->id==1)
            <div class="row">
                <div class="col-xs-12 form-group">
                <label for="spaccount" class="control-label">SP Account*</label>
                <select class="form-control select2" required="" id="spaccount" name="spaccount">
                    @foreach($spaccounts as $spaccount)
                        <option value="{{$spaccount->id}}">
                            {{$spaccount->alias}} ( {{ $spaccount->zone->count() }} Zones )
                        </option>
                    @endforeach
                </select>
                   
                    <p class="help-block"></p>
                    @if($errors->has('roles'))
                        <p class="help-block">
                            {{ $errors->first('roles') }}
                        </p>
                    @endif
                </div>
            </div>
            @endif
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

