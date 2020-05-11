@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    
<div class="content" id="sidebar">

    <!-- Start Content-->
    <meta http-equiv="refresh" content="0; url=zones" />

       
</div> <!-- content -->


        <!-- knob plugin -->
        <script src="{{ asset('libs/jquery-knob/jquery.knob.min.js') }}"></script>

        <!--Morris Chart-->
        <script src="{{ asset('libs/morris-js/morris.min.js') }}"></script>
        <script src="{{ asset('libs/raphael/raphael.min.js') }}"></script>

        <!-- Dashboard init js-->
        <script src="{{ asset('js/pages/dashboard.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('js/app.min.js') }}"></script>
@stop


