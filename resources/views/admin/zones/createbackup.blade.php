@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    

        @if (!empty($cache))
  <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$cache}} </strong>
  </div>
@endif
    

@if (!empty($message))
  <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$message}} </strong>
  </div> 
@endif
 




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
                        <th>Action</th>
                       
                        
                         
                   <!--      <th>&nbsp;</th> -->

                    </tr>
                </thead>
                
                <tbody>

                @foreach($sucuri_user as $sucuri_users)
     

                            <tr data-entry-id="{{ $sucuri_users->id }}">
                                

                                <td><a href="{{ $sucuri_users->id }}/overview">{{ $sucuri_users->name }}</a></td>
                                <td><a href="{{ $sucuri_users->url }}" class="btn btn-xs btn-info">{{ $sucuri_users->url }}</a>
                                </td>
                              
                                
                                
                                <td>
                                   
                                   <a href="{{url('add' ,['id' => $sucuri_users->id]) }}" class="btn btn-xs btn-success">Add SSL</a> </td>

                            </tr>

                            @endforeach

                       
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('javascript') 

@endsection
