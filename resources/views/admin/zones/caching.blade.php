@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    





<div class="row">
                <div class="col-xs-12">
                    <h2>Caching</h2>
                    <h2 class="subtitle">Manage caching settings for your website</h2>

 <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >
    
  <div class="panel panel-default panel-main cacheClearDiv">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
Purge Cache    
</h3>




  <p>Clear cached files to force BlockDOS to fetch a fresh version of those files from your web server. You can purge files selectively or all at once.</p>
  <p>Note: Purging the cache may temporarily degrade performance for your website.</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          
          </div>
          <div class="col-lg-4 right ">
          <div  class="setting-title" >
          </div>
              <div class="row">
              
               <div class="col-lg-8 ">
               
               <button swalTitle="Purge All Cache?" swalText="Purging your cache may slow your website temporarily." zoneName="{{ $zone->name }}" action="purgeCacheAll" class="btn btn-info customActions">Purge Everything</button>
               
              </div>
              </div>

              

                 <div class="row">
              
               <div class="col-lg-8">
               
               <button swalTitle="Purge Individual Files?" swalText="You can purge up to 30 files at a time.

Note: Wildcards are not supported with single file purge at this time. You will need to specify the full path to the file.
Separate tags(s) with commas, or list one per line " zoneName="{{ $zone->name }}" action="purgeFiles" extra="files" class="btn btn-info customActions">Purge Files</button>
               
              </div>
              </div>

               <div class="row">
              
               <div class="col-lg-8">
                
                @if($zone->plan != "enterprise") 

                <button disabled="disabled" class="btn btn-danger customActions">Purge Tags (Not Available for this domain)</button>

 @else
                <button  swalTitle="Purge Cache By Tags" swalText="You can purge up to 30 tags at a time.

Separate tags(s) with commas, or list one per line" zoneName="{{ $zone->name }}" action="purgeTags" extra="tags" class="btn btn-info customActions">Purge Tags</button>

   @endif
               
              </div>
              </div>
          </div>
      </div>
      

    </div>



 <div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
Caching Level     
    
</h3>




<p>
Determine how much of your website’s static content you want BlockDOS to cache. Increased caching can speed up page load time.
</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $cache_level=$zoneSetting->where('name','cache_level')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','cache_level')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="cache_level" name="cache_level">
                <option {{ $cache_level == "aggressive" ? "selected":"" }} value="aggressive">Standard</option>
                <option {{ $cache_level == "basic" ? "selected":"" }} value="basic">No Query String</option>
                <option {{ $cache_level == "simplified" ? "selected":"" }} value="simplified">Ignore Query String</option>
                
                
            </select>
          
          </div>
      </div>

    </div>








<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
Browser Cache Expiration     
</h3>




