@extends('layouts.app')

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['admin.zones.stored']]) !!}
<?php 
        $updateID = 0;
        $name = "" ;
        $url = "" ;
        $userID= 0;
        if(isset($_REQUEST['id'])){
            $updateID = $_REQUEST['id'];
            $data = DB::table('sucuri_user')->where('id',$updateID)->get();
            $name = $data[0]->name;
            $url = $data[0]->url;
            $userID = $data[0]->user_id;
        }
?>
    <div class="panel panel-default" style="background: white;">
        <div class="panel-heading">
            <h3 class="page-title" style="font-size: 20px !important;">Add New Domain</h3>

        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('name', 'Domain Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', "$name", ['class' => 'form-control', 'id'=>"zName", 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('url', 'Domain URL*', ['class' => 'control-label']) !!}
                    {!! Form::text('url', $url, ['class' => 'form-control', 'id'=>"url", 'placeholder' => 'site.com', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('url'))
                        <p class="help-block">
                            {{ $errors->first('url') }}
                        </p>
                    @endif
                 </div>
            </div>
            
             <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                	<?php 
                		$user = DB::table('users')->get();
                	?> 
                    @if ($user[0]->id == auth()->user()->id)
                	{!! Form::label('url', 'Client', ['class' => 'control-label']) !!}
                    <select class="form-control" id="user_id" name="user_id" >
 
 						<?php  
 							foreach ($user as $key ) { ?>
 							@if($key->id > 1)	 
 							<option value="{{$key->id}}">{{$key->name}}</option>
 							@endif	
 						<?php	} ?>
                    </select>
                    @else 
                        <input type="hidden" name="user_id" value="<?= auth()->user()->id ?>">
                    @endif
                </div>
            </div>
            
            {!! Form::hidden('a_key','7302b26beb3438873cf29499591358fc', ['class' => 'form-control', 'id'=>"a_key", 'placeholder' => '', 'required' => '']) !!}


            <!--div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('a_key', 'Api Key*', ['class' => 'control-label']) !!}
                    
                    <p class="help-block"></p>
                    @if($errors->has('a_key'))
                        <p class="help-block">
                            {{ $errors->first('a_key') }}
                        </p>
                    @endif
                </div>
            </div-->

           </div>
           </div>
            <?php 
                $btnName = "Save";
                
                if(isset($_REQUEST['sbt']))
                    $btnName = $_REQUEST['sbt'];
                
           ?>   
           <input type="hidden" name="sbt" value="{{$btnName}}"> 
           <input type="hidden" name="updatedID" value="{{$updateID}}">
    {!! Form::submit(trans($btnName), ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}    {!! Form::close() !!}
 <script type="text/javascript">
        $('#user_id').val(<?= $userID ?>);
    </script>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
@stop

