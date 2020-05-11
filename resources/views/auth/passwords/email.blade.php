@extends('layouts.auth')

@section('content')
<?php

// if($branding)
//     {

//         if($branding->logo!="")
//         {
//             $logo=$branding->logo;
//         }
//         else
//         {
//             $logo='images/bd-logo-white.png';
//         }

        
//     }
//     else
//     {
//         $logo='images/bd-logo-white.png';
//     }  

//     if($logo=="")
//     {
//         $logo='images/bd-logo-white.png';
//     }
$logo='/images/bd-logo-white.png';
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-md-push-4">
                <div class="login-logo">
                   <img src="{{ $logo }}" alt=
                {{ ucfirst(config('app.name')) }}
                ">
                </div>
                <div class="loginbox">
                    @if (session('status'))
                <!-- SUCCESS ALERT -->
               
                    <div class="alert alert-success">
                         {{ session('status') }}
                    </div>
                @endif

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
                    <p class="big">Forgot your password?</p>
                    <p>Enter your email address below and we will send you instructions on how to change your password.</p>

                    <form class="" role="form" method="POST" action="{{ url('password/email') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                    </form>
                </div>
                <div class="loginbox-alternate">
                    <p><a href="#">Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>

    
@endsection