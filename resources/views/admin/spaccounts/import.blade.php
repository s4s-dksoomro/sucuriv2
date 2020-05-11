@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">SP Zones Import</h3>
    <p>
        Importing Zones under account {{ $spaccount->email }}
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($zones) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                       
                        <th>Zone</th>
                        <th>Status</th>
                        <th>Plan</th>
                        <th>Setup Type</th>
                        <th>Assign to User</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($zones) > 0)
                        @foreach ($zones as $zone)
                            <tr @if($zone->exists) class="alert-success" style="display: none" @endif data-entry-id="{{ $zone->id }}">
                              
                                <td>{{ $zone->cdn_url }}</td>
                                <td>
                                    {{$zone->inactive==0 ? "Active":"inactive" }}
                               </td>
                               <td>
                                   
                               </td>
                               <td>
                                    {{ ucfirst($zone->type) }}
                               </td>
                               <td>

                                @if($zone->exists)
                                    Already assigned to: 
                                    {{ $zone->existing->user->email }}
                                @else
                               
                                   <select class="form-control select2 user" required=""  name="user">
                    @foreach($users as $user)


                    @if($user->id != 1)

                        <option value="{{$user->id}}">
                            {{$user->email}} ( {{ $user->zone->count() }} Zones )
                        </option>

                        @endif
                    @endforeach
                </select>
                 @endif
                               </td>
                                <td>
                                @if(!$zone->exists)
                                <a zone_id="{{ $zone->id }}" name="{{ $zone->cdn_url }}"  spaccount="{{ $spaccount->id }}"  class="btn btn-default importSpZone" > Import this Zone</a>
                                @endif
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