<?php
use Illuminate\Http\Request;

// Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {


// ->middleware('auth:api');
// });
Route::group(['middleware' => 'auth:api'], function(){
	// Route::post('get-details', 'API\PassportController@getDetails');

	// Route::get('{zone}/overview', 'API\ZoneController@show');\


	//Reseller / Super Admin Routes
	Route::post('getZone', 'API\ZoneController@show');
	Route::post('getSSOToken', 'API\ZoneController@sso_token');
	Route::post('createZone', 'API\ZoneController@createZone');
	Route::post('suspendZone', 'API\ZoneController@suspendZone');
	Route::post('unsuspendZone', 'API\ZoneController@unsuspendZone');


	// Route::post('cf','API\ZoneController@cfApi');

	Route::post('zones/{zone}/firewall/access_rules/rules','API\ZoneController@cfApi');
Route::post('zones/{zone}/purge_cache','API\ZoneController@cfApi');





});
//


Route::post('login', 'API\ZoneController@login');



// Route::get('zonelist','API\ZonsController@index');
Route::resource('packages', 'API\PackageController');

//  // Get overview the selected zone
//  Route::get('{zone}/overview', 'API\ZonsController@show');

//  // Get all selected Zons DNS LISTING
//  Route::get('{zone}/dns','API\DnsController@index')->name('dns');
//  // Create New dns Api
//  Route::POST('{zone}/createDNS','API\DnsController@createDNS');
//  // Delete dns api
//  // Route::delete('{zone}/delete-dns/{id}','API\DnsController@deleteDns');
//  Route::delete('dns/destroy/{id}','API\DnsController@destroy')->name('dns.delete');

//  // Get all selected zones crypto listing
//  Route::get('{zone}/crypto', 'API\ZonsController@crypto');

//  // Get Performance slected zone
//  Route::get('{zone}/performance', 'API\ZonsController@performance');

//  //Get caching Selected zone
//  Route::get('{zone}/caching', 'API\ZonsController@caching');

//  // get Page rule selected zone
//  Route::get('{zone}/pagerules', 'API\ZonsController@pageRules')->name('pagerules');
//  // Add page rule api
//   Route::put('{zone}/addPageRule','API\ZonsController@addPageRule');

//  //Get Network Selected zone
//   Route::get('{zone}/network', 'API\ZonsController@network');

//   // Get scrap selected zone
//   Route::get('{zone}/content-protection', 'API\ZonsController@contentProtection');
