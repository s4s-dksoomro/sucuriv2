@inject('request', 'Illuminate\Http\Request')

@extends('layouts.app')
@section('content')


<?php
 $ip;
//echo $sucuri_users;
// $result=json_decode($ok);
//$messages = $result->messages;
 //print_r($result->output);  ?>
<div class="row">
                <div class="col-xs-12">
                    <h2>White List IP</h2>
                    <div class="panel panel-success">
                        {{-- <div class="panel-heading"><h3>{{$result->output->domain}}</h3></div> --}}
                        <div class="panel-body">

                            
                            <h4>Remove Whitlisted IP</h4>
                            <table class="table table-bordered">
            <tbody>
                <tr>
                
                    <td><b>White Listed IP Address:</b> </td>

                    <td><b> {{ $ip }} </b></td>
                    <td>
                            <center>
                            <form method="get" action="remove">
								<input type="text" value="{{ $ip }}">
                                <input type="submit" value="Remove From Whitelisted IPs" class="btn btn-danger" >
                            </form>
                            </center>

                    </td>
                            <?php

//echo $sucuri_users;

                            function display()
                            {
                                $sucuri_users;
                                //$users      = Sucuri::where('id',$sucuri_users)->get();
                                $users  = DB::table('sucuri_user')->where('id',$sucuri_users)->get();
                                // dump($users->name); 
                                foreach($users as $user){
                                // return "$user->name";
                                $s_key = "$user->s_key";
                                    $a_key= "$user->a_key";
                                } 


                                $curl = curl_init();
                                $auth_data = array(
                                'k' 		=> $a_key,
                                's' 		=> $s_key,
                                'a' 	=> 'delete_whitelist_ip',
                                'ip'    =>  $ip,
                                'format' =>  'json'
                                
                                );
                                curl_setopt($curl, CURLOPT_POST, 1);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
                                curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                $result = curl_exec($curl);
                                if(!$result){die("Connection Failure");}
                                curl_close($curl);
                                //$result1 = utf8_encode($result);
                                //$result2 =json_decode($result1);
                                //$result=array($result);
                                $result;
                            }
                            if($_SERVER['REQUEST_METHOD']=='POST')
                            {
                                   display();
                            } 
                            ?>
                    </td>
                </tr>

          

           
            </tbody>
        </table>

        

                        </div>
                    </div>
                </div>
            </div>









@stop

@section('javascript') 
    
@endsection
