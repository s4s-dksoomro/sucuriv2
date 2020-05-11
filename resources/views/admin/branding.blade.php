@extends('layouts.app')

@section('content')
    <h3 class="page-title">Update Branding Information</h3>

    @if(session('success'))
        <!-- If password successfully show message -->
        <div class="row">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @else
        {!! Form::model($branding,['method' => 'PATCH', 'route' => ['admin.branding'], 'files' => true]) !!}
        <!-- If no success message in flash session show change password form  -->
        <div class="panel panel-default">
            <div class="panel-heading">
                @lang('global.app_edit')
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 form-group">
                        {!! Form::label('name', 'Company Name*', ['class' => 'control-label']) !!}
                        <input value="{{ old('name', $branding->name) }}" type="text" name="name" id="name" class="form-control">
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
                        {!! Form::label('logo', 'Logo*', ['class' => 'control-label']) !!}
                        <input value="{{ old('logo', $branding->logo) }}"  type="file" name="logo" id="logo" class="form-control">
                        <p class="help-block"></p>
                        @if($errors->has('logo'))
                            <p class="help-block">
                                {{ $errors->first('logo') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 form-group">
                        {!! Form::label('url', 'Panel URL*', ['class' => 'control-label']) !!}
                        <input value="{{ old('url', $branding->url) }}"  type="text" name="url" id="url" class="form-control">
                        <p class="help-block"></p>
                        @if($errors->has('url'))
                            <p class="help-block">
                                {{ $errors->first('url') }}
                            </p>
                        @endif
                    </div>
                </div>

                

                
            </div>
        </div>

        {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    @endif
@stop

