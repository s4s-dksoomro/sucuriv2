@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">Panel Logs</h3>
    <p>
       
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($zones) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                        <th>Domain Name</th>
                       
                        
                        <th>Hosted on CF Account</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($zones) > 0)
                        @foreach ($zones as $zone)
                            <tr data-entry-id="{{ $zone->id }}">
                                <td></td>

                                <td><a href="{{ url("admin/".$zone->name) }}/overview"> {{ $zone->name }}</a></td>
                                
                              
                                <td>
                                     @if($zone->cfaccount_id!=0)
                                        
                                        {{ $zone->cfaccount->email }}    
                                    
                                    @else
                                    
                                        {{ $zone->spaccount->alias }}
                                    @endif
                                </td>
                                <td>
                                   
                                    
                                    <a href="panel_logs/{{ $zone->name }}">View Logs</a>
                                    
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









<div class="modal" id="elsModal" data-reveal>

   <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Enable Enterprise Log Share Parsing</h4>
      </div>
      <div class="modal-body">
    <form id="elsForm">
    <div style="padding-bottom: 15px;" class="row">
        <div class="col-lg-3">Select Hours for previous Data</div>
        <div class="clo-lg-6">
            <select  class="select2"  id="hours" name="hours">
                <option value="2">2 Hours</option>
                <option value="6">6 Hours</option>
                <option value="12">12 Hours</option>
                <option selected="selected" value="24">24 Hours</option>
                <option value="48">48 Hours</option>
                <option value="72">72 Hours</option>
            </select>
        </div>
    </div>

     <div style="padding-bottom: 15px;" class="row">
        <div class="col-lg-3">Select Bucket Size</div>
        <div class="clo-lg-6">
            <select class="select2" id="elsminutes" name="minutes">
                <option value="2">Fetch 2 Minutes logs every minute</option>
                <option value="5">Fetch 5 Minutes logs every minute</option>
                <option selected="selected" value="10">Fetch 10 Minutes logs every minute</option>
                <option value="20">Fetch 20 Minutes logs every minute</option>
                <option value="30">Fetch 30 Minutes logs every minute</option>
                <option value="40">Fetch 40 Minutes logs every minute</option>
                <option value="50">Fetch 50 Minutes logs every minute</option>
                <option value="60">Fetch 60 Minutes logs every minute</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id="fetchInfo"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-right pull-right">
            <input type="submit" name="" value="Continue">
        </div>
    </div>


    </form>

</div></div>

</div>
</div>

@stop

@section('javascript') 
    
@endsection
