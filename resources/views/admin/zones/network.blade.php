@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app1')

@section('content')
    

        @if (!empty($audit))
  <div class="alert alert-success alert-dismissable custom-success-box">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$audit}} </strong>
  </div>
@endif
    
<div class="panel panel-success" style="background: white">
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
                        <th>Domian URL</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>

                @foreach($sucuri_user as $sucuri_users)
     

                            <tr data-entry-id="{{ $sucuri_users->id }}">

                                <td><a href="{{url('trails' ,['id' => $sucuri_users->id])}} ">{{ $sucuri_users->name }}</a></td>
                                <td >{{ $sucuri_users->url }}</td>
                                <td>
                                   <center>
                                   <a href="{{url('AuditTrails' ,['id' => $sucuri_users->id]) }}" class="btn btn-primary"> Audit Trails</a> 
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
