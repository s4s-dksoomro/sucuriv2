@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    


<div class="row">
                <div class="col-xs-12">
                    <h2>Content Protection</h2>
                    <h2 class="subtitle">Protect content on your site</h2>

 <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >
    
  <div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
   Email Address Obfuscation  
    
    
</h3>



<p>
Obfuscated email addresses displayed on your website to prevent harvesting by bots and spammers, without visible changes to the address for human visitors.
</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $email_obfuscation=$zoneSetting->where('name','email_obfuscation')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
            <select  settingid="{{$zoneSetting->where('name','email_obfuscation')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="email_obfuscation" name="email_obfuscation">
                <option {{ $email_obfuscation == "off" ? "selected":"" }} value="off">Off</option>
                <option {{ $email_obfuscation == "on" ? "selected":"" }} value="on">ON</option>
                
                
            </select>

          </div>
      </div>

    </div>



 <div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
Server-side Excludes     
    
</h3>




<p>
Automatically hide specific content from suspicious visitors.

</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $server_side_exclude=$zoneSetting->where('name','server_side_exclude')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','server_side_exclude')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="server_side_exclude" name="server_side_exclude">
                <option {{ $server_side_exclude == "off" ? "selected":"" }} value="off">Off</option>
                <option {{ $server_side_exclude == "on" ? "selected":"" }} value="on">ON</option>
                
                
            </select>
          
          </div>
      </div>

    </div>





 <div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
Hotlink Protection     
</h3>




<p>
Protect your images from off-site linking.

</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $hotlink_protection=$zoneSetting->where('name','hotlink_protection')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           
           <select  settingid="{{$zoneSetting->where('name','hotlink_protection')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="hotlink_protection" name="hotlink_protection">
                <option {{ $hotlink_protection == "off" ? "selected":"" }} value="off">Off</option>
                <option {{ $hotlink_protection == "on" ? "selected":"" }} value="on">ON</option>
                
                
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
