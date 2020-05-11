@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">Rejected Domains</h3>
      
 @if (!empty(session()->get( 'data' )))
  <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{session()->get( 'data' )}} </strong>
  </div>
@endif

    <div class="panel panel-default" style="background: white;">
        <div class="panel-heading">
            {{-- <h3 style="font-size: 20px !important;">Rejected Requests</h3> --}}
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($users) > 0 ? 'datatable' : '' }} ">
                <thead>
                    <tr>
                        <th>@lang('global.users.fields.name')</th>
                        <th>URL</th>
                        <th>API</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    
                    
                        @foreach ($users as $user => $val)

                            <tr data-entry-id="{{ $val->id }}">
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->url }}</td>
                                 <td>{{ $val->a_key }}</td>
                                
                               
                                <td>
                                    <div style="">
                                    
                                    <a href="update?id={{ $val->id}}" class="btn  btn-success">Approve</a>
                                    
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.users.destroy', $val->id])) !!}
                                        <input type="hidden" name="delete" value="delete" />
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-danger')) !!}
                                    {!! Form::close() !!}
                                </div>
                                </td>

                            </tr>
                            
                        @endforeach
                    
                       
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br><br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
@stop

@section('javascript') 
    <script>
        
    </script>
@endsection