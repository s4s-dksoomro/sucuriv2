@inject('request', 'Illuminate\Http\Request')
<!DOCTYPE html>
<html lang="en">

<head>
	
    @include('partials.head')
</head>


<body class="hold-transition skin-blue sidebar-mini">



@include('partials.topbar')

 @if(isset($result) OR isset($ip) OR isset($sucuri_user) or isset($ok))
 




@endif

@if(isset($result) OR isset($ip) OR isset($sucuri_user) or isset($ok))

<!-- Content Wrapper. Contains page content -->
   
             @else 
            @include('partials.sidebar')
            <div class="main">
        <div class="container-fluid">
            @if(isset($siteTitle))
                <h3 class="page-title">
                    {{ $siteTitle }}
                </h3>
            @endif
            
            @endif
            
            <div class="row">
                <div class="col-md-12">

                    @if (Session::has('message'))
                        <div class="note note-info">
                            <p>{!! Session::get('message') !!}</p>
                        </div>
                    @endif


                    @if (Session::has('status'))
                        <div style="margin-top: 50px" class="alert alert-info">
                            <p>{!! Session::get('status') !!}</p>
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div style="margin-top: 50px" class="alert alert-danger">
                            <p>{!! Session::get('error') !!}</p>
                        </div>
                    @endif


                    @if ($errors->count() > 0)
                        <div class="note note-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')

                </div>
            </div>
        </div>
    </div>


{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}

{!! Form::close() !!}

    <footer class="footer" id="sidebar">
        <div class="container">
			<center>
				<p>Copyright &copy; 2020. All Rights Reserved.</p>
			</center>
        </div>
    </footer>
@include('partials.javascripts')
</body>
</html>