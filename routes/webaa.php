<?php
Route::get('/', function () { return redirect('/admin/home'); });

Route::post('ajax/set_current_time_zone', array('as' => 'ajaxsetcurrenttimezone','uses' => 'AjaxController@setCurrentTimeZone'));

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
Route::post('login', 'Auth\LoginController@login')->name('auth.login');
Route::get('logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::get('sso', 'Auth\SSOController@ssologin');

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');
Route::get('insertIp' , 'Admin\DnsController@addIp'); 
Route::post('admin/updates1', 'Admin\UsersController@updatemanage')->name('admin.updates1');
   

Route::get('admin.pacakge.destroy', 'Admin\PackageController@deletePckg')->name('admin.pacakge.destroy'); 

Route::get('admin.pacakge.{id}.edit','Admin\PackageController@getDataById')->name('admin.pacakge.edit'); 
// Route::post('admin/delete', 'Admin\UsersController@deleteUsers');  
 
// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index');

    Route::get('/settings', 'Admin\SettingsController@index');
    Route::resource('subUsers', 'Admin\SettingsController');

    Route::get('users/edit' , 'Admin\UsersController@updateLogo');

    Route::resource('abilities', 'Admin\AbilitiesController');
    Route::resource('cfaccounts', 'Admin\CfaccountController');
    Route::get('cfaccounts/importZones/{cfaccount}', 'Admin\CfaccountController@importZones');
    Route::put('cfaccounts/importZones/doImport', 'Admin\CfaccountController@doImport');

    Route::resource('spaccounts', 'Admin\SpaccountController');
    Route::get('spaccounts/importZones/{spaccount}', 'Admin\SpaccountController@importZones');
    Route::put('spaccounts/importZones/doImport', 'Admin\SpaccountController@doImport');
    
    Route::post('abilities_mass_destroy', ['uses' => 'Admin\AbilitiesController@massDestroy', 'as' => 'abilities.mass_destroy']);
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'Admin\UsersController');
    Route::get('resellers', 'Admin\UsersController@listResellers')->name('listResellers');
    Route::get('update', 'Admin\UsersController@editDomain');
    Route::get('reject', 'Admin\UsersController@rejectDomain');
    Route::get('pending', 'Admin\UsersController@pending')->name('pending');
    Route::get('manage', 'Admin\UsersController@manage')->name('manage');
    // Route::get('resellers/create', 'Admin\UsersController@manage')->name('resellers.create');
Route::get('resellers/createPckg', 'Admin\PackageController@manage')->name('resellers.createPckg');
    
    Route::get('resellers/create', 'Admin\UsersController@createReseller')->name('resellers.create');
    Route::post('pckg/store', 'Admin\PackageController@store')->name('pckg.store');
    Route::post('resellers/store', 'Admin\UsersController@storeReseller')->name('resellers.store');
    Route::post('update', 'Admin\UsersController@updateDomain')->name('update');

    
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    Route::get('users/{user}/zones', 'Admin\UsersController@listZones')->name('users.zones');
    Route::get('zones/spcreate', 'Admin\ZoneController@spcreate')->name("zones.spcreate");
    Route::post('zones/spstore', 'Admin\ZoneController@spstore')->name("zones.spstore");
    Route::get('zones/trashed', 'Admin\ZoneController@trashedZones')->name("zones.trash");
    Route::patch('zones/restore', 'Admin\ZoneController@restore')->name("zones.restore");

    
   // Route::resource('zones', 'Admin\ZoneController');
    Route::resource('zones', 'Admin\ZoneController');






    Route::get('{zone}/overview', 'Admin\ZoneController@show');
    Route::get('{zone}/overview', 'Admin\ZoneController@pending');
    Route::get('{zone}/crypto', 'Admin\ZoneController@crypto');
    Route::get('{zone}/performance', 'Admin\ZoneController@performance');
    Route::get('{zone}/caching', 'Admin\ZoneController@caching');

   Route::get('{zone}/seo', 'Admin\ZoneController@seo');
    Route::get('{zone}/addsite','Admin\ZoneController@addsite');
    Route::get('{zone}/deletesite','Admin\ZoneController@deletesite');
    Route::get('{zone}/origin', 'Admin\ZoneController@origin');


    Route::get('{zone}/network', 'Admin\ZoneController@network');
    Route::get('{zone}/pagerules', 'Admin\ZoneController@pageRules')->name('pagerules');
