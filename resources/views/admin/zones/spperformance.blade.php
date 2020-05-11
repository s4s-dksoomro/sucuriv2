@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    



<div class="row">
                <div class="col-xs-12">
                    <h2>Performance</h2>
                    <h2 class="subtitle">Manage performance settings for your website</h2>


 <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >
    
  








<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>Gzip Compression    
    
</h3>




<p>
Enable gzip compression for static files to reduce bandwidth usage. For a full list of supported file types, please refer to our support page</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $compress=$zoneSetting->where('name','compress')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','compress')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="compress" name="compress">
                <option {{ $compress == "0" ? "selected":"" }} value="0">Disabled</option>
                <option {{ $compress == "1" ? "selected":"" }} value="1">Compression Level 1</option>
                <option {{ $compress == "2" ? "selected":"" }} value="2">Compression Level 2</option>
                <option {{ $compress == "3" ? "selected":"" }} value="3">Compression Level 3</option>
                <option {{ $compress == "4" ? "selected":"" }} value="4">Compression Level 4</option>
                <option {{ $compress == "5" ? "selected":"" }} value="5">Compression Level 5</option>
                <option {{ $compress == "6" ? "selected":"" }} value="6">Compression Level 6</option>
                
               
                
                
            </select>
          
          </div>
      </div>

    </div>




<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>Content Disposition
 
    
</h3>




<p>
Force files to download instead of loading in the browser's viewport.</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $content_disposition=$zoneSetting->where('name','content_disposition')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','content_disposition')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="content_disposition" name="content_disposition">
                <option {{ $content_disposition == "0" ? "selected":"" }} value="0">OFF</option>
                <option {{ $content_disposition == "1" ? "selected":"" }} value="1">ON</option>
                
                
                
            </select>
          
          </div>
      </div>

    </div>




<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>Remove Cookies

 
    
</h3>




<p>
Have the CDN ignore cookie headers (e.g. session cookies set by Google Analytics).

  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $ignore_setcookie_header=$zoneSetting->where('name','ignore_setcookie_header')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','ignore_setcookie_header')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="ignore_setcookie_header" name="ignore_setcookie_header">
                <option {{ $ignore_setcookie_header == "0" ? "selected":"" }} value="0">OFF</option>
                <option {{ $ignore_setcookie_header == "1" ? "selected":"" }} value="1">ON</option>
                
                
                
            </select>
          
          </div>
      </div>

    </div>





<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>Pseudo Streaming

 
    
</h3>




<p>
Serve media files (flv and mp4 ONLY with h.264 encoding) with your site. Consult support before enabling this option.

</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $pseudo_streaming=$zoneSetting->where('name','pseudo_streaming')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','pseudo_streaming')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="content_disposition" name="pseudo_streaming">
                <option {{ $pseudo_streaming == "0" ? "selected":"" }} value="0">OFF</option>
                <option {{ $pseudo_streaming == "1" ? "selected":"" }} value="1">ON</option>
                
                
                
            </select>
          
          </div>
      </div>

    </div>





<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>X-Forwarded-For HTTP Header
 
    
</h3>




<p>
X-Forwarded-For (XFF) header identifies the originating IP address of a client connecting to a web server through an HTTP proxy.
</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $x_forward_for=$zoneSetting->where('name','x_forward_for')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','x_forward_for')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="x_forward_for" name="x_forward_for">
                <option {{ $x_forward_for == "0" ? "selected":"" }} value="0">OFF</option>
                <option {{ $x_forward_for == "1" ? "selected":"" }} value="1">ON</option>
                
                
                
            </select>
          
          </div>
      </div>

    </div>





<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>Cross Origin Resource Sharing
 
    
</h3>




<p>
Force files to download instead of loading in the browser's viewport.</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $content_disposition=$zoneSetting->where('name','content_disposition')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','content_disposition')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="content_disposition" name="content_disposition">
                <option {{ $content_disposition == "0" ? "selected":"" }} value="0">OFF</option>
                <option {{ $content_disposition == "1" ? "selected":"" }} value="1">ON</option>
                
                
                
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
