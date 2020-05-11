@can('users_manage')


 <div class="topbar">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <div class="logo">
                        <a href="/admin/home"><img src="{{ asset("images/bd-logo-white.png") }}" alt="BlockDOS"></a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="topbar-links">
                        <ul>

                            <?php // Allow Account creation for the organization admin only. 
           ?>
           
                <li><a href="{{ url('logout') }}">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="title">@lang('global.app_logout')</span>
                </a>
            </li>
                           
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Logo -->
   
        
   
@else

<?php
$user=\App\User::find(auth()->user()->id);
if($user->owner!=1)
    {

        if(\App\User::find($user->owner)->isAn('reseller'))
        {
            $logo=\App\User::find($user->owner)->branding->logo;
        }
        elseif(\App\User::find(\App\User::find($user->owner)->owner)->isAn('reseller'))
        {
            $logo=\App\User::find(\App\User::find($user->owner)->owner)->branding->logo;
        }
        else
        {
            $logo='images/bd-logo-white.png';
        }

        
    }
    else
    {
        $logo='images/bd-logo-white.png';
    }  

    if($logo=="")
    {
        $logo='images/bd-logo-white.png';
    }

    ?>


    <div class="topbar">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <div class="logo">
                        <a href="/admin/home"><img src="{{ asset($logo) }}" alt="BlockDOS"></a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="domainSelectorDiv">
                       <select style="width:200px;" class="select2" id="changeZone" name="changeZone">
                <?php

            $user=\App\User::find(auth()->user()->id);
                         $allowedZone =  $request->session()->get('zone', null);

            
            ?>
             @if($allowedZone!=null)
                  <option {{{ (Request::is('*/'.$allowedZone.'/*') ? 'selected="selected"' : '') }}}  value="{{ \App\Zone::where('name',$allowedZone)->first()->id }}">{{ $allowedZone }}</option>
                
            @elseif($user->owner!=1 AND \App\User::find($user->owner)->isNotAn('reseller'))
                
                @foreach(\App\Zone::where('user_id',$user->owner)->get() as $zone)
                <option {{{ (Request::is('*/'.$zone->name.'/*') ? 'selected="selected"' : '') }}}  value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach
               

            @else
            <?php $ids=\App\User::where('owner',auth()->user()->id)->pluck('id')->toArray();
            // dd($ids);
            $ids[]=auth()->user()->id;

             ?>
                @foreach(\App\Zone::whereIn('user_id',$ids)->get() as $zone)
                <option {{{ (Request::is('*/'.$zone->name.'/*') ? 'selected="selected"' : '') }}}  value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach

            @endif
                
                @if(Request::is('*/*/*'))
                
                @else
                    <option selected="">Select Zone</option>
                @endif
               
            </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="topbar-links">
                        <ul>

                            <?php // Allow Account creation for the organization admin only. 
           ?>
            @if($user->owner==1 OR \App\User::find($user->owner)->isAn('reseller')) 
                <li><a  href="{{ url('admin/settings') }}">
                    <i class="fas fa-cog"></i>
                    <span class="title">Account</span>
                </a>
            </li>
            @endif
                <li><a href="{{ url('logout') }}">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="title">@lang('global.app_logout')</span>
                </a>
            </li>
                           
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endcan

