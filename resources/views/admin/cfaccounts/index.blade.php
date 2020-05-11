@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">Cloudflare Accounts</h3>
    <p>
        <a href="{{ route('admin.cfaccounts.create') }}" class="btn btn-success">Link Cloudflare Account</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($cfAccounts) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <th>Email Address</th>
                        <th>Domains using this Account (in BlockDOS Panel)</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($cfAccounts) > 0)
                        @foreach ($cfAccounts as $cfaccount)
                            <tr data-entry-id="{{ $cfaccount->id }}">
                                <td></td>
                                <td>{{ $cfaccount->email }}</td>
                                <td>
                                @if($cfaccount->zone->where('hosted_on',0)->count()>0)
                                    @foreach($cfaccount->zone->where('hosted_on',0)->all() as $domain)
                                          <a href="{{ $domain->name }}/overview">{{ $domain->name }}</a>  @if($domain->user)[ client: <a href="users/{{ $domain->user->id }}/zones">{{ $domain->user->name }} </a>] @else Not assigned to any user @endif <br>
                                    @endforeach
                                @endif
                               </td>
                                <td>

                                <a href="cfaccounts/importZones/{{ $cfaccount->id }}"> Import Zones from Cloudflare</a>
                                   {{--  <a href="{{ route('admin.cfaccounts.edit',[$cfaccount->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.cfaccounts.destroy', $cfaccount->id])) !!}
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