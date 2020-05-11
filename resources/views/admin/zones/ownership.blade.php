@extends('layouts.app')

@section('content')
    <h3 class="page-title">Ownership Modification</h3>
    


    <form method="post">


           {{ csrf_field() }}
    <div class="panel panel-default">
        <div class="panel-heading">
            Change Ownership of  {{ $zone->name }}
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                   Current Owner: @if($zone->user) {{ $zone->user->name }} ({{ $zone->user->email }}) @else Not Assigned to any user @endif
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 form-group">
                    <select class="form-control select2 user" required=""  name="user">
                    @foreach($users as $user)


                    @if($user->id != 1)

                        <option @if($user->email == $zone->user->email) selected="selected" @endif  value="{{$user->id}}">
                            {{$user->email}} ( {{ $user->zone->count() }} Zones )
                        </option>

                        @endif
                    @endforeach
                </select>
                </div>
            </div>
            

            <div class="row">
                <div class="col-xs-12 form-group">
                    <input class="btn btn-success" type="submit" value="Change Ownership">
                </div>
            </div>
            
        </div>
    </div>


</form>
   
@stop

