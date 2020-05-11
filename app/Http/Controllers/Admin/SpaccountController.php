<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\UpdateAbilitiesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Silber\Bouncer\Database\Ability;

use App\Spaccount;
use App\User;

use App\Zone;

use App\Jobs\FetchSpZones;

use App\Jobs\FetchZoneDetails;
use App\Jobs\FetchSpZoneSetting;
use App\Jobs\FetchDns;
use App\Jobs\FetchWAFPackages;
use App\Jobs\FetchAnalytics;
use App\Jobs\FetchFirewallRules;



class SpaccountController extends Controller
{
    /**
     * Display a listing of Abilities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        //$spAccounts = Spaccount::all();
        $spAccounts = User::findOrFail(auth()->user()->id)->spaccount;

        return view('admin.spaccounts.index', compact('spAccounts'));
    }




    public function importZones(Spaccount $spaccount)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }  


        //dd(auth()->user()->id);
        if(!(auth()->user()->id == $spaccount->reseller->id OR auth()->user()->id == 1))
        {
            return abort(401);
        }

        $FetchZones= new FetchSpZones($spaccount);
        $zones=$FetchZones->handle();
       // dd($zones);
        $existingZones= Zone::all();
        

        foreach ($zones as $zone) {
            $zone->exists=$existingZones->contains('name',$zone->cdn_url);
            if($zone->exists)
            {
                $zone->existing=$existingZones->where('name',$zone->cdn_url)->first();
            }
            
        }
       // dd($zones);

        $users = User::all();

        return view('admin.spaccounts.import', compact('users','spaccount','zones','existingZones'));
    }



    public function doImport(Request $request)
    {
        //
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }



       // var_dump((int)$request->spaccount);
 
            $zone = Zone::create([
                "name"         => $request->name,
                "zone_id"      => $request->zone_id,
                "spaccount_id"  => (int)$request->spaccount,
                "user_id"      => $request->userID,
                "cfaccount_id" => 0
            ]);

 // dd($zone);
        FetchSpZoneSetting::dispatch($zone);

           
        //     FetchZoneDetails::dispatch($zone);

        //     FetchZoneSetting::dispatch($zone);
        // FetchDns::dispatch($zone);
        // FetchWAFPackages::dispatch($zone);
        // FetchAnalytics::dispatch($zone);
        // FetchFirewallRules::dispatch($zone);

            // $request->session()->flash('status', 'Zone Created Successfully! Please update the DNS at domain registrar for '.$request->name ." to <b>".$result->name_servers[0]."</b> & <b>".$result->name_servers[1]."</b>");
            
        return response($request->name." imported Successfully and assigned to ".User::where('id',$request->userID)->first()->email);


        //$user = User::create($request->all());

        // foreach ($request->input('roles') as $role) {
        //     $user->assign($role);
        // }
        


        //return redirect()->route('admin.zones.index');

    }

    /**
     * Show the form for creating new Ability.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        return view('admin.spaccounts.create');
    }

    /**
     * Store a newly created Ability in storage.
     *
     * @param  \App\Http\Requests\StoreAbilitiesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $spaccount=Spaccount::create($request->except('_token'));

        $spaccount->reseller_id=auth()->user()->id;
        $spaccount->save();

        return redirect()->route('admin.spaccounts.index');
    }


    /**
     * Show the form for editing Ability.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $ability = Ability::findOrFail($id);

        return view('admin.abilities.edit', compact('ability'));
    }

    /**
     * Update Ability in storage.
     *
     * @param  \App\Http\Requests\UpdateAbilitiesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAbilitiesRequest $request, $id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $ability = Ability::findOrFail($id);
        $ability->update($request->all());

        return redirect()->route('admin.abilities.index');
    }


    /**
     * Remove Ability from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $ability = Ability::findOrFail($id);
        $ability->delete();

        return redirect()->route('admin.abilities.index');
    }

    /**
     * Delete all selected Ability at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Ability::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
