@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app1')

@section('content')



@if(isset($message))
<div class="alert alert-success" role="alert">
    {{  $message }}
</div>
@endif
        {{-- For Errors --}}
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
         </div>
        @endif

        <div class="panel panel-success" style="width: 100%; background: white;">
 <div class="panel-heading">
  @foreach($sucuri_user as $sucuri_users)
            <h3 style="font-size: 20px !important; padding: 10px;"><b>{{ $sucuri_users->name }}</b></h3>
            @endforeach
          </div>
                <div class="col-xs-12">
                    <h1 style="font-size: 20px !important; padding: 10px;">ADD/DELETE SITE</h1>
                    <h2 class="subtitle" style="font-size: 20px !important; padding-left: 10px;">Adds one or more sites to your account.</h2>

<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
            
          <div  class="setting-title" >
		  <h3>Add Site</h3>
<br>
<div class="col-xs-12">
  <form class="form-inline" method="get" action="addsite">
      <div class="container">
            <div class="form-group">  
              <input type="text" class="form-control" id="url" placeholder="e.g. /wp-login.php or /admin.php" name="add" style="width: 500px;">
            </div>
          <button type="submit" onclick="myFunction2()" class="btn btn-success" name="submit">ADD SITE</button>
          </form>
        </div>
      </div>






</div>

               </div>
          
      </div>

    </div>

    <h2 class="subtitle" style="font-size: 20px !important; padding: 10px;">Removes one site from your account.</h2>



<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>Delete Site</h3>

            <div class="col-xs-12">
              <form class="form-inline" method="get" action="deletesite">
                  <div class="container">
                        <div class="form-group">  
                          <input type="text" class="form-control" id="url" placeholder="e.g. /wp-login.php or /admin.php" name="delete" style="width: 500px;">
                        </div>
                        <button type="submit" onclick="myFunction2()" class="btn btn-danger" name="submit">DELETE SITE</button>
                      </form>
                    </div>
                  </div>




</div>

        </div>
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
