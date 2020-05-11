@extends('layouts.app')

@section('content')
    <h3 class="page-title">My Account</h3>

    <?php
        // echo $id;
$servername = env('DB_HOST');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$dbname = env('DB_DATABASE');

$conn = mysqli_connect($servername, $username, $password, $dbname);
    

  $query1 = "SELECT * FROM users WHERE id = $id";
  $result1 = mysqli_query($conn, $query1);
   
  if (mysqli_num_rows($result1) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result1)) {
        
    ?>
    @if(session('success'))
        <!-- If password successfully show message -->
        <div class="row" style="background: white">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @else
        {{-- {!! Form::open(['method' => 'PATCH', 'route' => ['auth.my_account']]) !!}
        
        <!-- If no success message in flash session show change password form  --> --}}
        <form action="" method = "get">
        <div class="panel panel-default" style="background: white;">
            <div class="panel-heading">
                {{-- @lang('global.app_edit') --}}
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 form-group" style="width: 100%;">
                        {{-- {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                        {!! Form::text('username', ['class' => 'form-control', 'placeholder' => '']) !!} --}}
                        <label for="" class="control-label">Name*</label>
                        <input type="text" required name="name" value="<?php echo $row['name']; ?>" class="form-control">
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
                        {{-- {!! Form::label('current_password', 'Email*', ['class' => 'control-label']) !!}
                        {!! Form::password('email', ['class' => 'form-control', 'placeholder' => '']) !!} --}}
                        <label for="" class="control-label">Email*</label>
                        <input required type="text" name="email" value="<?php echo $row['email']; ?>" class="form-control">
                        <p class="help-block"></p>
                        @if($errors->has('email'))
                            <p class="help-block">
                                {{ $errors->first('email') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 form-group" style="width: 100%;">
                        {{-- {!! Form::label('current_password', 'Member Since*', ['class' => 'control-label']) !!}
                        {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => '']) !!} --}}
                        <label for="" class="control-label">Member Since*</label>
                        <input type="text" disabled name="created_at" value="<?php echo $row['created_at']; ?>" class="form-control">
                        <p class="help-block"></p>
                        @if($errors->has('created_at'))
                            <p class="help-block">
                                {{ $errors->first('created_at') }}
                            </p>
                        @endif
                    </div>
                </div>

         <!--       <div class="row">
                    <div class="col-xs-12 form-group" style="width: 100%;">
                        {{-- {!! Form::label('current_password', 'Current Password*', ['class' => 'control-label']) !!}
                        {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => '']) !!} --}}
                        <label for="" class="control-label">Password*</label>
                        <input type="password" required name="current_password" class="form-control">
                        <p class="help-block"></p>
                        @if($errors->has('current_password'))
                            <p class="help-block">
                                {{ $errors->first('current_password') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 form-group" style="width: 100%">
                        {{-- {!! Form::label('new_password', 'New Password*', ['class' => 'control-label']) !!}
                        {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => '']) !!} --}}
                        <label for="" class="control-label">New Password*</label>
                        <input type="new_password" name="name" class="form-control">
                        
                        <p class="help-block"></p>
                        @if($errors->has('new_password'))
                            <p class="help-block">
                                {{ $errors->first('new_password') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 form-group" style="width: 100%">
                        {{-- {!! Form::label('new_password_confirmation', 'New Password confirmation*', ['class' => 'control-label']) !!}
                        {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => '']) !!} --}}
                        <label for="" class="control-label">Confirm Password*</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                        
                        <p class="help-block"></p>
                        @if($errors->has('new_password_confirmation'))
                            <p class="help-block">
                                {{ $errors->first('new_password_confirmation') }}
                            </p>
                        @endif
                    </div>
                </div>
            -->
            </div>
        </div>

        {{-- {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!} --}}
        <input type="submit" value="Submit" name = "submit" class="btn btn-danger">
    </form>
    @endif
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <?php }}
    if(isset($_GET['submit'])){
echo        $name = $_GET['name'];
    echo    $email = $_GET['email'];

    $query2 = "UPDATE users set name = '$name', email = '$email'  WHERE id = $id";
    $result2 = mysqli_query($conn, $query2);
    if ($result2) {
        echo "<script>alert('Data Changed')</script>";
        echo '<meta http-equiv="refresh"
        content="2; url = /my_account" />';
    }else{
        echo "nope";
    }

}
    ?>

@stop

