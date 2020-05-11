<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\SelectedDate;
// use SelectedDate;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redirect;




// include 'date.php';


	class SucuriController extends Controller {
		
		public $date = "";

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

			$curl = curl_init();
			$ch = "https://waf.sucuri.net/api?v2";
			// curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
			curl_setopt($curl, CURLOPT_URL, $ch);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$result = curl_exec($curl);

			$sucuri_users = DB::table("sucuri_user")->get();

 
			if(empty($result)){
				return view('admin.zones.crypto',['sucuri_user'=>''])->with('cache','Cache Not Cleared');
			}
			else{
				return view('admin.zones.crypto',['sucuri_user'=>$sucuri_users])->with('cache','Cache Cleared');
			}

		}

		function auditTrails(Request $request){


			// return $msg;

			$sucuri = DB::table("sucuri_user")->where('id' , $request->id)->get();

			$date = date('d/M/Y');

			if(!empty($request->date)){
				$date = $request->date;
			}
			// $date = date('d/M/Y', strtotime($date));
			// echo $date;
			// die();
		$auth_data=array();


		// return $request->date." ok";
  
			foreach($sucuri as $users){	
			
				$auth_data = array(
    				'k' 		=> $users->a_key,
    				's' 		=> $users->s_key,
    				'a'	=>		'audit_trails',
    				'date' 	=> $date,
    				'format'=>'json',
    				 'offset' => '1',
    				'limit' => '500'
				); 
			}

			$curl = curl_init();
			$ch = "https://waf.sucuri.net/api?v2";
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
			curl_setopt($curl, CURLOPT_URL, $ch);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$result = curl_exec($curl);

			$sucuri_users = DB::table("sucuri_user")->get();
			
			$result = json_decode($result , true);
	
			$check = array();

		if( !empty($result['output']['access_logs'] )){

			$check = $result['output']['access_logs'];
 
 		}
 		// return $result;
 		return view('admin.zones.trashed',['result'=>$check])->with('id',$request->id); 
	} 

	function ok(Request $request){
		

			$sucuri = DB::table("sucuri_user")->where('id' , $request->id)->get();
			$date="";	

			if(!empty($request->date)){
				$date = $request->date;
			}

			
 		// return $check;  
 		// return view('AuditTrails/1',['result'=>$check])->with('id',$request->id);
 		// return Redirect::back();
 
 		return redirect('trails/1/'.$date); 
 

	}


		function reports(Request $request){
			$sucuri = DB::table("sucuri_user")->where('id' , $request->id)->get();

			foreach($sucuri as $users){	
			
				$auth_data = array(
    				'k' 		=> $users->a_key,
    				's' 		=> $users->s_key,
    				'a' 	=>	'email_reports_settings',
    				'status' => 'enabled',
    				'period' => 'day',
    				'format'=>'pdf',
    				'offser' => '0',
    				'emails'=>'user@domain1.tld,user@domain2.tld,user@domainn.tld'
				); 
			} 

			$curl = curl_init();
			$ch = "https://waf.sucuri.net/api?v2";
			// curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
			curl_setopt($curl, CURLOPT_URL, $ch);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$result = curl_exec($curl);

			$sucuri_users = DB::table("sucuri_user")->get();
			
			$result = json_decode($result , true);
			$message="";
			$index=0;
			foreach($result as $ok => $data)
			{	$index++;
				if($index == 3){
					foreach ($data as $message) {
						$this->message= $message;
					}
				}
			}
			$sucuri_user = DB::table("sucuri_user")->where('id' , $request->id)->get();
			return view('admin.zones.origin',['sucuri_user'=>$sucuri_users])->with('audit',$message);
			// return "ok";
	}

	// function check(Request $request){
	// 	return "ok";
	// }


}
