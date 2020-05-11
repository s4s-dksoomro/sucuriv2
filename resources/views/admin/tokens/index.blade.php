@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')

    <h3 class="page-title">API Tokens</h3>
    <p>
        <a href="{{ route('admin.token.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>

    @if($token!="")

        <div style="margin-top: 50px" class="alert alert-info">
                            <p>New Token Generated! Please copy the token given below...</p>
        </div>
         <div class="row">
            <div class="col-lg-8">
           
       
         <div class="form-group">
  <label>API Token:</label>
  <textarea class="form-control" rows="5"  readonly="readonly">{{$token}}</textarea>
 </div>
</div> 
</div>
        
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($tokens) > 0 ? 'datatable' : '' }} ">
                <thead>
                    <tr>
                        

                        <th>Token Name</th>
                        <th>Token Created On</th>
                        <th></th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($tokens) > 0)
                        @foreach ($tokens as $token)

                          
                            <tr data-entry-id="">
                               

                                <td>{{ $token->name }}</td>
                                <td>{{ $token->created_at }}</td>
                                <td>
                                   
                                </td>
                                <td>
                                  
                               

                                  
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.token.destroy', 'id='.$token->id])) !!}
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

