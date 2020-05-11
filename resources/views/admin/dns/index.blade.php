@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')



<div class="row">
                <div class="col-xs-12">
                    <h2>DNS</h2>
                    <h2 class="subtitle">Manage your Domain Name System (DNS) settings</h2>




<div class="before-panel">


 </div>

 @if($records->count())
    <div class="panel panel-default panel-main">
      <div class="panel-heading"><h2 style="display: inline">DNS zone records for {{ $records->first()->zone->name }}</h2>
      <div class="pull-right">
      <a class="btn btn-primary" id="add_dns" data-toggle="modal" data-target=".bs-example-modal-lg" > Add New record</a>

    </div>
  </div>


      <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >

        <div class="panel-body table-responsive">
        <table style="display: none;" class="table table-bordered table-striped table-condensed">
            <form class="form-inline" id="create_dns" name="create_dns" method="post" action="createDNS">
            <tr>
            <td>
                  <div class="form-group">
                    <label class="sr-only" for="type">Type</label>
                    <select class="form-control" id="type" name="type">
                        <option value="A">A</option>
                        <option value="AAAA">AAAA</option>
                        <option value="CNAME">CNAME</option>
                        <option value="MX">MX</option>
                        <option value="LOC">LOC</option>
                        <option value="SRV">SRV</option>
                        <option value="SPF">SPF</option>
                        <option value="TXT">TXT</option>
                        <option value="NS">NS</option>
                    </select>
                  </div>
                  </td>
                  <td>
                  <div class="form-group">

                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                  </div>
                  </td>
                  <td>
                  <div class="form-group">

                    <input type="text" class="form-control" id="content" name="content" placeholder="IPv4 address">
                  </div>
                  </td>
                  <td>
                  <div class="form-group" id="status">
                    <select class="form-control select2" id="ttl" name="ttl">
                        <option value="1">Automatic TTL</option>
                        <option value="120">2 minutes</option>
                        <option value="300">5 minutes</option>
                        <option value="600">10 minutes</option>
                        <option value="900">15 minutes</option>
                        <option value="1800">30 minutes</option>
                        <option value="3600">1 hour</option>
                        <option value="7200">2 hours</option>
                        <option value="18000">5 hours</option>
                        <option value="43200">12 hours</option>
                        <option value="86400">1 day</option>
                    </select>

                  </div>
                  </td>
                  <td>
                  <div class="form-group">

                   <input class="form-control" name="proxied" id="proxied" type="checkbox">
                  </div>
                  </td>
                  <td>
                  <input type="hidden" name="proxiable" id="proxiable">
                  <input type="hidden" name="id" id="id">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  </td></tr>
                </form>
                </table>

            <table class="table table-bordered table-striped table-condensed">

                <thead>
                    <tr>
                        <th> Type</th>

                        <th>Name</th>
                        <th >Value</th>
                        <th>TTL</th>
                        <th>Status</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>

                <tbody>
                    @if (count($records) > 0)
                        @foreach ($records as $record)
                            <tr id="record_{{ $record->id }}" data-entry-id="{{ $record->id }}">
                                <td>{{ $record->type }}</td>

                                <td><span class="name" data-name="name" data-type="text" data-placement="right" data-title="Enter Hostname" data-pk="{{$record->id}}">

                                @if($record->name!=$zone->name)

                                    @if(ends_with($record->name,$zone->name))

                                        {{ str_replace_last(".".$zone->name,'',$record->name) }}
                                    @else
                                        {{ $record->name }}
                                    @endif

                                @else
                                {{ $record->name }}
                                @endif</span></td>
                                <td style="white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:300px;">
                                @if($record->type=="A")
                                points to <span class="value" data-name="content" data-type="text" data-placement="right" data-title="Enter Value" data-pk="{{$record->id}}">
                                {{ $record->content }}
                                </span>
                                @elseif($record->type=="CNAME")
                                cname of <span class="cname" data-name="content" data-type="text" data-placement="right" data-title="Enter Value" data-pk="{{$record->id}}">
                                {{ $record->content }}
                                </span>
                                
                                @else
