@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-6"><h3 class="page-title">@lang('global.users.title')</h3></div>
         <div class="col-lg-6 text-right"> <a style="margin-top:20px;" href="{{ route('admin.subUsers.create') }}" class="btn btn-success">@lang('global.app_add_new')</a></div>
    </div>
    
    <p>
       
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            Sub Users
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        

                        <th>@lang('global.users.fields.name')</th>
                        <th>@lang('global.users.fields.email')</th>
                        <th></th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($users) > 0)
                        @foreach ($users as $user)

                            @if($user->id!=1)
                            <tr data-entry-id="{{ $user->id }}">
                               

                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                   
                                </td>
                                <td>

                                   
                                    <a style="" href="{{ route('admin.subUsers.edit',[$user->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.subUsers.destroy', $user->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>

                            </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">No User Accounts found!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

