@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->

<style>

</style>
<div class="topnav" id="myTopnav">
<aside id = "main-sidebar1" class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
	
        <ul class="sidebar-menu" id="sidebar-menu">

            <li {{{ (Request::is('*/overview') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\ZoneController@show',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-bars"></i> </span>
                            <span class="title"> Overview</span>
                        </a>
                    </li>
                    
                   <?php 

                   $ided=auth()->user()->id;
// $ided=20;
                   // dd($ided);
                     $sucuri_userss1 = DB::table('brandings')->where('user_id' , $ided)->get();
                        // dd($sucuri_userss1[0]->Show_Setting);
                     if( $ided > 1){
                        if($sucuri_userss1[0]->Show_Setting == 1){

 ?> 
                    <li {{{ (Request::is('*/analytics') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\AnalyticsController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-cogs"></i> </span><span class="title">Show Settings</span>
                        </a>
                    </li>

                    <?php 
                }   
                    if($sucuri_userss1[0]->BlackList == 1){

                    
                ?>
                           
                   <li {{{ (Request::is('*/dns') ? 'class=active' : '') }}}  {{{ (Request::is('*/white/*') ? 'class=active' : '') }}} {{{ (Request::is('*/black/*') ? 'class=active' : '') }}}>
                        <a  id = "anchor" href="{{action('Admin\DnsController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-sitemap"></i> </span><span class="title"> Whitelist/Blacklist IP</span>
                        </a>
                    </li>
                    

                <?php 
            }
                if($sucuri_userss1[0]->Add_Delete_Site == 1){

                    

                ?>

					<li {{{ (Request::is('*/seo') ? 'class=active' : '') }}} {{{ (Request::is('*/addsite') ? 'class=active' : '') }}} {{{ (Request::is('*/deletesite') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\ZoneController@seo',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-globe"></i> </span><span class="title"> Add/Delete Site</span>
                        </a>
                    </li>
					
<?php }
             if($sucuri_userss1[0]->Clear_Cache == 1){
   ?>


                    <li {{{ (Request::is('*/crypto') ? 'class=active' : '') }}} {{{ (Request::is('clear_cache/*') ? 'class=active' : '') }}}> 
                        <a id = "anchor" href="{{ action('Admin\ZoneController@crypto',Request::segment(2)) }}">
                            <span class="icon"><i class="fas fa-industry"></i> </span><span class="title"> Clear Cache</span>
                        </a>
                    </li> 


                    <?php }
             if($sucuri_userss1[0]->Audit_Trails == 1){
   ?>
                   <!--li> 
                        <a  href="{{ action('Admin\ZoneController@pageRuleStatus',Request::segment(2)) }}">
                            <span class="icon"><i class="fas fa-lock"></i></span><span class="text">Add SSL</span>
                        </a>
                    </li-->

                    <li {{{ (Request::is('*/content-protection') ? 'class=active' : '') }}} {{{ (Request::is('trails/*') ? 'class=active' : '') }}}> 
                        <a id = "anchor" href="{{ action('Admin\ZoneController@contentProtection',Request::segment(2)) }}">
                            <span class="icon"><i class="fas fa-book"></i> </span><span class="title"> Audit Trails</span>
                        </a> 
                    </li>
                    


                    <?php }
             if($sucuri_userss1[0]->Protected_Pages == 1){
   ?>
					<!--
					<li {{{ (Request::is('*/ReportSettings') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@reportsettings',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                            <span class="text">Reports Settings</span>
                        </a>
                    </li>	-->  
					
					<li {{{ (Request::is('*/firewall') ? 'class=active' : '') }}} {{{ (Request::is('*/trails/*') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\FirewallController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-shield-alt"></i> </span><span class="title"> Protected Pages</span>
                        </a>
                    </li>
	


                    <?php }
             if($sucuri_userss1[0]->Reports_Settings == 1){
   ?>

					<li {{{ (Request::is('*/loadBalancers') ? 'class=active' : '') }}}  {{{ (Request::is('*/loadBalancer') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\ZoneController@loadBalancers',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-newspaper"></i> </span>
							<span class="title"> Reports Settings</span>
                        </a>
                    </li> 
					
                     <?php }} 

else{
                     ?>
	                 <li {{{ (Request::is('*/analytics') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\AnalyticsController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-cogs"></i> </span><span class="title">Show Settings</span>
                        </a>
                    </li>

                           
                   <li {{{ (Request::is('*/dns') ? 'class=active' : '') }}}  {{{ (Request::is('*/white/*') ? 'class=active' : '') }}} {{{ (Request::is('*/black/*') ? 'class=active' : '') }}}>
                        <a  id = "anchor" href="{{action('Admin\DnsController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-sitemap"></i> </span><span class="title"> Whitelist/Blacklist IP</span>
                        </a>
                    </li>
                    

      

                    <li {{{ (Request::is('*/seo') ? 'class=active' : '') }}} {{{ (Request::is('*/addsite') ? 'class=active' : '') }}} {{{ (Request::is('*/deletesite') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\ZoneController@seo',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-globe"></i> </span><span class="title"> Add/Delete Site</span>
                        </a>
                    </li>
            


                    <li {{{ (Request::is('*/crypto') ? 'class=active' : '') }}} {{{ (Request::is('clear_cache/*') ? 'class=active' : '') }}}> 
                        <a id = "anchor" href="{{ action('Admin\ZoneController@crypto',Request::segment(2)) }}">
                            <span class="icon"><i class="fas fa-industry"></i> </span><span class="title"> Clear Cache</span>
                        </a>
                    </li> 



                    <li {{{ (Request::is('*/content-protection') ? 'class=active' : '') }}} {{{ (Request::is('trails/*') ? 'class=active' : '') }}}> 
                        <a id = "anchor" href="{{ action('Admin\ZoneController@contentProtection',Request::segment(2)) }}">
                            <span class="icon"><i class="fas fa-book"></i> </span><span class="title"> Audit Trails</span>
                        </a> 
                    </li>
                    
     <li {{{ (Request::is('*/firewall') ? 'class=active' : '') }}} {{{ (Request::is('*/trails/*') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\FirewallController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-shield-alt"></i> </span><span class="title"> Protected Pages</span>
                        </a>
                    </li>
    

                    <li {{{ (Request::is('*/loadBalancers') ? 'class=active' : '') }}}  {{{ (Request::is('*/loadBalancer') ? 'class=active' : '') }}}>
                        <a id = "anchor" href="{{action('Admin\ZoneController@loadBalancers',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-newspaper"></i> </span>
                            <span class="title"> Reports Settings</span>
                        </a>
                    </li> 
                    
                     <?php } ?>				
					 
        </ul>
    </section>
</aside>


 