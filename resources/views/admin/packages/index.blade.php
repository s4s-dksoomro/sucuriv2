@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
<!-- <h3 class="page-title">@lang('global.Packages.title')</h3> -->
<h3 class="page-title">Packages</h3>
    <p>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>
<div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($packageRecord) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                	
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                        <!-- <th>@lang('global.users.fields.name')</th> -->
                         <th>Name</th>
               <!--          <th>@lang('global.users.fields.email')</th> -->
		               <th>Description</th>
		               <th>Price</th>
                        <th></th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($packageRecord) > 0)
                        @foreach ($packageRecord as $data)

                            <tr data-entry-id="{{ $data->id }}">
                                <td></td>

                                <td>{{ $data->name }}</td>
                                <td>{{ $data->description }}</td>
                                <td>{{ $data->price }}</td>
                                <td>
                                   
                                </td>
                                <td>
                                	<a href="{{ route('admin.packages.edit',[$data->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                	{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.packages.destroy', $data->id])) !!}
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