<p>
Determine the length of time BlockDOS instructs a visitor’s browser to cache files. During this period, the browser loads the files from its local cache, speeding up page loads.
</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $browser_cache_ttl=$zoneSetting->where('name','browser_cache_ttl')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','browser_cache_ttl')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="browser_cache_ttl" name="browser_cache_ttl">

                @if($zone->plan != "enterprise") 
                <option disabled="disabled" {{ $browser_cache_ttl == "30" ? "selected":"" }} value="30">30 Seconds (Disabled)</option>
                <option disabled="disabled" {{ $browser_cache_ttl == "60" ? "selected":"" }} value="60">1 Minute (Disabled)</option>
                <option disabled="disabled" {{ $browser_cache_ttl == "300" ? "selected":"" }} value="300">5 Minutes (Disabled)</option>
                <option disabled="disabled" {{ $browser_cache_ttl == "1200" ? "selected":"" }} value="1200">20 Minutes (Disabled)</option>
                @else
                <option {{ $browser_cache_ttl == "30" ? "selected":"" }} value="30">30 Seconds</option>
                <option {{ $browser_cache_ttl == "60" ? "selected":"" }} value="60">1 Minute</option>
                <option {{ $browser_cache_ttl == "300" ? "selected":"" }} value="300">5 Minutes</option>
                <option {{ $browser_cache_ttl == "1200" ? "selected":"" }} value="1200">20 Minutes</option>
                @endif
                <option {{ $browser_cache_ttl == "1800" ? "selected":"" }} value="1800">30 Minutes</option>
                <option {{ $browser_cache_ttl == "3600" ? "selected":"" }} value="3600">1 Hour</option>
                <option {{ $browser_cache_ttl == "7200" ? "selected":"" }} value="7200">2 Hours</option>
                <option {{ $browser_cache_ttl == "10800" ? "selected":"" }} value="10800">3 Hours</option>
                <option {{ $browser_cache_ttl == "14400" ? "selected":"" }} value="14400">4 Hours</option>
                <option {{ $browser_cache_ttl == "18000" ? "selected":"" }} value="18000">5 Hours</option>
                <option {{ $browser_cache_ttl == "28800" ? "selected":"" }} value="28800">8 Hours</option>
                <option {{ $browser_cache_ttl == "43200" ? "selected":"" }} value="43200">12 Hours</option>
                <option {{ $browser_cache_ttl == "57600" ? "selected":"" }} value="57600">16 Hours</option>
                <option {{ $browser_cache_ttl == "72000" ? "selected":"" }} value="72000">20 Hours</option>
                <option {{ $browser_cache_ttl == "86400" ? "selected":"" }} value="86400">1 Day</option>
                <option {{ $browser_cache_ttl == "172800" ? "selected":"" }} value="172800">2 Days</option>
                <option {{ $browser_cache_ttl == "259200" ? "selected":"" }} value="259200">3 Days</option>
                <option {{ $browser_cache_ttl == "345600" ? "selected":"" }} value="345600">4 Days</option>
                <option {{ $browser_cache_ttl == "432000" ? "selected":"" }} value="432000">5 Days</option>
                <option {{ $browser_cache_ttl == "691200" ? "selected":"" }} value="691200">8 Days</option>
                <option {{ $browser_cache_ttl == "1382400" ? "selected":"" }} value="1382400">16 Days</option>
                <option {{ $browser_cache_ttl == "2073600" ? "selected":"" }} value="2073600">24 Days</option>
                <option {{ $browser_cache_ttl == "2678400" ? "selected":"" }} value="2678400">1 Month</option>
                <option {{ $browser_cache_ttl == "5356800" ? "selected":"" }} value="5356800">2 Months</option>
                <option {{ $browser_cache_ttl == "16070400" ? "selected":"" }} value="16070400">6 Months</option>
                <option {{ $browser_cache_ttl == "31536000" ? "selected":"" }} value="31536000">1 Year</option>
                <option {{ $browser_cache_ttl == "0" ? "selected":"" }} value="0">Respect Existing Headers</option>

                
                
            </select>
          
          </div>
      </div>

    </div>








<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
Always Online</h3>




<p>
If your server goes down, BlockDOS will serve your website’s static pages from our cache.
</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $always_online=$zoneSetting->where('name','always_online')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','always_online')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="always_online" name="always_online">
                <option {{ $always_online == "off" ? "selected":"" }} value="off">Off</option>
                <option {{ $always_online == "on" ? "selected":"" }} value="on">On</option>
              
                
                
            </select>
          
          </div>
      </div>

    </div>






<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
Development Mode  </h3>




<p>
Temporarily bypass our cache allowing you to see changes to your origin server in realtime.
</p>
<p>
Note: Enabling this feature can significantly increase origin server load. Development mode does not purge the cache so files will need to be purged after development mode expires.
</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $development_mode=$zoneSetting->where('name','development_mode')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','development_mode')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="development_mode" name="development_mode">
                <option {{ $development_mode == "off" ? "selected":"" }} value="off">Off</option>
                <option {{ $development_mode == "on" ? "selected":"" }} value="on">ON</option>
                
                
            </select>
          
          </div>
      </div>

    </div>













</div>
</div>

@stop

@section('javascript') 
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
