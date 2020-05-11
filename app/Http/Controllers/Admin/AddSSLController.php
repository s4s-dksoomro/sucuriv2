<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;




	class AddSSLController extends Controller {
		function add(Request $request){
			
			

			$sucuri_user = DB::table('sucuri_user')->where('id',$request->id)->get();
		    
			
			foreach($sucuri_user as $users){	
			
				$auth_data = array(
    				'k' 		=> $users->a_key,
    				's' 		=> $users->s_key,
    				'a' 	=> 'add_certificate',
    				// "private_key" => $users->id,
    				"ssl_certificate" => "SSL_CERTIFICATE",
    				'format' =>  'json'
				);
			} 

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
			curl_setopt($curl, CURLOPT_URL, 'https://waf.sucuri.net/api?v2');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$result = curl_exec($curl);
			$result = json_decode($result , true );
			// print_r($result);
			$ApiMessage="";
			$index=0;
			foreach($result as $ok => $data)
			{	$index++;
				if($index == 3){
					foreach ($data as $message) {
						$ApiMessage=$message;
					}
				}
			}
			$sucuri_users = DB::table("sucuri_user")->get();
			return view('admin.zones.create',['sucuri_user'=>$sucuri_users])->with('message',$ApiMessage); 
		}

		function clearCache( Request $request){
			
			$sucuri = DB::table("sucuri_user")->where('id' , $request->id)->get();



			foreach($sucuri as $users){	
			
				$auth_data = array(
    				'k' 		=> $users->a_key,
    				's' 		=> $users->s_key,
    				'a' 	=> 'clear_cache',
				);
			} 



// 			curl 'https://waf.sucuri.net/api?v2' \
// --data 'k=API_KEY' \
// --data 's=API_SECRET' \
// --data 'a=clear_cache'

			$curl = curl_init();
			$ch = "https://waf.sucuri.net/api?v2";
			// curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
			curl_setopt($curl, CURLOPT_URL, $ch);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$result = curl_exec($curl);

			//$sucuri_users = DB::table("sucuri_user")->get();

 
			if(empty($result)){
				return view('admin.zones.crypto',['sucuri_user'=>''])->with('cache','Cache Not Cleared');
			}
			else{
				return view('admin.zones.crypto',['sucuri_user'=>$sucuri])->with('cache','Cache Cleared');
			}

		}
	}


?>