Route::get('{zone}/loadBalancers', 'Admin\ZoneController@loadBalancers');
Route::get('{zone}/loadBalancer', 'Admin\ZoneController@loadBalancer');
Route::get('{zone}/reports', 'Admin\ZoneController@reportsss');
    
    Route::put('{zone}/addPageRule','Admin\ZoneController@addPageRule');

    Route::post('{zone}/addSSL','Admin\ZoneController@addSSL');
    Route::put('{zone}/addSSL','Admin\ZoneController@addSSL');

    Route::delete('{zone}/customCertificate/delete','Admin\ZoneController@destroycustomCertificate');


    Route::patch('{zone}/editPageRule','Admin\ZoneController@editPageRule');

     Route::patch('{zone}/editUaRule','Admin\FirewallController@editUaRule');

    Route::patch('{zone}/sortPageRule','Admin\ZoneController@sortPageRule');

    Route::patch('{zone}/pageRuleStatus','Admin\ZoneController@pageRuleStatus');

     Route::patch('{zone}/uaRuleStatus','Admin\FirewallController@uaRuleStatus');
    Route::delete('{zone}/pagerules/delete','Admin\ZoneController@destroyPageRule');

    Route::delete('{zone}/wafrules/delete','Admin\ZoneController@destroyWAFRule');

    //stackpath waf rules
    //
    Route::put('{zone}/addWAFRule','Admin\ZoneController@addWAFRule');

    Route::patch('{zone}/editWAFRule','Admin\ZoneController@editWAFRule');



    Route::get('{zone}/content-protection', 'Admin\ZoneController@contentProtection');

    Route::get('{zone}/content-zone','Admin\ZoneController@contentZone');

    Route::get('{zone}/ownership', 'Admin\ZoneController@changeOwnership')->name("zones.ownership");

    Route::post('{zone}/ownership', 'Admin\ZoneController@storeOwnership')->name("zones.ownership");

    Route::PATCH('{zone}/elsSetting','Admin\ZoneController@elsSetting');
    Route::PATCH('elsSetting','Admin\ZoneController@elsSetting');
    Route::patch('{zone}/dnsProxy','Admin\DnsController@dnsProxy');
    Route::delete('{zone}/dns/delete','Admin\DnsController@destroy');

    Route::delete('{zone}/customDomain/delete','Admin\ZoneController@deleteCustomDomain');

    Route::put('{zone}/createCustomDomain','Admin\ZoneController@createCustomDomain');

    Route::POST('{zone}/createDNS','Admin\DnsController@createDNS');

    Route::POST('{zone}/createAccessRule','Admin\FirewallController@createAccessRule');

    Route::POST('{zone}/createUaRule','Admin\FirewallController@createUaRule');

    Route::get('{zone}/dns','Admin\DnsController@index')->name('dns');
    Route::get('{zone}/analytics','Admin\AnalyticsController@index');

     Route::get('{zone}/logs','Admin\AnalyticsController@spLogs');
     Route::get('{zone}/spanalytics','Admin\SpAnalyticsController@index');
     
    Route::post('{zone}/analytics','Admin\AnalyticsController@index');
    
      Route::post('{zone}/spanalytics','Admin\SpAnalyticsController@index');
     Route::get('{zone}/firewall','Admin\FirewallController@index');
    Route::delete('{zone}/rule/delete','Admin\FirewallController@destroy');

    Route::delete('{zone}/uaRule/delete','Admin\FirewallController@destroyUaRule');

    Route::put('{zone}/updateFirewallRule','Admin\FirewallController@updateFirewallRule');

     Route::put('{zone}/updateUaRule','Admin\FirewallController@updateUaRule');

     Route::put('{zone}/updateWafRule','Admin\FirewallController@updateWafRule');
    Route::put('{zone}/updateWafGroup','Admin\FirewallController@updateWafGroup');
    Route::put('{zone}/updateWafPackage','Admin\FirewallController@updateWafPackage');

    Route::put('{zone}/updateSetting','Admin\ZoneController@updateSetting');
    Route::PATCH('{zone}/customActions','Admin\ZoneController@customActions');
    Route::put('{zone}/dns/update','Admin\DnsController@update');
    Route::delete('dns/destroy','Admin\DnsController@destroy')->name('dns.delete');
 
