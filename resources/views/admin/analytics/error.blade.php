@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
   
@include('partials.topmenu')
<div class="container">

<div class="row pageHeading">
    <div class="col-lg-6">
        <h1>Analytics</h1>
    </div>
     <div style="float: right; margin-top: 20px;" class="form-group col-lg-6 text-right" >
 <form method="post">
 {{csrf_field()}}


                <select style="width:200px;" class="select2 changeableSetting" id="minutes" name="minutes">
                    @if($zoneObj->plan=="enterprise")
                <option {{ $minutes == "30" ? "selected":"" }} value="30">Last 30 Minutes</option>
                <option {{ $minutes == "360" ? "selected":"" }} value="360">Last 6 Hours</option>
                <option {{ $minutes == "720" ? "selected":"" }} value="720">Last 12 Hours</option>
                @endif
                <option {{ $minutes == "1440" ? "selected":"" }} value="1440">Last 24 Hours</option>
                <option {{ $minutes == "10080" ? "selected":"" }} value="10080">Last 7 Days</option>
                <option {{ $minutes == "43200" ? "selected":"" }} value="43200">Last Month</option>
                
               
            </select>
                  </div>
</div>


<div class="alert alert-danger"> Analytics Service seems to be down, We are working to resolve this issue, Please try again later. </div>


    


    
@stop

@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
