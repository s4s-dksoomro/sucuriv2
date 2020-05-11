@extends('layouts.app')

@section('content')
    <h3 class="page-title">Add New Domain</h3>

        <div class="alert alert-success" role="alert">
        {{  $data }}
    </div>

    {!! Form::open(['method' => 'POST', 'route' => ['admin.update']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        <input type="hidden" name="id" value="{{ $users[0]->id }}">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Name (domain name)*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', $users[0]->name, ['class' => 'form-control', 'id'=>"zName", 'placeholder' => '', 'required' => '' ]) !!}
                    <p class="help-block"></p> 
                    
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('url', 'Domain URL*', ['class' => 'control-label']) !!}
                    {!! Form::text('url', $users[0]->url, ['class' => 'form-control', 'id'=>"url", 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('url'))
                        <p class="help-block">
                            {{ $errors->first('url') }}
                        </p>
                    @endif
                </div>
            </div>
            @if(auth()->user()->id==1)
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('s_key', 'Secret Key*', ['class' => 'control-label']) !!}
                    {!! Form::text('s_key', $users[0]->s_key, ['class' => 'form-control', 'id'=>"s_key", 'placeholder' => '', 'required' => '']) !!}
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
                   
                    {!! Form::hidden('a_key','7302b26beb3438873cf29499591358fc'
, ['class' => 'form-control', 'id'=>"a_key", 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('a_key'))
                        <p class="help-block">
                            {{ $errors->first('a_key') }}
                        </p>
                    @endif
                </div>
            </div>
@else



<div class="row">
                <div class="col-xs-12 form-group">
                   
                    {!! Form::hidden('s_key', old('s_key'), ['class' => 'form-control', 'id'=>"s_key", 'placeholder' => '', 'required' => '']) !!}
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
                   
                    {!! Form::hidden('a_key','7302b26beb3438873cf29499591358fc', ['class' => 'form-control', 'id'=>"a_key", 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('a_key'))
                        <p class="help-block">
                            {{ $errors->first('a_key') }}
                        </p>
                    @endif
                </div>
            </div>


            @endif

           </div>
           </div> 
           <input type="hidden" name="user_id" value="<?= auth()->user()->id ?>">
    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}
@stop