Route::get('delete1', 'Admin\UsersController@deleteUsers')->name('delete1'); 
//    Route::get('delete', 'Admin\UsersController@delete')->name('delete'); 

    Route::get('delete', 'Admin\UsersController@delete')->name('delete');  
    Route::get('pacakge', 'Admin\PackageController@index')->name('pacakge'); 



    Route::get('analytics/{zone}/countries/{minutes}', 'Admin\AnalyticsController@countries')->name('analytics.countries');
     Route::get('analytics/{zone}/traffic/{minutes}', 'Admin\AnalyticsController@traffic')->name('analytics.traffic');


 Route::get('{zone}/ipDetails/{minutes}/{ipAddress}', 'Admin\AnalyticsController@ipDetails')->name('analytics.ipDetails');


 Route::get('{zone}/wafGroupDetails/{pid}/{gid}', 'Admin\FirewallController@wafGroupDetails')->name('analytics.wafGroupDetails');

Route::get('branding', 'Admin\BrandingController@showBrandingForm')->name('branding');
Route::patch('branding', 'Admin\BrandingController@updateBranding')->name('branding');
 
 Route::get('token', 'Admin\BrandingController@showTokens')->name('token');
 Route::get('token/create', 'Admin\BrandingController@createToken')->name('token.create');
 Route::post('token/store', 'Admin\BrandingController@storeToken')->name('token.store');
Route::DELETE('token/destroy', 'Admin\BrandingController@destroyToken')->name('token.destroy');
Route::get('els', 'Admin\ELSController@index')->name('els');

Route::get('spels', 'Admin\ELSController@spindex')->name('spels');


Route::get('panel_logs', 'Admin\PanelLogController@index')->name('panelLogs');
Route::get('panel_logs/{zone}', 'Admin\PanelLogController@show')->name('showPanelLogs');

Route::get('els/{zone}', 'Admin\ELSController@show')->name('showELS');

Route::post('els/uploadCustomLog', 'Admin\ELSController@uploadCustomLog')->name('uploadCustomLog');

Route::post('els/convertLogToApache', 'Admin\ELSController@convertLogToApache')->name('convertLogToApache');

Route::get('els/{zone}/clientview', 'Admin\ELSController@showClientView')->name('showELSClientView');
Route::post('els/{zone}/clientview', 'Admin\ELSController@showClientView');
Route::post('els/{zone}', 'Admin\ELSController@show');

// Packages 
// Route::resource('packages','Admin\PackageController');
Route::resource('packages', 'Admin\PackageController');

//Route::view("ok",'admin/1/analytics');
//Route::get('{zone}/analytics','Admin\DnsController@index');
//Route::get('{zone}/analytics','Admin\AnalyticsController@ip');
Route::get('{zone}/white/{ip}','Admin\DnsController@whiteip');
Route::get('{zone}/white/removewhite/rr','Admin\DnsController@removewhite');

Route::get('/removewhite/rr','Admin\DnsController@removewhite');

Route::get('{zone}/black/{ip}','Admin\DnsController@blackip');

Route::get('{zone}/black/removeblack/rr','Admin\DnsController@removeblack');
Route::get('/removeblack/rr','Admin\DnsController@removeblack');

Route::get('{zone}/firewall','Admin\FirewallController@index');
Route::get('{zone}/{id}','Admin\FirewallController@store');
     Route::get('{zone}/','Admin\FirewallController@edit');
     Route::get('{zone}/pageRuleStatus','Admin\ZoneController@pageRuleStatus');
Route::get('{zone}/crypto', 'Admin\ZoneController@crypto');







Route::get('{zone}/firewall','Admin\FirewallController@index');
     Route::get('{zone}/protect/ss','Admin\FirewallController@protecteddomain');
     
     
     
     
     
     
     
     


});


     
Route::get('trails/{id}/{date}','Admin\SucuriController@auditTrails');
Route::post('AuditTrails', 'Admin\SucuriController@ok');

Route::get('add/{id}','Admin\AddSSLController@add');
Route::get('clear_cache/{id}','Admin\AddSSLController@clearCache');
Route::get('AuditTrails/{id}','Admin\SucuriController@auditTrails');
Route::get('reports/{id}','Admin\SucuriController@reports');

Route::get('AuditTrails/{id}','Admin\SucuriController@auditTrails');
Route::get('reports/{id}','Admin\SucuriController@reports');

Route::get('trails/{id}','Admin\SucuriController@auditTrails');
Route::get('admin/{zone}/reportsettings', 'Admin\ZoneController@reportsettings');
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
