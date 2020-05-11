<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Branding;
use App\Cfaccount;
use App\Spaccount;

use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;



class SettingsController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        
        // dd(auth()->user()->email);
        $users = User::whereIs('subUser')->where('owner',auth()->user()->id)->where('email','NOT LIKE', "%__".auth()->user()->email)->with('roles')->get();
        
       
        
        return view('admin.subUsers.index', compact('users'));
    }



    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $roles = Role::get()->pluck('name', 'name');

        $abilities = Role::where("name","org")->first()->getAbilities();

        return view('admin.subUsers.create', compact('roles','abilities'));
    }

  

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {
        

        
        $user = User::create($request->all());

        
       $user->owner=auth()->user()->id;

       $user->save();


        foreach ($request->input('abilities') as $ability=>$permission) {
             if($permission==0)
             {
                $user->forbid($ability);
                $user->forbid('view_'.$ability);
                $user->forbid('edit_'.$ability);
             }
             elseif($permission==1)
             {  
                $user->forbid('edit_'.$ability);
                
                $user->unforbid($ability);
                $user->allow('view_'.$ability);
             }
             elseif($permission==2)
             {
                $user->unforbid($ability);
                $user->allow('edit_'.$ability);
             }
             
        }


        // foreach ($request->input('roles') as $role) {
             $user->assign('subUser');
        // }

        return redirect()->route('admin.subUsers.index');
    }
 


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       /* if (! Gate::allows('users_manage')) {
            return abort(401);
        }*/

        $user = User::findOrFail($id);

        if ( $user->owner!= auth()->user()->id) {
            return abort(401);
        }

        $roles = Role::get()->pluck('name', 'name');

        //dd();
        // $abilities = Ability::get()->pluck('name', 'name');
        // 
         $abilities = Role::where("name","org")->first()->getAbilities();

        // foreach ($abilities as $ability) {
        //     # code...
        //     # 
        //     echo($ability->name);
        // }
        // die();


       

        return view('admin.subUsers.edit', compact('user', 'roles','abilities'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       /* if (! Gate::allows('users_manage')) {
            return abort(401);
        }*/
        $user = User::findOrFail($id);
        if ( $user->owner!= auth()->user()->id) {
            return abort(401);
        }
        
        $user->update($request->all());

        if(isset($request->password))
        {
             //dd($request->password);
            $user->password_updated = 1; 
             $user->save();   
        }
        
       
        // dd($user->can("reseller_access"));
       foreach ($user->getAbilities() as $ability) {
            $user->forbid($ability->name);
        }

        //dd($request->input('abilities'));
        //

        if($request->input('abilities')!=null)
        {
            foreach ($request->input('abilities') as $ability) {
                 $user->unforbid($ability);
                 $user->allow($ability);
            }
             \Bouncer::refreshFor($user);
        }

        


        return redirect()->route('admin.subUsers.index');
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.subUsers.index');
    }

    






}
