<?php 
namespace App\Libraries;
// Code Sample for the CloudFlare Host Provider API
// Copyright (c) 2010 by CloudFlare, Inc.
// All Rights Reserved.
//
// You may use these Code Samples for any purpose, commercial or otherwise, provided that you
// adhere to the disclaimer at the bottom of this document. You must also require any transferee of this
// Code Sample to abide by the terms of the disclaimer.
//
// Requires: PHP 5.0.3+ compiled with OpenSSL and CLI support
// Run from the command line like so:  php test_hostapi.php <ACTION> <params>
//
// A) Sample USER_CREATE operations:
// 
//   // 1) Register a user with CloudFlare, creating the CloudFlare account.
//   php test_hostapi.php USER_CREATE someuser@example.com some_password
// 
//   // 2) Same as #1, but also specifying CloudFlare username of 'someuser'.
//   php test_hostapi.php USER_CREATE someuser@example.com some_password someuser
// 
//   // 3) Same as #2, but also specifying a unique_id.
//   php test_hostapi.php USER_CREATE someuser@example.com some_password someuser 1023
// 
//   // 4) Same as #1, but also specifying a unique_id.
//   php test_hostapi.php USER_CREATE someuser@example.com some_password - 1023
//
// B) Sample ZONE_SET operations:
//   // 1) Register a zone to route CNAMEs through CloudFlare. Fields are: user_key, zone_name, resolve_to, cnames-to-host
//   php test_hostapi.php ZONE_SET abcb9319cb395bdb6a8444ac5feabcd1 example.com  resolve-to-cloudflare.example.com  www,blog,@
//

// Debug settings:
define('DEBUG_LEVEL',      0); // 0 for no debug, 1 for debug, 2 for more debug, etc.
define('PRINT_RESPONSE',   0); // 1 to print the received response

// Service URL:
define('HOST_GW_SERVICE_URL',  'https://api.cloudflare.com/host-gw.html');

// Clobber any previously assigned unique_ids. A 'unique_id' is a custom value,
// an alias to map a user in your database to one in the CloudFlare system.
// Any operations that can set a unique_id can be set to automatically "clobber"
// or unassign a previously set unique_id.
define('ENABLE_CLOBBER_UNIQUE_ID', TRUE);

// For API access, specify your host_key:
// define('HOST_KEY',         '1199be00ce2078a7a52e8fb57ddaabae');
define('HOST_KEY',         '3483a41c9f169200e9f38698586a4d1b');
// define('HOST_KEY',         'e3ee4df34b3be5e414b176fce72544c9f7df7');


