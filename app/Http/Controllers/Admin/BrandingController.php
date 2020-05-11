<?php
namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\UpdateAbilitiesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Silber\Bouncer\Database\Ability;

use App\Cfaccount;
use App\User;
use App\Branding;
use App\Zone;

use App\Jobs\FetchZones;
use Illuminate\Support\Facades\Auth;
use App\Jobs\FetchZoneDetails;
use App\Jobs\FetchZoneSetting;
use App\Jobs\FetchDns;
use App\Jobs\FetchWAFPackages;
use App\Jobs\FetchAnalytics;
use App\Jobs\FetchFirewallRules;



class BrandingController extends Controller
{


    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Where to redirect users after password is changed.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/branding';

    /**
     * Change password form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBrandingForm()
    {


        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $branding = Auth::getUser()->branding;

        // dd($branding);

        return view('admin.branding', compact('branding'));
    }

    public function showTokens()
    {


        if (! Gate::allows('users_manage')) {
            //return abort(401);
        }

        // dd(auth()->user()->getToken());
        // $token=auth()->user()->createToken('My Token');
        // dd($token);
    $tokens=auth()->user()->tokens()->get();
        // $branding = Auth::getUser()->branding;

        // dd($tokens);
        $token="";
        return view('admin.tokens.index', compact('tokens','token'));
    }


    public function createToken()
    {
        if (! Gate::allows('users_manage')) {
            //return abort(401);
        }

        return view('admin.tokens.create');
    }


    public function storeToken(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            //return abort(401);
        }


        $token=auth()->user()->createToken($request->get('name'))->accessToken;
        $tokens=auth()->user()->tokens()->get();
        return view('admin.tokens.index',compact('tokens','token'));
    }


      public function destroyToken(Request $request)
    {


        if (! Gate::allows('users_manage')) {
            // return abort(401);
        }

  // dd($request->get('id'));
  $token= auth()->user()->tokens->find($request->get('id'));
  $token->revoke();
  $token->delete();




        return redirect()->route('admin.token');
    }
    /**
     * Change password.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateBranding(Request $request)
    {
       $branding = Auth::getUser()->branding;
        //$this->validator($request->all())->validate();
       $data=$request->all();
       if($request->hasFile('logo')){

           // echo 'Uploaded';
            // $file = $request->file('logo');
            // $fileupload=$file->move('uploads', uniqid('logo_'));
            // dd($fileupload);
           // echo '';

            // \Matriphe\Imageupload\
            $upload=\Imageupload::upload($request->file('logo'));
            $data['logo']=$upload['dimensions']['logo']['filedir'];
            // dd($upload['dimensions']['logo']['filedir']);
        }

            if($branding)
            {
                 $branding->update($data);
            }
            else
            {

                $data['user_id']=Auth::getUser()->id;
                 Branding::create($data);
            }

            // $user->password = $request->get('new_password');
            // $user->save();
            return redirect($this->redirectTo)->with('success', 'Branding changed successfully!');

    }

    /**
     * Get a validator for an incoming change password request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
    }
}
