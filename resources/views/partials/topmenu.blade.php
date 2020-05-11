<div class="menu" id = "topmenu">
    <ul >
                    <li {{{ (Request::is('*/overview') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@show',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-bars"></i></span>
                            <span class="text">Overview</span>
                        </a>
                    </li>
                    
                    
                    <li {{{ (Request::is('*/analytics') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\AnalyticsController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-cogs"></i></span><span class="text">Settings</span>
                        </a>
                    </li>
                           
                   <li {{{ (Request::is('*/dns') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\DnsController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-sitemap"></i></span><span class="text">Whitelist/Blacklist</span>
                        </a>
                    </li>
                    
                    <li {{{ (Request::is('*/crypto') ? 'class=active' : '') }}}> 

                        <a  href="{{ action('Admin\ZoneController@crypto',Request::segment(2)) }}">
                            <span class="icon"><i class="fas fa-lock"></i></span><span class="text">Clear Cache</span>
                        </a>
                    </li> 
                   <!--li> 
                        <a  href="{{ action('Admin\ZoneController@pageRuleStatus',Request::segment(2)) }}">
                            <span class="icon"><i class="fas fa-lock"></i></span><span class="text">Add SSL</span>
                        </a>
                    </li-->

                    <li> 
                        <a  href="{{ action('Admin\ZoneController@contentProtection',Request::segment(2)) }}">
                            <span class="icon"><i class="fas fa-lock"></i></span><span class="text"> Audit Trails</span>
                        </a> 
                    </li>
                    
					<!--
					<li {{{ (Request::is('*/ReportSettings') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@reportsettings',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                            <span class="text">Reports Settings</span>
                        </a>
                    </li>	-->  
					
					<li {{{ (Request::is('*/loadbalancers') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@loadBalancers',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-filter"></i></span><span class="text">Reports Settings</span>
                        </a>
                    </li> 
					
                    <li {{{ (Request::is('*/protected') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\FirewallController@index',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-shield-alt"></i></span><span class="text">Protected Pages</span>
                        </a>
                    </li> 
					
					<li {{{ (Request::is('*/seo') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@seo',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-globe"></i></span><span class="text">Add/Delete Sites</span>


                        </a>
                    </li> 
                    
                     <!--li {{{ (Request::is('*/crypto') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@crypto',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-lock"></i></span><span class="text">Crypto</span>
                        </a>
                    </li> 
                    
                    <li>
                        <a data-toggle="tab" href="#settings">
                            <span class="fa fa-user-secret"></span>
                            <div class="text-center">Access</div>
                        </a>
                    </li--> 
                     
                    <!--li {{{ (Request::is('*/performance') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@performance',Request::segment(2))}}">
                           <span class="icon"><i class="fas fa-bolt"></i></span><span class="text">Speed</span>
                        </a>
                    </li> 
                     
                    <li {{{ (Request::is('*/caching') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@caching',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-server"></i></span><span class="text">Caching</span>
                        </a>
                    </li> 

                    
                    <li {{{ (Request::is('*/pagerules') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@pageRules',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-filter"></i></span><span class="text">Page Rules</span>
                        </a>
                    </li> 
                    
                    
                    <li {{{ (Request::is('*/loadbalancers') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@loadBalancers',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-filter"></i></span><span class="text">LoadBalancers</span>
                        </a>
                    </li> 
                    
                    <li {{{ (Request::is('*/origin') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@origin',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                            <span class="text">Origin</span>
                        </a>
                    </li> 
                    
                    <li {{{ (Request::is('*/logs') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\AnalyticsController@spLogs',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-server"></i></span><span class="text">Logs Analysis</span>
                        </a>
                    </li--> 

                 <!--    <li class="">
                        <a data-toggle="tab" href="#information">
                            <span class="glyphicon glyphicon-filter"></span>
                            <div class="text-center">Page Rules</div>
                        </a>
                    </li> -->
                        
                    <!--li {{{ (Request::is('*/network') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@network',Request::segment(2))}}">
                           <span class="icon"><i class="fas fa-map-marker-alt"></i></span><span class="text">Network</span>
                        </a>
                    </li--> 
                    
                    
                   <!--  <li>
                        <a data-toggle="tab" href="#email">
                            <span class="fa fa-list"></span>
                            <div class="text-center">Traffic</div>
                        </a>
                    </li> -->
                    
                     <!--li {{{ (Request::is('*/content-protection') ? 'class=active' : '') }}}>
                        <a  href="{{action('Admin\ZoneController@contentProtection',Request::segment(2))}}">
                            <span class="icon"><i class="fas fa-file-alt"></i></span><span class="text">Scrape</span>
                        </a>
                    </li-->
                   
                </ul>
</div>