class Cfhost
{





public static function request($act,$arg2=NULL,$arg3=NULL,$arg4=NULL,$arg5=NULL)
{

$params   = array();
        
if (!$act) {
    echo "ERROR: No operation specified. (Operations: USER_CREATE|USER_AUTH|USER_LOOKUP)\n";
    echo "Usage: <task> [params]\n";
    exit(1);
}

$params["host_key"] = HOST_KEY;
$params["act"]      = strtolower($act);

switch (strtoupper($act)) {
 
// Create a CloudFlare account for a user. Register you as the host provider.
case "USER_CREATE":
    // required:
    $params["cloudflare_email"]     = $arg2;
    $params["cloudflare_pass"]      = $arg3;
    // optional:
    $params["cloudflare_username"]  = $arg4 && $arg4 !== '-' ? $arg4 : NULL;
    $params["unique_id"]            = $arg5 && $arg5 !== '-' ? $arg5 : NULL;
    break;

// Look a user by 'cloudflare_email' or by a previosly assigned 'unique_id'.
case "USER_LOOKUP":
    $lookup_type                    = $arg2;
    $lookup_value                   = $arg3;

    if ($lookup_type == 'cloudflare_email' || $lookup_type == 'unique_id') {
        $params[$lookup_type]           = $lookup_value;
    } else {
        echo "ERROR: Invalid USER_LOOKUP type: '$lookup_type'; Options are: cloudflare_email or unique_id\n";
        exit(3);
    }
    break;

// Authorize you as the host provider for an existing user.
case "USER_AUTH":
    $params["cloudflare_email"]     = $arg2;
    $params["cloudflare_pass"]      = $arg3;
    // optional:
    $params["unique_id"]            = $arg4 && $arg4 !== '-' ? $arg4 : NULL;
    break;


// Setup a Zone.
case "ZONE_SET":
    $params["user_key"]             = $arg2;
    $params["zone_name"]            = $arg3;
    $params["resolve_to"]           = $arg4;
    $params["subdomains"]           = $arg5;
    break;

// Lookup a Zone by 'user_key' and 'zone_name'.
case "ZONE_LOOKUP":
    $params["user_key"]             = $arg2;
    $params["zone_name"]            = $arg3;
    break;

// Delete a Zone by 'user_key' and 'zone_name'.
case "ZONE_DELETE":
    $params["user_key"]             = $arg2;
    $params["zone_name"]            = $arg3;
    break;

default:
    echo "That operation is not supported.\n";
    exit(4);
}

if (defined("ENABLE_CLOBBER_UNIQUE_ID") && ENABLE_CLOBBER_UNIQUE_ID == 1) {
    $params["clobber_unique_id"] = 1;
}



$response  = Cfhost::performRequest($params);
if ($response === FALSE) {
    die("Failed to get response from service.");
}

return Cfhost::processResponse($response);


}
// Contact the service. Returns FALSE on error.
public static function performRequest(& $data, $headers=NULL) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, HOST_GW_SERVICE_URL);

    if (DEBUG_LEVEL > 1) {
        curl_setopt($ch, CURLOPT_VERBOSE, 1); 
    }
    if (DEBUG_LEVEL > 0) {
        echo "REQUEST DATA (to be sent via POST):\n";
        print_r($data);
    }

    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($ch, CURLOPT_TIMEOUT, 20); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_AUTOREFERER,    TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    if (($http_result = curl_exec($ch)) === FALSE) {
        echo "WARNING: A connectivity error occured while contacting the service.\n";
        trigger_error(curl_error($ch));
        return FALSE;
    }

    curl_close($ch);
    return $http_result;
}

public static function processResponse(& $response) {
    $settings  = NULL;
    
    if (PRINT_RESPONSE) {
        echo "RAW RESPONSE DATA:\n".$response."\n";
    }

    // echo "DECODED RESPONSE DATA:\n";
    $decoded_response = json_decode($response);
    return $decoded_response;
    // print_r($decoded_response);
}




//
// Use of this Code Sample is at Your Own Risk
//
// You understand that your use of the this Code Sample is at your own risk, i.e. CloudFlare, Inc.
// (CloudFlare) will not be liable either directly or indirectly to you or any third party transferee recipient of the Code Sample.
// CloudFlare is under no obligation to provide you with any error corrections, updates, upgrades, bug fixes and/or enhancements of the Code Sample.
//
// This Code Sample May Not Include Adequate System Protection Functionality
//
// The code sample included herein has not been reviewed for suitability in terms of error-handling, system loading, memory requirements,
// CPU usage or other factors that may influence the operation of systems on which it is installed.
//
// Disclaimer of Warranty; Limitation of Liability
//
// YOUR USE OF THIS CODE SAMPLE IS AT YOUR SOLE RISK. ANY CODE SAMPLE IS PROVIDED "AS IS," "WITH ALL FAULTS" AND "AS AVAILABLE" FOR YOUR USE,
// WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, UNLESS SUCH WARRANTIES ARE LEGALLY INCAPABLE OF EXCLUSION. SPECIFICALLY,
// CloudFlare AND ITS VENDORS DISCLAIM IMPLIED WARRANTIES THAT THE CODE SAMPLE IS MERCHANTABLE, OF SATISFACTORY QUALITY, ACCURATE, FIT FOR A PARTICULAR
// PURPOSE OR NEED, OR NON-INFRINGING. CloudFlare AND ITS VENDORS DO NOT WARRANT THAT THE FUNCTIONS CONTAINED IN THE CODE SAMPLE WILL MEET YOUR REQUIREMENTS
// OR THAT THE CODE SAMPLE IS ERROR-FREE, OR THAT DEFECTS IN THE CODE SAMPLE WILL BE CORRECTED. CloudFlare AND ITS VENDORS DO NOT WARRANT OR MAKE ANY
// REPRESENTATIONS REGARDING THE USE OR THE RESULTS OF THE USE OF THE CODE SAMPLE OR RELATED DOCUMENTATION IN TERMS OF THEIR CORRECTNESS, ACCURACY,
// RELIABILITY OR OTHERWISE. 
//


}
?>
