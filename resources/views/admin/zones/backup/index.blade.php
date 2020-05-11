@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">All
    Sucuri Websites</h3>
    <p>






 


 <a href="{{ route('admin.zones.spcreate') }}" class="btn btn-success">Add New  Domain</a>

                           
                          
                          
                           
            
        

 

       
       
       
                           

        
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>
  
     
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($sucuri_user) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        
                        <th>Domain Name</th>
                        <th>Domian Url</th>

                       
                        
                         
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>

                @foreach($sucuri_user as $sucuri_users)
     

                            <tr data-entry-id="{{ $sucuri_users->id }}">
                                

                                <td><a href="{{ $sucuri_users->id }}/overview">{{ $sucuri_users->name }}</a></td>
                                <td><a href="{{ $sucuri_users->url }}" class="btn btn-xs btn-info">{{ $sucuri_users->url }}</a>

                                    <!--a href="{{ route('admin.zones.ownership',[1]) }}" class="btn btn-xs btn-primary">Change Ownership</a-->

                                </td>
                              
                                
                                
                                <td>
                                   
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.zones.destroy', 1])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>

                            </tr>

                            @endforeach

                       
                            <!--tr data-entry-id="">
                                

                                <td><a href="">name</a></td>
                                <td><a href="" class="btn btn-xs btn-info">info</a>

                                    <a href="{{ route('admin.zones.ownership',1) }}" class="btn btn-xs btn-primary">Change Ownership</a>

                                </td>
                              
                                <td>   
                                  
                                    
                                </td>
                                
                                <td>
                                   
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.zones.destroy', 1])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>

                            </tr-->

                          
                        <!--tr>
                            <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                        </tr-->
                   
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 

@endsection
