<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use App\Zone;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function darkmode(Request $request){
        $value2 = $request->text;
        // return $value2;
    //     $servername = env('DB_HOST');
    //     $username = env('DB_USERNAME');
    //     $password = env('DB_PASSWORD');
    //     $dbname = env('DB_DATABASE');
    
    //     $conn = new mysqli($servername, $username, $password, $dbname);
    
    //     if ($conn->connect_error) {
    //       die("Connection failed: " . $conn->connect_error);
    //   }
    //     if(isset($_POST['submit'])){
    //         $value2 = $_POST['text'];
    //     $sql = "UPDATE darkmode SET darkmode='$value2' WHERE id=1";
        
    //     if (mysqli_query($conn, $sql)) {
    //         echo "Record updated successfully";
    //     die();
        
    //     } else {
    //         echo "Error updating record: " . mysqli_error($conn);
    //     }
    //     }
    //     // die();
        
    //     // die();
    //     // echo $counter2;
    // return $value2;
        return view('admin.zones.darkmode')->with('value2', $value2);
    }
    public function index()
    {
	$sucuri = DB::table('sucuri_user')->count();
	$reseller = DB::table('brandings')->count();
    //$user = DB::select('select * from student_details');
    $ided=auth()->user()->id;
       

        // echo auth()->user()->createToken('My Token')->accessToken;

        return view('home', ['sucuri'=>$sucuri, 'reseller'=>$reseller, 'user'=>$ided]);
    }

    public function my_account()
    {
	// $sucuri = DB::table('sucuri_user')->count();
	// $reseller = DB::table('brandings')->count();
    // //$user = DB::select('select * from student_details');
    // $ided=auth()->user()->id;
       

        // echo auth()->user()->createToken('My Token')->accessToken;

        // return view('home', ['sucuri'=>$sucuri, 'reseller'=>$reseller, 'user'=>$ided]);
    $id=auth()->user()->id;

        return view('auth.my_account', ['id'=> $id]);
    }
}
