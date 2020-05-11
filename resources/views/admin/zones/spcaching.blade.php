@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')


<!-- <?php
//$result=json_decode($ok);
  ?> -->


@if (!empty($audit))
  <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>{{$audit}} </strong>
  </div>
@endif
    
    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>
  

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ">
                <thead>
                    <tr>
                        
                        <th>Domain Name</th>
                        <th>Domian Url</th>
                        <th>Action</th>
                       

                    </tr>
                </thead>
                
                <tbody>

              
     

                            <tr data-entry-id="">
                                

                                <td>name</td>
                                <td >name</td>
                              
                                
                                
                                <td>
                                   
                                   <a href="" class="btn btn-xs btn-success"> Audit Trails</a> 
                                </td>

                            </tr>

                            
                       
                </tbody>
            </table>
        </div>
    </div>








@stop

@section('javascript') 
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
