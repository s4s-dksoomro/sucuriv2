@inject('request', 'Illuminate\Http\Request')



@extends('layouts.app1')

@section('content')
    

        @if (!empty($cache))
  <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$cache}} </strong>
  </div>
@endif
    
<div class="panel panel-success" style="background: white;">
        <div class="panel-heading">
            @foreach($sucuri_user as $sucuri_users)
            <h3 style="font-size: 20px !important; padding: 10px;"><b>{{ $sucuri_users->name }}</b></h3>
            @endforeach
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($sucuri_user) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        
                        <th>Domain Name</th>
                        <th>Domian Url</th>
                        <th>Action</th>
                    
                    </tr>
                </thead>
                
                <tbody>

                @foreach($sucuri_user as $sucuri_users)
     

                            <tr data-entry-id="{{ $sucuri_users->id }}">
                                

                                <td>{{ $sucuri_users->name }}</td>
                                <td >{{ $sucuri_users->url }}</td>
                              
                                
                                
                                <td>
                                   <center>
										<a href="{{url('clear_cache' ,['id' => $sucuri_users->id]) }}" class="btn btn-primary">Clear Cache</a> 
								   </center>

                                </td>

                            </tr>

                            @endforeach

                       
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('javascript') 

@endsection
