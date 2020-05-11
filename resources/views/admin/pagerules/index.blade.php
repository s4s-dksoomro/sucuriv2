@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')




<div class="row">
                <div class="col-xs-12">
                    <h2>Page Rules</h2>
                    <h2 class="subtitle">Control your BlockDos settings by URL</h2>


@if($zone->cfaccount_id!=0)
    


     


@endif
<div class="before-panel">


      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
  Page Rules
    
</h3>

<?php
  $allowed=3;

  if($zone->plan=="pro")
  {
             $allowed=20;
  }
  elseif($zone->plan=="business")
  {
             $allowed=50;
  }
  
  elseif($zone->plan=="enterprise")
  {
             $allowed=100;
  }


            
?>
<p style="font-weight: bold;">You have used <span id="ruleCount">{{  count($pagerules) }}</span> out of 
            <span id="allowed">{{ $allowed }}</span> allowed page rules.</p>

  <p>Page Rules let you control which BlockDoS settings trigger on a given URL. Only one Page Rule will trigger per URL, so it is helpful if you sort Page Rules in priority order, and make your URL patterns as specific as possible.
</p>



  

</div>

          
          </div>
          <div class="col-lg-4 right ">
             <div class="col-lg-8">
 


          </div>
      </div>

    
 </div>
</div>
<div class="firewall">
      

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="ipfirewall">



          
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><h2 style="display: inline">Page Rules for {{ $zone->name }}</h2>

        <div class="pull-right">
          
      <a 
      @if($allowed<=count($pagerules)) style="display: none;"  @endif
 class="btn btn-primary" id="addPageRuleBtn" data-toggle="modal" data-target="#rule-edit-modal" > Add New Rule</a>
       
    </div>
  </div>


      <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >
      <input type="hidden" id="zid" name="zid" value="{{ $zone->id }}">
        <div class="panel-body table-responsive">
        

            <table class="table table-bordered table-striped table-condensed">

                <thead>
                    <tr>
                        
                        <th ></th>
                        <th>URL/Description</th>
                        

                        <th style="min-width:215px;">&nbsp;</th>

                    </tr>
                </thead>

                <tbody class="pageRulesTableBody">
                    @if (count($pagerules) > 0)
                   <?php  $n=1; ?>
                        @foreach ($pagerules as $rule)
                            <tr id="rule_{{ $rule->id }}" data-entry-id="{{ $rule->id }}">

                              <td class="drag-td"><i class="drag-handle glyphicon glyphicon-resize-vertical"></i><span class="sortable-number">{{ $n }}</span></td>
                              <?php $n++; ?>
                                <td>{{ $rule->value }}
                                  <br>
                                     @foreach ($rule->pageRuleAction as $action)
                                          @if($rule->pageRuleAction->first()->action != $action->action)
                                          
                                              {{ "," }}
                                          
                                          @endif

                                          @if($action->action=="forwarding_url")
                                          Forwarding URL: [Status Code: {{ str_replace(",SPLIT,"," url: ",$action->value) }}]

                                         @elseif($action->value!=NULL AND $action->value!="NULL")
                                         
                                          {{ ucwords(str_replace("_"," ",$action->action.": ".$action->value)) }}
                                          @else
                                            {{ ucwords(str_replace("_"," ",$action->action)) }}
                                          @endif

                                     @endforeach


                                     
                                </td>

                             
                                
                                
                                
                              
                                <td>
                                  <input class="pageRuleStatus"  record-id="{{$rule->id}}"  type="checkbox" data-onstyle="success" data-offstyle="default" {{ $rule->status == "active" ? "checked" : "" }} data-toggle="toggle" data-on="<i class='fa fa-check'></i> Active" data-off="<i class='fa fa-exclamation'></i> Disabled">

                                  <a style="margin:20px;"  data-toggle="modal" data-target="#rule-edit-modal_{{ $rule->id }}" class="editPageRule" rule-id="{{$rule->id}}" class="btn btn-secondary">
                                    <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                  
                                    <a class="deletePageRule" rule-id="{{$rule->id}}" class="btn btn-default">
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

          </div>

          
</div>
</div>




 



</div></div>


 @if (count($pagerules) > 0)
   @foreach ($pagerules as $rule)
                          

<div class="modal" id="rule-edit-modal_{{ $rule->id }}" >

   <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Page Rule</h4>
      </div>
      <div class="modal-body">

  
   <form method="post" action="editPageRule" class="pageRuleEditForm">
    <div class="">


  <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >
<p><strong>If the URL matches:</strong> By using the asterisk (*) character, you can create dynamic patterns that can match many URLs, rather than just one. </p>
<input class="form-control" value="{{ $rule->value }}" required="" placeholder="Example: www.example.com/*" name="url"  type="text">

<p  style="padding-top: 20px;"><strong>Then the settings are:</strong></p>
<div id="settings" class="settings">

  @foreach ($rule->pageRuleAction as $action)
                                       
                                     