<span class="otherValues" data-name="content" data-type="text" data-placement="right" data-title="Enter Value" data-pk="{{$record->id}}">
                                {{ $record->content }}
                                </span>
                                @endif</td>
                                <td>

                                    <span data-value="{{$record->ttl}}" class="ttl" data-name="ttl" data-type="select" data-title="Enter TTL" data-pk="{{$record->id}}">

                    @if($record->ttl=="1") Automatic TTL
                       @elseif($record->ttl=="120") 2 minutes
                        @elseif($record->ttl=="300") 5 minutes
                         @elseif($record->ttl=="600") 10 minutes
                          @elseif($record->ttl=="900") 15 minutes
                           @elseif($record->ttl=="1800") 30 minutes
                            @elseif($record->ttl=="3600") 1 hour
                             @elseif($record->ttl=="7200") 2 hours
                              @elseif($record->ttl=="18000") 5 hours
                              @elseif($record->ttl=="43200") 12 hours
                              @elseif($record->ttl=="86400") 1 day

                        @endif


                                </span></td>
                                <td>@if($record->proxiable)
                                    <input class="dnsProxy"  record-id="{{$record->id}}"  type="checkbox" data-onstyle="success" data-offstyle="default" {{ $record->proxied === 1 ? "checked" : "" }} data-toggle="toggle" data-on="<i class='fa fa-cloud'></i> Active" data-off="<i class='fa fa-exclamation'></i> Stopped">
                                    @endif

                                  </td>
                                <td>
                                    <a record-id="{{$record->id}}" class="btn btn-default deleteDNS">
                                    <i class="glyphicon glyphicon-remove"></i>
                                    </a>


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

    @else


      <div class="alert alert-info">No DNS Records Found</div>
    @endif


<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
CNAME Flattening    
    
</h3>




<p>
BlockDos will follow a CNAME to where it points and return that IP address instead of the CNAME record.
</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $cname_flattening=$zone->zoneSetting->where('name','cname_flattening')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

       
           <select  settingid="{{$zone->zoneSetting->where('name','cname_flattening')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="cname_flattening" name="cname_flattening">
                <option {{ $cname_flattening == "flatten_at_root" ? "selected":"" }} value="flatten_at_root">Flatten at root</option>
                <option {{ $cname_flattening == "flatten_all" ? "selected":"" }} value="flatten_all">Flatten All CNAME</option>
                <option {{ $cname_flattening == "flatten_none" ? "selected":"" }} value="flatten_none">Do not flatten CNAME</option>
                
                
            </select>
          
          </div>
      </div>

    </div>

</div>

</div>


<!-- Modal start -->
<div id="dns" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="Enter DNS">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Create DNS</h4>
      </div>
      <div class="modal-body">
        <form class="form-inline" id="create_dns" name="create_dns" method="post" action="createDNS">
          {{ csrf_field() }}
          <input type="hidden" name="zid" value="{{ $zone->id }}">
          <div class="form-group">
            <label class="sr-only" for="type">Type</label>
            <select class="form-control" id="type" name="type">
              <option value="A">A</option>
              <option value="AAAA">AAAA</option>
              <option value="CNAME">CNAME</option>
              <option value="MX">MX</option>
              <option value="LOC">LOC</option>
              <option value="SRV">SRV</option>
              <option value="SPF">SPF</option>
              <option value="TXT">TXT</option>
              <option value="NS">NS</option>
            </select>
          </div>
          <div class="form-group">
            <label class="sr-only" for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
          </div>
          <div class="form-group">
            <label class="sr-only" for="content">IPv4 address</label>
            <input type="text" class="form-control" id="content" name="content" placeholder="IPv4 address">
          </div>
          <div class="form-group" id="status">
            <label for="proxied">Status</label>
              <input class="form-control" name="proxied" id="proxied" type="checkbox">
            </div>
          <div class="form-group">
            <label class="sr-only" for="type">TTL</label>
            <select class="form-control" id="ttl" name="ttl">
              <option value="1">Automatic TTL</option>
              <option value="120">2 minutes</option>
              <option value="300">5 minutes</option>
              <option value="600">10 minutes</option>
              <option value="900">15 minutes</option>
              <option value="1800">30 minutes</option>
              <option value="3600">1 hour</option>
              <option value="7200">2 hours</option>
              <option value="18000">5 hours</option>
              <option value="43200">12 hours</option>
              <option value="86400">1 day</option>
            </select>
          </div>
          <input type="hidden" name="proxiable" id="proxiable">
          <input type="hidden" name="id" id="id">
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal end -->



@stop

@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
