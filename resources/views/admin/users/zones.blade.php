@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">Domains owned by {{  $user->name }} ( {{  $user->email }} )</h3>
    <p>
        <a href="{{ route('admin.zones.create') }}?accID={{ $user->id }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($zones) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                        <th>Domain Name</th>
                        <th>Plan (Fetched from Cloudflare)</th>
                        <th>Status</th>
                        <th>Hosted on CF Account</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($zones) > 0)
                        @foreach ($zones as $zone)
                            <tr data-entry-id="{{ $zone->id }}">
                                <td></td>

                                <td><a href="{{ url("admin/".$zone->name) }}/overview"> {{ $zone->name }}</a></td>
                                <td>{{ ucfirst($zone->plan) }}</td>
                                <td>{{ ucfirst($zone->status) }}

                                    {{-- @if($zone->status=="pending")

                                        <a href="" class=""> (Fetch Currenct Status)</a>

                                    @endif --}}
                                </td>
                                <td>
                                     @if($zone->cfaccount_id!=0)
                                        
                                        {{ $zone->cfaccount->email }}    
                                    
                                    @else
                                    
                                        {{ $zone->spaccount->alias }}
                                    @endif
                                </td>
                                <td>
                                   
                                  
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.zones.destroy', $zone->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>

                            </tr>
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
    
@endsection
