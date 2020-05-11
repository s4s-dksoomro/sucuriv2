@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    

        @if (!empty($audit))
  <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$audit}} </strong>
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
                        <th>Email</th>
                        <th>Action</th>
                       

                    </tr>
                </thead>
                
                <tbody>


                    

                @foreach($sucuri_user as $sucuri_users)
     

                            <tr data-entry-id="{{ $sucuri_users->id }}">








                           
                              {!! Form::open(['method' => 'POST', 'route' => ['admin.zones.store']]) !!}
 
                                <td>{{ $sucuri_users->name }}</td>
                                <td> {!! Form::text('Email', old('name'), ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => '']) !!}</td>
                                 
                                <td>
                                    <!-- <input type="submit" name="sbt" value="daily" /> -->

                                   <a href="{{url('reports' ,['id' => $sucuri_users->id]) }}" class="btn btn-xs btn-success"> Weekly</a>
                                   <a href="{{url('reports' ,['id' => $sucuri_users->id]) }}" class="btn btn-xs btn-danger"> 
                                   Monthly</a> 
                                     {!! Form::submit(trans('ok'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
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
