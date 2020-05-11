@extends('layouts.app')

@section('content')
<h3 style="font-size: 20px !important;"> Package</h3>

    {!! Form::open(['method' => 'POST', 'route' => ['admin.pckg.store'], 'onsubmit' => 'myFunction()']) !!}

    <div class="panel panel-default" style="background: white;">
        <div class="panel-heading"> 
        </div>
        
        <?php 
          $name= "";
          $price = 0;
          $domains = 0;
          $description  = "";
          $Show_Setting = 0;
          $BlackList = 0;
          $Add_Delete_Site = 0;
          $Clear_Cache = 0;
          $Audit_Trails = 0;
          $Protected_Pages = 0;
          $Reports_Settings = 0;
          $btnName = "Save";                    
          $id = 0 ;
          if(isset($pckg)){
              $name = $pckg->name;
              $price = $pckg->price;
              $domains = $pckg->domains;
              $description = $pckg->description;
              $Show_Setting = $pckg->Show_Setting;
              $BlackList = $pckg->BlackList;
              $Add_Delete_Site = $pckg->Add_Delete_Site;
              $Clear_Cache = $pckg->Clear_Cache;
              $Audit_Trails = $pckg->Audit_Trails;
              $Protected_Pages = $pckg->Protected_Pages;
              $Reports_Settings = $pckg->Reports_Settings;
              $btnName = "Update";
              $id = $pckg->id;
          } 

        ?>
  <input type="hidden" name="id" value="{{$id}}" />
        <div class="panel-body">
            <div class="row">  
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('Name', 'Package Name*', ['class' => 'control-label']) !!}  
                    {!! Form::text('name', $name, ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::label('Price', 'Price', ['class' => 'control-label']) !!}
                    {!! Form::text('price', $price, ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif 
                </div>
            </div>
            
            <div class="row" >
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('domains', 'No. of Domains*', ['class' => 'control-label']) !!}
                    {{-- <label class="form-control" for="domains">No of domains</label> --}}
                    <input type="number" name="domains" placeholder="Enter domains here..." value="{{ $domains }}" class="form-control" required> 
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group" style="width: 100%;">
                    {!! Form::label('Description', 'Description*', ['class' => 'control-label']) !!}
                    {{-- <label class="form-control" for="domains">No of domains</label> --}}
                    <textarea name="description" id="" cols="20" rows="10" class="form-control" placeholder="Enter description here...">{{$description}}</textarea>
                </div>
            </div>

             <div  class="row">
                     <div class="col-xs-12 " style="width: 100%;">

<table >
                       
  <table  class="table table-bordered" >
    <tr>
    <td>
    Show Setting 
    </td>
    <td>
      <label class="switch"> 
        <input type="checkbox" value="1" <?php if($Show_Setting > 0){?> checked <?php } ?> name="Show_Setting">
        <span class="slider round"></span>
    </label>
      {{-- <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger" data-on="Yes" data-off="No" value="1" name="Show_Setting"   > --}}
      
    
    </td>
    
    <td>
    BlackList/WhiteList 
    </td>
    <td>
      <label class="switch">
        <input type="checkbox" value="1" <?php if($BlackList > 0){?> checked <?php } ?> name="BlackList">
        <span class="slider round"></span>
    </label>
      {{-- <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger" data-on="Yes" data-off="No" value="1" name="BlackList"  > --}}
    
    </td>
    
    
    
    <td>
    Add Delete Site 
    </td><td>
      <label class="switch">
        <input type="checkbox" value="1" <?php if($Add_Delete_Site > 0){?> checked <?php } ?> name="Add_Delete_Site">
        <span class="slider round"></span>
    </label>
      {{-- <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger" data-on="Yes" data-off="No" value="1" name="Add_Delete_Site" > --}}
    
    </td>
    
    <td>
    Clear Cache 
    </td><td>
      <label class="switch">
        <input type="checkbox" value="1" <?php if($Clear_Cache > 0){?> checked <?php } ?> name="Clear_Cache">
        <span class="slider round"></span>
    </label>
      {{-- <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger" data-on="Yes" data-off="No" value="1" name="Clear_Cache" > --}}
    
    </td>
    </tr>
    
    <tr>
    
    <td>
    Audit Trails 
    </td>
    <td>
      <label class="switch">
        <input type="checkbox" value="1" <?php if($Audit_Trails > 0){?> checked <?php } ?> name="Audit_Trails">
        <span class="slider round"></span>
    </label>
      {{-- <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger" data-on="Yes" data-off="No" value="1" name="Audit_Trails" > --}}
    </td>
    
    
    
    <td>
    Protected Pages 
    </td><td>
      <label class="switch">
        <input type="checkbox" value="1" <?php if($Protected_Pages > 0){?> checked <?php } ?> name="Protected_Pages">
        <span class="slider round"></span>
    </label>
      {{-- <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger" data-on="Yes" data-off="No" value="1" name="Protected_Pages" > --}}
    
    </td>
    <td>
    Reports Settings 
    </td>
    <td>
      <label class="switch">
        <input type="checkbox"  value="1" <?php if($Reports_Settings > 0){?> checked <?php } ?> name="Reports_Settings"> 
        <span class="slider round"></span>
    </label>

      {{-- <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger" data-on="Yes" data-off="No" value="1" name="Reports_Settings" > --}}
    </td>
    
    </tr>
                        <p class="help-block"></p>
                        @if($errors->has('abilities'))
                            <p class="help-block">
                                {{ $errors->first('abilities') }}
                            </p>
                        @endif
                    </div>
                </div>
                
    
                
            </div>
        </div>
        </table>
         
        <br>
        <br> 
        {!! Form::submit( $btnName , ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
      </div>
    </div>
    </div>
    </div>
    </div>
    
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
      function myFunction() {
          alert("Done...!!!");
      }
  </script>
@stop

