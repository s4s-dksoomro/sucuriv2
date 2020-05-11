@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">SP Accounts</h3>
    <p>
        <a href="{{ route('admin.spaccounts.create') }}" class="btn btn-success">Link SP Account</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($spAccounts) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <th>Company Alias</th>
                        <th>Domains using this Account (in BlockDOS Panel)</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($spAccounts) > 0)
                        @foreach ($spAccounts as $spaccount)
                            <tr data-entry-id="{{ $spaccount->id }}">
                                <td></td>
                                <td>{{ $spaccount->alias }}</td>
                                <td>
                                @if($spaccount->zone->where('hosted_on',1)->count()>0)
                                    @foreach($spaccount->zone->where('hosted_on',1)->all() as $domain)
                                          <a href="{{ $domain->name }}/overview">{{ $domain->name }}</a>  [ client: <a href="users/{{ $domain->user->id }}/zones">{{ $domain->user->name }} </a>]<br>
                                    @endforeach
                                @endif
                               </td>
                                <td>

                                <a href="spaccounts/importZones/{{ $spaccount->id }}"> Import Zones from SP</a>
                                   {{--  <a href="{{ route('admin.spaccounts.edit',[$spaccount->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.spaccounts.destroy', $spaccount->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!} --}}
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">@lang('global.app_no_entries_in_table')</td>
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