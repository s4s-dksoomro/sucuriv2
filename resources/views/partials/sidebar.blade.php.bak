@inject('request', 'Illuminate\Http\Request')

<aside class="main-sidebar" id = "sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" id = "sidebar">
        <center>
            <div class="user-box" id = "sidebar">
                <div class="user-img">
                    
                   <a href="{{ url('/') }}" class="user-edit"><img style="width: 150px; " src="{{asset('images/bd-logo-white.png')}}" alt="user-image" ></a>
                </div>
        <br>
                <h5><a href="javascript: void(0);">
                    <?php
                                            $user_id=auth()->user()->id;
                                            // echo $ided;
                                            $users  = DB::table('users')->where('id',$user_id)->get();
                                            //  echo $users;
                                            foreach($users as $user){
                                                $name = $user->name;
                                            } 
                                            echo $name; 
                                        ?>  
                </a> </h5>
                {{-- <p class="text-muted mb-0"><small>Admin Head</small></p> --}}
                <br>
                <a  class=" notify-item">
                    <i class="fe-settings"></i>
                 </a> &nbsp;
                 <a href="{{ url('logout') }}" class="notify-item">
                    <i class="mdi mdi-power"></i>
                </a>
            </div>
        </center>
        
        <hr>

        <ul class="sidebar-menu ">
            <li class="menu-title">Navigation</li>
            <br>
            <li class=" {{ $request->segment(2) == 'home' ? 'active' : '' }}">
                <a id="anchor" href="{{ url('/') }}">
                    <i class="mdi mdi-view-dashboard"> </i> 
                    <span style="padding-left: 16px;" class="title">Dashboard</span>
                    <span class="pull-right-container"></span>

                </a>
            </li>
            @if(auth()->user()->id==1)
             <li class="{{ $request->segment(2) == 'resellers' ? 'active' : '' }}" >
                <a  id="anchor" href="{{ route('admin.listResellers') }}" >
                    <i class="fa fa-users"></i>
                    <span style="padding-left: 10px;" class="title">Resellers</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>

            <li class="{{ $request->segment(2) == 'pending' ? 'active' : '' }}">
                <a id="anchor" href="{{ route('admin.pending') }}">
                    <i class="far fa-address-book"></i>
                    <span style="padding-left: 15px;" class="title">Pending Request</span>
                    <span class="pull-right-container"></span>
                </a> 
                
            </li>
            
            <li class="{{ $request->segment(2) == 'delete' ? 'active' : '' }}">
                <a id="anchor" href="{{ route('admin.delete') }}">
                    <i class="fas fa-landmark"></i>
                    <span style="padding-left: 13px;" class="title">Delete Request</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            @endif
            
            <li class="{{ $request->segment(2) == 'zones' ? 'active' : '' }}">
                <a id="anchor" href="{{ route('admin.zones.index') }}">
                    <i class="far fa-user"></i>
                    <span style="padding-left: 15px;" class="title">All Sucuri Accounts</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>

            <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a id="anchor" href="{{ route('auth.change_password') }}">
                    <i class=" fas fa-unlock-alt"></i>
                    <span style="padding-left: 15px;" class="title">Change password</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>

            <li>
                <a id="anchor" href="{{ url('logout') }}" >
                    <i class="fe-log-out"></i> &nbsp;
                    <span style="padding-left: 8px;" class="title">@lang('global.app_logout')</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
           <li>
               <center>

                <table>
                    <tr>
                        <td>
                            <label for="" style="padding: 10px">DARK MODE:</label>
                        </td>
                        <td> 
                            <label class="switch">
                                <input type="checkbox"  onchange="changeHeadingBg('black');">
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                </table>
                  {{-- <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger"   onchange="changeHeadingBg('black');"> --}}

                {{-- <button type="button" class="btn btn-danger" onclick="changeHeadingBg('black');">Turn To Dark Mode</button> --}}
               </center><br>    
               <br>    <br>
               <br>    <br>
            </li>
            
        </ul>
        <script>
           // Function to change webpage background color
           function changeHeadingBg(color){
                var element = document.getElementById("sidebar");
                element.classList.toggle("dark-mode");
             // document.getElementById("sidebar").style.background = color;
            }
            </script>
    </section>

</aside>