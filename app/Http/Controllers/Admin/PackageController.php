<?php

namespace App\Http\Controllers\Admin;

use App\Package;
use App\User;
use App\Branding;
use App\Cfaccount;
use App\Spaccount; 

use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;


class PackageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $user = User::create($request->all());

//       $user->save(); 
        
         $pckg = DB::table('packages')->get();

        return view('admin.users.pacakge',compact('pckg'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        $abilities = Role::where("name","org")->first()->getAbilities();

        return view('admin.packages.create', compact('roles','abilities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 
         // $user = packages::create();
         


         // $user->save();
        //dd($request->all());
    if ($request->id == 0) {
        # code...
    

        $pckg = array('name' => $request->name,
                      'price' => $request->price,
                      'domains' => $request->domains,
                      'description' => $request->description,
                      'Show_Setting' =>$request->Show_Setting , 
                      'BlackList' => $request->BlackList ,
                      'Add_Delete_Site' => $request->Add_Delete_Site ,
                      'Clear_Cache' => $request->Clear_Cache ,
                      'Audit_Trails' => $request->Audit_Trails ,
                      'Protected_Pages' => $request->Protected_Pages ,
                      'Reports_Settings' => $request->Reports_Settings, );

       // $package->owner=auth()->user()->id;
 DB::table('packages')->insert($pckg);
       // $package->save(); 
       

         $pckg = DB::table('packages')->get();

        return view('admin.users.pacakge',compact('pckg'));     
    }
    else if($request->id > 0){
        $pckg = array('name' => $request->name,
                      'price' => $request->price,
                      'domains' => $request->domains,
                      'description' => $request->description,
                      'Show_Setting' =>$request->Show_Setting , 
                      'BlackList' => $request->BlackList ,
                      'Add_Delete_Site' => $request->Add_Delete_Site ,
                      'Clear_Cache' => $request->Clear_Cache ,
                      'Audit_Trails' => $request->Audit_Trails ,
                      'Protected_Pages' => $request->Protected_Pages ,
                      'Reports_Settings' => $request->Reports_Settings, );

        DB::table('packages')->where('id',$request->id)->update($pckg);
       

         $pckg = DB::table('packages')->get();

        return view('admin.users.pacakge',compact('pckg'));        
    }
            // $this->index();

//        return redirect()->route('admin.users.createPckg');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $packageFind = Package::findOrFail($id);
        // dd($packageFind->abilities);
        return view('admin.packages.edit', compact('packageFind'));
    }
    function deletePckg(Request $req){
        DB::table('packages')->where('id',$req->id)->delete();
          $pckg = DB::table('packages')->get();

        return view('admin.users.pacakge',compact('pckg'));        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $result = package::find($id); 
        $result->name= $request->get('name');
        $result->description = $request->get('description');
        $result->price = $request->get('price');
        $result->save();
        return redirect()->route('admin.packages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    { 
    } 
    function getDataById(Request $req){
        $id = $req->id;
        $pckgData = DB::table('packages')->where('id',$id)->get();
        $pckg = $pckgData[0];
        return view('admin.users.createPckg',compact('pckg'));        
    }
    public function manage(){
        $id = auth()->user()->id;
 
        return view('admin.users.createPckg');
    }
}
