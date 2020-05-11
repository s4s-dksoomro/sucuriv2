<?php

namespace App\Http\Controllers\API;

use App\Package;
use App\User;
use App\Branding;
use App\Cfaccount;
use App\Spaccount;
use Response;

use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PackageRequest;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class PackageController extends Controller
{
    protected $request;
    protected $Package;
    public function __construct(Request $request, Package $Package) {
        $this->request = $request;
        $this->Package = $Package;
    }
	// public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        
        $Package = $this->Package->all();
        return response()->json(['data' => $Package,
            'status' => 200]);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //  $data = $this->request->all();
        // $this->Package->name = $data['name'];
        // $this->Package->description = $data['description'];
        // $this->Package->price = $data['price'];
        // $this->Package->save();
        
        // return response()->json(['status' => 201]);
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
        
       
       //   $data = $this->request->all();
       // // dd($data);
       //  $Package = $this->Package->find($id);
       //  // 
       //  $Package->name = $data['name'];
       //  $Package->description = $data['description'];
       //  $Package->price = $data['price'];
       //  $Package->save();

        
        
       //  return response()->json(['status' => 200]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $package = $this->Package->find($id);
        // $package->delete();
        
        // return response()->json(['status' => 200]);
       
    }
}