<div style="padding-bottom: 15px;" class="row">
<div class="col-lg-5">
  <select oldValue="{{ $action->action }}" name="action[]" tabindex="-1" title="" class="select2 action">
    <option value="always_online">Always Online</option><option value="always_use_https">Always Use HTTPS</option><option value="browser_cache_ttl">Browser Cache TTL</option><option value="browser_check">Browser Integrity Check</option><option value="cache_deception_armor">Cache Deception Armor</option><option value="cache_level">Cache Level</option><option value="disable_apps">Disable Apps</option><option value="disable_performance">Disable Performance</option><option value="disable_security">Disable Security</option><option value="edge_cache_ttl">Edge Cache TTL</option><option value="email_obfuscation">Email Obfuscation</option><option value="forwarding_url">Forwarding URL</option><option value="automatic_https_rewrites">Automatic HTTPS Rewrites</option><option value="ip_geolocation">IP Geolocation Header</option><option value="opportunistic_encryption">Opportunistic Encryption</option><option value="explicit_cache_control">Origin Cache Control</option><option value="rocket_loader" >Rocket Loader</option><option value="security_level">Security Level</option><option value="server_side_exclude">Server Side Excludes</option><option value="ssl">SSL</option></select>
</div>
<div class="col-lg-3 valueDiv">
  <select oldValue="{{ $action->value }}"  name="actionValue[]" class="select2 value form-control">
    <option value="on">YES</option>
    <option value="of">NO</option>
  </select>

  <input type="hidden" name="actionID[]" value="{{ $action->id }}">
  <input class="deleteinput" type="hidden" name="delete[]" value="0">
</div>
<div class="col-lg-3">
   @if($rule->pageRuleAction->first()->action != $action->action)
    <a href="#" class=" btn btn-inverse hideAction"> <i class="glyphicon glyphicon-remove"></i></a>
   @endif
</div>
<div style="padding-top: 20px; display: none;" class="col-lg-12 extraDiv">
  <input placeholder="Redirect to this URL" type="text" name="extra[]" class="form-control extra">
  </div>
</div>

@endforeach
 </div>
  <div class="formMsg">
 </div>
  <a href="#" class="btn btn-link add-setting-link">
  + Add a Setting

</a>
<input type="hidden" name="zid" value="{{ $zone->id }}">
<input type="hidden" name="ruleid" value="{{ $rule->id }}">
<div class="row">
  <div class="col-lg-12 text-right">
<input class="btn " type="submit" value="Edit Rule">
</div>

</div>
</div>
</form>

</div></div>


</div></div>




@endforeach
@endif









<div class="modal" id="rule-edit-modal" data-reveal>

   <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Custom WAF Rule</h4>
      </div>
      <div class="modal-body">

  
   
    <div class="">

<form method="post" action="addPageRule" class="pageRuleForm">
  <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >
<p><strong>If the URL matches:</strong> By using the asterisk (*) character, you can create dynamic patterns that can match many URLs, rather than just one. </p>
<input class="form-control" required="" placeholder="Example: www.example.com/*" name="url"  type="text">

<p style="padding-top: 20px;"><strong>Then the settings are:</strong></p>
<div id="settings" class="settings">
<div  style="padding-bottom: 15px;"  class="row">
<div class="col-lg-5">
  <select name="action[]" tabindex="-1" title="" class="select2 action">
    <option value="">Select One</option><option value="always_online">Always Online</option><option value="always_use_https">Always Use HTTPS</option><option value="browser_cache_ttl">Browser Cache TTL</option><option value="browser_check">Browser Integrity Check</option><option value="cache_deception_armor">Cache Deception Armor</option><option value="cache_level">Cache Level</option><option value="disable_apps">Disable Apps</option><option value="disable_performance">Disable Performance</option><option value="disable_security">Disable Security</option><option value="edge_cache_ttl">Edge Cache TTL</option><option value="email_obfuscation">Email Obfuscation</option><option value="forwarding_url">Forwarding URL</option><option value="automatic_https_rewrites">Automatic HTTPS Rewrites</option><option value="ip_geolocation">IP Geolocation Header</option><option value="opportunistic_encryption">Opportunistic Encryption</option><option value="explicit_cache_control">Origin Cache Control</option><option value="rocket_loader" >Rocket Loader</option><option value="security_level">Security Level</option><option value="server_side_exclude">Server Side Excludes</option><option value="ssl">SSL</option></select>
</div>
<div class="col-lg-3 valueDiv">
  <select name="actionValue[]" class="select2 value form-control">
    <option value="on">YES</option>
    <option value="of">NO</option>
  </select>
</div>
<div class="col-lg-3">
  
</div>
<div style="padding-top: 20px; display: none;" class="col-lg-12 extraDiv">
  <input placeholder="Redirect to this URL" type="text" name="extra[]" class="form-control extra">
  </div>
</div>
 </div>

 <div class="formMsg">
 </div>
  <a href="#" class="btn btn-link add-setting-link">
  + Add a Setting

</a>
<input type="hidden" name="zid" value="{{ $zone->id }}">
<div class="row">
  <div class="col-lg-12 text-right">
<input class="btn " type="submit" value="Add Rule">
</div>
</div>
</form>

</div>



</div></div>

</div>
</div>








@stop

@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
