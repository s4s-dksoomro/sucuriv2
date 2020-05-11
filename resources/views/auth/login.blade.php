@extends('layouts.auth')

@section('content')


<?php

if($branding)
    {

        if($branding->logo!="")
        {
            $logo=$branding->logo;
        }
        else
        {
            $logo='images/bd-logo-white.png';
        }

        
    }
    else
    {
        $logo='images/bd-logo-white.png';
    }  

    if($logo=="")
    {
        $logo='images/bd-logo-white.png';
    }
$logo='images/bd-logo-white.png';
    ?>


<div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-md-4 col-md-push-4" style = "background: #333;">
        <div class="login-logo" >

          <br>
        </div>
        <div class="loginbox">

        <!-- DANGER ALERT -->
        <!--
          <div class="alert alert-danger">
            <strong>Whoops!</strong> Incorrect Email/Password
          </div>
        -->
          <p class="big"><h2>Login</h2></p>
			<hr>
			<br>
          <form class="" role="form" method="POST" action="{{ url('login') }}">
            <input type="hidden"
                               name="_token"
                               value="{{ csrf_token() }}">

            <div class="form-group">
				<label>Email Address:</label>
              <input type="email" class="form-control" name="email"  value="{{ old('email') }}" placeholder="Email" style="font-size: 15px;">
            </div>
            <div class="form-group">
              <label>Your Password:</label>
			  <input type="password" class="form-control" name="password" placeholder="Password" style="font-size: 15px;">
            </div>
			
            <div class="form-group">
              <label><input type="checkbox" name="remember"> Remember me </label>
            </div>
			<br>
            <div class="form-group">
              <button type="submit" class="btn btn-success btn-block" style="font-size: 15px;">Login </button>
            </div>
          </form>

           @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were problems with input:
                            <br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

        </div>
        <div class="loginbox-alternate">
          {{-- <p><a href="{{ route('auth.password.reset') }}">Forgot Password?</a></p> --}}
        </div>
      </div>
    </div>
  </div>



    @if($current_time_zone=Session::get('current_time_zone'))@endif
<input type="hidden" id="hd_current_time_zone" value="{{{$current_time_zone}}}">


<script type="text/javascript">
  $(document).ready(function(){
      if($('#hd_current_time_zone').val() ==""){ // Check for hidden field is empty. if is it empty only execute the post function
          var current_date = new Date();
          curent_zone = -current_date.getTimezoneOffset() * 60;
          var token = "{{csrf_token()}}";
          $.ajax({
            method: "POST",
            url: "{{URL::to('ajax/set_current_time_zone/')}}",
            data: {  '_token':token, curent_zone: curent_zone } 
          }).done(function( data ){
        });   
      }       
});
</script>
@endsection