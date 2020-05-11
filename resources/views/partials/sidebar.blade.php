@inject('request', 'Illuminate\Http\Request')

<?php 
$servername = env('DB_HOST');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$dbname = env('DB_DATABASE');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$value = "";
$query2 = "SELECT * FROM darkmode WHERE id = 1";
$result = mysqli_query($conn, $query2);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        // echo "darkmode: " . $row["darkmode"];
        $value = $row["darkmode"];
    }
} else {
    echo "0 results";

}

?>
    
<aside class="main-sidebar" id = "sidebar" 
<?php
    if($value == 'checked'){
        echo "style='background: black;'";
    }else{
        echo "style='background: white;'";

    }
?>
>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" id = "sidebar">
        <center>
            <div class="user-box" id = "sidebar">
                <div class="user-img">
                   
                @if(auth()->user()->id!=1)

<?php            

$id = auth()->user()->id;
$user  = DB::table('brandings')->where('user_id',$id)->get();

 $image= $user[0]->logo;

?> 


@if($image==null)
<a href="/admin/home">  <img style="width: 150px; " src="{{ asset("images/bd-logo-white.png") }}" alt="BlockDos" > </a>

@else
<a href="/admin/home">  <img  style="width: 150px; " src="{{ asset('images/') }}/<?php echo $image; ?>" alt="BlockDos" ></a>
@endif

@else

<a href="/admin/home">  <img style="width: 150px; " src="{{ asset("images/bd-logo-white.png") }}" alt="BlockDos" > </a>


@endif
  

                </div>

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
                <!--a  class=" notify-item">
                    <i class="fe-settings"></i>
                 </a--> &nbsp;
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
            <li class="{{ $request->segment(2) == 'zones' ? 'active' : '' }}">
                <a id="anchor" href="{{ route('admin.zones.index') }}">
                    <i class="far fa-user"></i>
                    <span style="padding-left: 15px;" class="title">All Sucuri Accounts</span>
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
            <li class="{{ $request->segment(2) == 'rejected' ? 'active' : '' }}">
                <a id="anchor" href="{{ route('admin.rejected') }}">
                    <i class="fas fa-landmark"></i>
                    <span style="padding-left: 13px;" class="title">Rejected Request</span>
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
            <li class="{{ $request->segment(2) == 'pacakge' ? 'active' : '' }}">
                <a id="anchor" href="{{ route('admin.pacakge') }}">
                    <i class="fa fa-users"></i>
                    <span style="padding-left: 8px;" class="title">Packages</span>
                    <span class="pull-right-container"></span>
                </a> 
                
            </li>
           
            @endif
            
          

            <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a id="anchor" href="{{ route('auth.change_password') }}">
                    <i class=" fas fa-unlock-alt"></i>
                    <span style="padding-left: 15px;" class="title">Change password</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>

            {{-- <li>
                <a id="anchor" href="{{ url('logout') }}" >
                    <i class="fe-log-out"></i> &nbsp;
                    <span style="padding-left: 8px;" class="title">@lang('global.app_logout')</span>
                    <span class="pull-right-container"></span>
                </a>
            </li> --}}
            <li>&nbsp;</li>
            <li>&nbsp;</li>
           <li>
               <center>

                <table>
                    <tr>
                        <td>
                            <label for="" 
                            <?php
                            if($value == 'checked'){
                                echo "style='padding: 10px; color:white; !important'";
                            }else{
                                echo "style='padding: 10px'";
                        
                            }
                        ?> >DARK MODE:</label>
                        </td>


<!--                        <td> 
                            <label class="switch">
                                <input type="checkbox"  onchange="changeHeadingBg('black');" <?php echo $value;?>>
                                <span class="slider round"></span>
                            </label>
                        </td> -->
                        <td>
                            <form action="darkmode" method="get">
                                <input type="hidden" name='text' value = '<?php if($value == "checked"){
                                    echo "not checked";
                                }else{
                                    echo "checked";
                            
                                }?>' >
                                <input type="submit" class="btn <?php
                                if($value == 'checked'){
                                    echo "btn-danger";
                                }else{
                                    echo "btn-primary";
                            
                                }
                            ?>" name = "submit" value="<?php
                                if($value == 'checked'){
                                    echo "Disable";
                                }else{
                                    echo "Enable";
                            
                                }
                            ?>" >
                            </form>
                        <?php
                        if(isset($_GET['submit'])){
                            $value2 = $_GET['text'];
                        // $sql = "UPDATE darkmode SET darkmode='$value2' WHERE id=1";
                        
                        // if (mysqli_query($conn, $sql)) {
                        //     echo "Record updated successfully";
                        // die();
                        
                        // } else {
                        //     echo "Error updating record: " . mysqli_error($conn);
                        // }
                        }
                        // die();
                        
                        // die();
                        // echo $counter2;
                        
                        ?>
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