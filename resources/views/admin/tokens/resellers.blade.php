@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">Resellers</h3>
    <p>
        <a href="{{ route('admin.resellers.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($users) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                        <th>@lang('global.users.fields.name')</th>
                        <th>@lang('global.users.fields.email')</th>
                        <th>Allowed CF zones</th>
                        <th>Allowed SP zones</th>
                        <th></th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($users) > 0)
                        @foreach ($users as $user)

                            @if($user->id!=1)
                            <tr data-entry-id="{{ $user->id }}">
                                <td></td>

                                <td>{{ $user->branding->name }}</td>
                                <td>{{ $user->email }}</td>
                                 <td>{{ $user->branding->cf }}</td>
                                  <td>{{ $user->branding->sp }}</td>
                                <td>
                                   
                                </td>
                                <td>
                                    <div style="display: none;">
                                    <a href="{{ route('admin.users.zones',[$user->id]) }}" class="btn btn-xs btn-info">
                                    Show Hosted Domains
                                    </a>
                                    <a href="{{ route('admin.users.edit',[$user->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.users.destroy', $user->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </div>
                                </td>

                            </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        
    </script>
@endsection
