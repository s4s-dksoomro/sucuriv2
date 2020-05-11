@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app2')

@section('content')

@if(isset($message))
    <div class="alert alert-success" role="alert">
        {{  $message }}
    </div>
    @endif
    @if(isset($messages))
    
    <meta http-equiv = "refresh" content = "0; url = {{action('Admin\ZoneController@index',Request::segment(2))}}" />
    
    @endif
            {{-- For Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h3 class="page-title" style="font-size: 20px !important; padding: 10px;">All Sucuri Websites</h3>
    <p>
 <a href="{{ route('admin.zones.create3') }}" class="btn btn-success">Add New  Domain</a>
 <a href="{{ route('admin.zones.created') }}" class="btn btn-success">Add Existing Domain</a>
 <?php $ided=auth()->user()->id; 
if($ided == 1){
 ?>
 
 <a href="{{ route('admin.resellers.create') }}" class="btn btn-success">Add New Reseller </a>
<?php } ?>
    </p>

    <div class="panel panel-default" style="background: white;">
        <div class="panel-heading">
            {{-- @lang('global.app_list') --}}
        </div>
        <?php                     


$ided=auth()->user()->id;
 // $sucuri_userss = DB::table('brandings')->where('user_id' , $ided)->get();
$sucuri_userss = DB::table('brandings') 
    ->join('packages', 'packages.id', '=', 'brandings.pckg_detail')
    ->where('brandings.user_id', $ided)
    ->get();

 $limit = 0 ; 

 // $pckg = DB::table('packages')->where('user_id' , $ided)->get();
 
   foreach($sucuri_userss as $cf){
       $limit = $cf->domains;
   } 

?>

    <div class="alert alert-success" role="alert" id="pending" style="display: none;">
        <span style="color: white;">This domain is pending approval at the moment.</span>       
    </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($sucuri_user) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                         
                        <th>Domain Name</th>
                        <th>Domian Url</th>
                        <th>UserName</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php  $i = 0; ?>
                @foreach($sucuri_user as $sucuri_users)
                <?php  $i++;  ?>
                <input type="hidden" name="id" value="{{ $sucuri_users->id }}" />
                            <tr data-entry-id="{{ $sucuri_users->id }}">
                                <td>
                                    @if($sucuri_users->active == 2)
                                    	<a href="#">{{  $sucuri_users->name }}</a>
                                    @elseif($sucuri_users->s_key != null)
                                        <a href=" @if ($sucuri_users->s_key != null) {{ $sucuri_users->id }}/overview  @else @endif ">{{ $sucuri_users->name }}</a>
                                    
                                    @else
                                        <a href="#" id="pending" onclick="pending();">{{ $sucuri_users->name }}</a>
                                    @endif
                                </td>
                                
                                <td>
                                @if($sucuri_users->s_key != null)
                                    <a href="@if ($sucuri_users->s_key != null) {{ $sucuri_users->id }}/overview  @else @endif" class="btn btn-info">{{ $sucuri_users->url }}</a>
                                    @else
                                        <a href="#" class="btn btn-info" id="pending" onclick="pending();">{{ $sucuri_users->url }}</a>
                                    @endif
                                </td>
                                <td>
                                    <?php 
    $idede= $sucuri_users->user_id;
 $sucuri_usersss = DB::table('brandings')->where('user_id', $idede)->get();
 foreach($sucuri_usersss as $cf){
   echo  $named = $cf->name;
 } 
                                    ?>                                    
                                </td>  
                                <td>
                                    @if($ided ==1)
                                    <form action="{{route('admin.zones.create3')}}" method="get">  
                                        <input type="hidden" name="id" value="{{$sucuri_users->id}}">
                                        <input type="submit" class="btn btn-info" name="sbt" value="Update" style="display: inline;" />
                                    </form>
                                    @endif
                                <?php  

                                if ($sucuri_users->s_key != null) { ?>

                                <button type="button" class="btn  btn-success">Approved</button>
                                <?php  } else if ($sucuri_users->active == 2) { ?>
                                    <button type="button" class="btn    btn-success">Deleted</button>

                                <?php  } else { ?>
<button type="button" class="btn  btn-warning">Pending</button>

<?php  }  ?> 

<?php if($sucuri_users->active == 1){?>

{!! Form::open(array(
    'style' => 'display: inline-block;',
    'method' => 'DELETE',
    'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
    'route' => ['admin.zones.destroy', $sucuri_users->id ])) !!}
{!! Form::submit(trans('global.app_delete'), array('class' => 'btn  btn-danger')) !!}
{!! Form::close() !!}
<?php } else {?>
   <a href="#" class="btn btn-danger">Delete In Progress</a>
   <a href="/req/backToApproved/{{$sucuri_users->id}}" class="btn btn-success">Back To Approved/Pending</a>
   <?php } ?>  
                                </td>
                            </tr>
                            @endforeach 
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    var checklimit = <?= $i ?>;
    // var checklimit =5;
    
    var  limit = <?= $limit ?>;

if(checklimit > 0){
    if( checklimit > limit ){
        $('#domain').css('display' , 'none' );
    }
}
</script>
<script type="text/javascript">
    
    function pending(){
        $('#pending').css('display' , 'block');
    }
</script>
@stop

@section('javascript') 

@endsection