@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    


<div class="row">
                <div class="col-xs-12">
                    <h2>Overview</h2>
                    <div class="panel panel-success">
                        <div class="panel-heading"><h3>{{ $zone->name }}</h3></div>
                        <div class="panel-body">

                             @if($zoneSetting)
                            <h4>Summary</h4>
                            <table class="table table-bordered">
            <tbody>
         
                <tr>
                    <td><b>Security Level:</b> <a href="firewall">{{ str_replace("_"," ",title_case($zoneSetting->where('name','compress')->first()->value)) }}</a></td>
                    <td><b>Caching Level:</b> {{ $zoneSetting->where('name','cache_valid')->first()->value }}</td>
                    <td><b>SSL:</b> {{ str_replace("_"," ",title_case($zoneSetting->where('name','ssl')->first()->value)) }}</td>
                </tr>
                <tr>
                   
                  
                 
                </tr>
            </tbody>
        </table>
        @else

            It looks like Zone is marked as pending. Please make sure that Zone is using correct DNS.
        @endif
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
