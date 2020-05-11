@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">Panel Logs for {{ $zone->name }}</h3>
    <p>
       
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($logs) > 0 ? 'datatable' : '' }} ">
                <thead>
                    <tr>
                       
                        <th>Action</th>
                        <th>Type</th>
                        <th>User</th>
                        
                        <th>Data</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($logs) > 0)
                        @foreach ($logs as $log)
                            <tr data-entry-id="{{ $log->id }}">
                               

                                <td>{{ $log->name }}</td>
                                
                              
                                <td>
                                     @if($log->type==0)
                                        Accessed
                                     @elseif($log->type==1)
                                        Background Process
                                     @else
                                        Ajax Action
                                     @endif
                                </td>
                                <td>
                                   @if($log->user)
                                  {{ $log->user->name }} - {{ $log->user->email }}
                                  @else
                                  User Does not exist
                                  @endif
                                    
                                    
                                </td>
                                  <td>
                                   @if($log->uri!="")
                                   

                                        {{ $log->uri }} 
                                   
                                   @else
                                        @if($log->type==1)
                                            <?php
                                                $data=unserialize($log->payload);
                                                unset($data->zone);
                                            ?>
                                            {!! print_r($data) !!} 
                                        @else
                                            {!! $log->payload !!} 
                                        @endif
                                   @endif
                                
                                </td>
                                <td>
                                   
                                  {{ $log->created_at }}
                                    
                                    
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
