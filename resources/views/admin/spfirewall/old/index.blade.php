@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')


@include('partials.topmenu')

   <div class="container">
 <div class="row pageHeading">
    <div class="col-lg-12">
        <h1>Firewall</h1>
    </div>
</div>


@if($zone->cfaccount_id!=0)
    <div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
  {{ title_case(str_replace("_"," ",$zoneSetting->where('name','security_level')->first()->name)) }}
    
    
</h3>




  <p>Adjust your website's Security Level to determine which visitors will receive a challenge page.</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $security_level=$zoneSetting->where('name','security_level')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>
          <select settingid="{{$zoneSetting->where('name','security_level')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="security_level" name="security_level">
                        <option {{ $security_level === "off" ? "selected":"" }} disabled="" value="off">Off</option>
                        <option {{ $security_level === "essentially_off" ? "selected":"" }} value="essentially_off">Essentially Off</option>
                        <option {{ $security_level === "low" ? "selected":"" }} value="low">Low</option>
                        <option {{ $security_level === "medium" ? "selected":"" }} value="medium">Medium</option>
                        <option {{ $security_level === "high" ? "selected":"" }} value="high">High</option>
                        <option {{ $security_level === "under_attack" ? "selected":"" }} value="under_attack">I'm Under Attack</option>
                        
                    </select>
          </div>
      </div>

    </div>


     <div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
   Challenge Passage 
    
    
</h3>




  <p>Specify how long a visitor with a bad IP reputation is allowed access to your website after completing a challenge. After the Challenge Passage TTL expires the visitor in question will have to pass a new Challenge.</p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

          <?php $challenge_ttl=$zoneSetting->where('name','challenge_ttl')->first()->value; ?>
          </div>
          <div class="col-lg-4 right ">
           <div  class="setting-title" >

           </div>

           <select  settingid="{{$zoneSetting->where('name','challenge_ttl')->first()->id }}"  style="width: 200px;" class="select2 changeableSetting" id="challenge_ttl" name="challenge_ttl">
                <option {{ $challenge_ttl === "300" ? "selected":"" }} value="300">5 minutes</option>
                <option {{ $challenge_ttl === "900" ? "selected":"" }} value="900">15 minutes</option>
                <option {{ $challenge_ttl === "1800" ? "selected":"" }} value="1800">30 minutes</option>
                <option {{ $challenge_ttl === "2700" ? "selected":"" }} value="2700">45 minutes</option>
                <option {{ $challenge_ttl === "3600" ? "selected":"" }} value="3600">1 hour</option>
                <option {{ $challenge_ttl === "7200" ? "selected":"" }} value="7200">2 hours</option>
                <option {{ $challenge_ttl === "10800" ? "selected":"" }} value="10800">3 hours</option>
                <option {{ $challenge_ttl === "14400" ? "selected":"" }} value="14400">4 hours</option>
                <option {{ $challenge_ttl === "28800" ? "selected":"" }} value="28800">8 hours</option>
                <option {{ $challenge_ttl === "57600" ? "selected":"" }} value="57600">16 hours</option>
                <option {{ $challenge_ttl === "86400" ? "selected":"" }} value="86400">1 day</option>
                <option {{ $challenge_ttl === "604800" ? "selected":"" }} value="604800">1 week</option>
                <option {{ $challenge_ttl === "2592000" ? "selected":"" }} value="2592000">1 month</option>
                <option {{ $challenge_ttl === "31536000" ? "selected":"" }} value="31536000">1 year</option>
            </select>

          
          </div>
      </div>

    </div>


@endif

<div class="firewall">
        <ul style="display: none;" class="nav nav-tabs" role="tablist">
          
          <li role="presentation" class="active"><a href="#waf" aria-controls="waf" role="tab" data-toggle="tab"> <span>WAF Policies</span><span class="loader"><span class="loader__dot">.</span><span class="loader__dot">.</span><span class="loader__dot">.</span></span></a></li>
          <li role="presentation" ><a href="#ipfirewall" aria-controls="ipfirewall" role="tab" data-toggle="tab">  <span>Custom WAF Rules</span><span class="loader"><span class="loader__dot">.</span><span class="loader__dot">.</span><span class="loader__dot">.</span></span></a></li>
          
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane" id="ipfirewall">

          
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><h2 style="display: inline">Custom Rules for {{ $records->first()->zone->name }}</h2>

        <div class="pull-right">
      <a class="btn btn-primary" id="add_dns" data-toggle="modal" data-target="#rule-edit-modal" > Add New Rule</a>

    </div>
  </div>


      <input type="hidden" name="csrftoken" value="{{csrf_token()}}" >

        <div class="panel-body table-responsive">
        <table style="display: none;" class="table table-bordered table-striped table-condensed">
            <form class="form-inline" id="create_dns" name="create_dns" method="post">
            <tr>
           
                 
                  <td>
                  <div class="form-group">

                    <input type="text" class="form-control" id="content" name="content" placeholder="IPv4 address">
                  </div>
                  </td>
                  <td>
                  <div class="form-group" >
                   <select style="width:200px;" class="select2 changeableSetting" id="challenge_ttl" name="challenge_ttl">
                <option  value="whitelist">Whitelist</option>
                <option  value="block">Block</option>
                <option  value="challenge">Challenge</option>
                <option  value="js_challenge">JS Challenge</option>
               
            </select>
                  </div>
                  </td>
                
                  <td>
                  
                  <button type="submit" class="btn btn-primary">Submit</button>
                  </td></tr>
                </form>
                </table>

            <table class="table table-bordered table-striped table-condensed">

                <thead>
                    <tr>
                        
                        <th >Name</th>
                        <th>Details</th>
                        <th>Action</th>

                        <th>&nbsp;</th>

                    </tr>
                </thead>

                <tbody>
                    @if (count($rules) > 0)
                        @foreach ($rules as $rule)
                            <tr id="rule_{{ $rule->id }}" data-entry-id="{{ $rule->id }}">
                                <td>{{ $rule->name }}</td>

                                <td>
                                    
                                    @if($rule->SpCondition->count()>1)
                                    Complex
                                    @else
                                      {{ $rule->SpCondition->first()->data }}
                                    @endif

                                </td>
                                
                                
                                <td>

                              {{ $rule->action }}
             
                                    </td>
                              
                                <td>
                                    <a class="deleteRule" rule-id="{{$rule->id}}" class="btn btn-default">
                                    <i class="glyphicon glyphicon-remove"></i>
                                    </a>


                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

          </div>

          <div role="tabpanel" class="tab-pane active" id="waf">

      @if($zone->plan == "free" AND $zone->cfaccount_id!=0)

      <div class="alert alert-info">Domain is not configured to use WAF Feature, Please contact BlockDOS for WAF activation.</div>
      @else
     
@foreach($wafPackages as $wafPackage)

 <div class="panel panel-default panel-main">
      <div class="panel-body  ">
      <div class="row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
  {{ title_case(str_replace("_"," ",$wafPackage->description)) }}
    
    
</h3>




  <p></p>


  <p class="text-info">This setting was last changed 2 days ago</p>


</div>

         
          </div>

     
     
</div>

      <div class="expandable wafGroups">

        

           <table class="table table-bordered table-striped table-condensed">
           <thead>
                <tr>
                <th width="90%">WAF Rule</th>
                
                <th></th>
                </tr>
</thead>
                @foreach($wafPackage->wafGroup as $wafGroup)

                  <tr>
                <td>{{ $wafGroup->name }}</td>
              
                <td  align="center"> 

                <input group-id="{{ $wafGroup->id }}" class="wafGroupToggle" type="checkbox" data-onstyle="primary" data-offstyle="default" {{ $wafGroup->mode === "on" ? "checked" : "" }} data-toggle="toggle" data-on=" ON" data-off="OFF">
                


                </td>
                </tr>

                @endforeach
            </table>
            

     

      </div>
 </div>
    </div>
@endforeach
 @endif    
          </div>
</div>
</div>





 


<div class="before-panel">


 </div>













<div class="modal" id="rule-edit-modal" data-reveal>

   <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Custom WAF Rule</h4>
      </div>
      <div class="modal-body">

  
   
    <form id="rule-edit-form" data-abide="ajax" novalidate data-rule-options='{"rule_conditional":{"if":"If","if_not":"If Not"},"rule_match_type":{"and":"Match All Conditions","or":"Match Any Conditions"},"rule_scope":{"Ip":"IP","IpRange":"IP Range","Url":"URL","UserAgent":"User Agent","Header":"Header","HttpMethod":"HTTP Method","FileExt":"File Type\/Extension","MimeType":"Content Type","Country":"Country","Organization":"Organization"},"rule_exact_match_scopes":["Url","Header","UserAgent"],"rule_scope_options":{"HttpMethod":{"post":"POST","get":"GET","head":"HEAD","put":"PUT","delete":"DELETE","patch":"PATCH","options":"OPTIONS"},"Country":{"AF":"Afghanistan","AL":"Albania","DZ":"Algeria","AS":"American Samoa","AD":"Andorra","AO":"Angola","AI":"Anguilla","AQ":"Antarctica","AG":"Antigua and Barbuda","AR":"Argentina","AM":"Armenia","AW":"Aruba","AU":"Australia","AT":"Austria","AZ":"Azerbaijan","BS":"Bahamas","BH":"Bahrain","BD":"Bangladesh","BB":"Barbados","BY":"Belarus","BE":"Belgium","BZ":"Belize","BJ":"Benin","BM":"Bermuda","BT":"Bhutan","BO":"Bolivia","BA":"Bosnia and Herzegovina","BW":"Botswana","BV":"Bouvet Island","BR":"Brazil","IO":"British Indian Ocean Territory","BN":"Brunei Darussalam","BG":"Bulgaria","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodia","CM":"Cameroon","CA":"Canada","CV":"Cape Verde","KY":"Cayman Islands","CF":"Central African Republic","TD":"Chad","CL":"Chile","CN":"China","CX":"Christmas Island","CC":"Cocos (Keeling) Islands","CO":"Colombia","KM":"Comoros","CG":"Congo","CD":"Congo, the Democratic Republic of the","CK":"Cook Islands","CR":"Costa Rica","CI":"Cote D&apos;Ivoire","HR":"Croatia","CU":"Cuba","CY":"Cyprus","CZ":"Czech Republic","DK":"Denmark","DJ":"Djibouti","DM":"Dominica","DO":"Dominican Republic","EC":"Ecuador","EG":"Egypt","SV":"El Salvador","GQ":"Equatorial Guinea","ER":"Eritrea","EE":"Estonia","ET":"Ethiopia","FK":"Falkland Islands (Malvinas)","FO":"Faroe Islands","FJ":"Fiji","FI":"Finland","FR":"France","GF":"French Guiana","PF":"French Polynesia","TF":"French Southern Territories","GA":"Gabon","GM":"Gambia","GE":"Georgia","DE":"Germany","GH":"Ghana","GI":"Gibraltar","GR":"Greece","GL":"Greenland","GD":"Grenada","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GN":"Guinea","GW":"Guinea-Bissau","GY":"Guyana","HT":"Haiti","HM":"Heard Island and Mcdonald Islands","VA":"Holy See (Vatican City State)","HN":"Honduras","HK":"Hong Kong","HU":"Hungary","IS":"Iceland","IN":"India","ID":"Indonesia","IR":"Iran, Islamic Republic of","IQ":"Iraq","IE":"Ireland","IL":"Israel","IT":"Italy","JM":"Jamaica","JP":"Japan","JO":"Jordan","KZ":"Kazakhstan","KE":"Kenya","KI":"Kiribati","KP":"Korea, Democratic People&apos;s Republic of","KR":"Korea, Republic of","KW":"Kuwait","KG":"Kyrgyzstan","LA":"Lao People&apos;s Democratic Republic","LV":"Latvia","LB":"Lebanon","LS":"Lesotho","LR":"Liberia","LY":"Libyan Arab Jamahiriya","LI":"Liechtenstein","LT":"Lithuania","LU":"Luxembourg","MO":"Macao","MK":"Macedonia, the Former Yugoslav Republic of","MG":"Madagascar","MW":"Malawi","MY":"Malaysia","MV":"Maldives","ML":"Mali","MT":"Malta","MH":"Marshall Islands","MQ":"Martinique","MR":"Mauritania","MU":"Mauritius","YT":"Mayotte","MX":"Mexico","FM":"Micronesia, Federated States of","MD":"Moldova, Republic of","MC":"Monaco","MN":"Mongolia","MS":"Montserrat","MA":"Morocco","MZ":"Mozambique","MM":"Myanmar","NA":"Namibia","NR":"Nauru","NP":"Nepal","NL":"Netherlands","AN":"Netherlands Antilles","NC":"New Caledonia","NZ":"New Zealand","NI":"Nicaragua","NE":"Niger","NG":"Nigeria","NU":"Niue","NF":"Norfolk Island","MP":"Northern Mariana Islands","NO":"Norway","OM":"Oman","PK":"Pakistan","PW":"Palau","PS":"Palestinian National Authority","PA":"Panama","PG":"Papua New Guinea","PY":"Paraguay","PE":"Peru","PH":"Philippines","PN":"Pitcairn","PL":"Poland","PT":"Portugal","PR":"Puerto Rico","QA":"Qatar","RE":"Reunion","RO":"Romania","RU":"Russian Federation","RW":"Rwanda","SH":"Saint Helena","KN":"Saint Kitts and Nevis","LC":"Saint Lucia","PM":"Saint Pierre and Miquelon","VC":"Saint Vincent and the Grenadines","WS":"Samoa","SM":"San Marino","ST":"Sao Tome and Principe","SA":"Saudi Arabia","SN":"Senegal","CS":"Serbia and Montenegro","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapore","SK":"Slovakia","SI":"Slovenia","SB":"Solomon Islands","SO":"Somalia","ZA":"South Africa","GS":"South Georgia and the South Sandwich Islands","ES":"Spain","LK":"Sri Lanka","SD":"Sudan","SR":"Suriname","SJ":"Svalbard and Jan Mayen","SZ":"Swaziland","SE":"Sweden","CH":"Switzerland","SY":"Syrian Arab Republic","TW":"Taiwan, Province of China","TJ":"Tajikistan","TZ":"Tanzania, United Republic of","TH":"Thailand","TL":"Timor-Leste","TG":"Togo","TK":"Tokelau","TO":"Tonga","TT":"Trinidad and Tobago","TN":"Tunisia","TR":"Turkey","TM":"Turkmenistan","TC":"Turks and Caicos Islands","TV":"Tuvalu","UG":"Uganda","UA":"Ukraine","AE":"United Arab Emirates","GB":"United Kingdom","US":"United States","UM":"United States Minor Outlying Islands","UY":"Uruguay","UZ":"Uzbekistan","VU":"Vanuatu","VE":"Venezuela","VN":"Viet Nam","VG":"Virgin Islands, British","VI":"Virgin Islands, U.S.","WF":"Wallis and Futuna","EH":"Western Sahara","YE":"Yemen","ZM":"Zambia","ZW":"Zimbabwe"}},"rule_scope_placeholders":{"ip":"IP address 1, IP address 2, etc.","url":"\/URL","useragent":"User Agent string","header":"Header field=value","fileext":"Ext1, Ext2, ...","mimetype":"Type 1, Type2, ...","organization":"Organization name"},"rule_action":{"Allow":"Allow","Block":"Block","Captcha":"Captcha","Handshake":"Extended Browser Validation","Gateway":"Browser Validation","Monitor":"Monitor"},"max_conditions":5,"rule_name_max_length":36}'>
        
        <div id="rule-edit-rule-name" class="row">
            <div class="col-lg-2">
                <label for="rule_name"">Rule Name:</label>
            </div>
            <div class="col-lg-9">
                <input type="text" id="rule_name" name="name" placeholder="Rule name - make it easy to remember" required>
              
            </div>
        </div>
        <div id="rules-container">
                    <div class="row  waf-rule-condition" data-condition-id="1">
                <div class="col-lg-2">
                    <label>
                        If:                    </label>
                </div>
                <div class="col-lg-3">
                                        <select id="rule_scope_1" name="scope" class="no-skin rule_scope" value="Ip" required>
                                                    <option value="Ip" selected>
                                IP                            </option>
                                                    <option value="IpRange" >
                                IP Range                            </option>
                                                    <option value="Url" >
                                URL                            </option>
                                                    <option value="UserAgent" >
                                User Agent                            </option>
                                                    <option value="Header" >
                                Header                            </option>
                                                    <option value="HttpMethod" >
                                HTTP Method                            </option>
                                                    <option value="FileExt" >
                                File Type/Extension                            </option>
                                                    <option value="MimeType" >
                                Content Type                            </option>
                                                    <option value="Country" >
                                Country                            </option>
                                                    <option value="Organization" >
                                Organization                            </option>
                                            </select>
                </div>
                <div class="col-lg-1">
                    =
                </div>
                <div class="scopes">
                <div class="col-lg-6 scope-data end">
                    <input id="rule_data_1" type="text" name="data" placeholder="">
                    <small class="form-error waf_rules_data_error"></small>
                    <input id="rule_exact_match_1" class="manual rule_exact_match" type="checkbox" name="function" data-allowed-scopes='["Url","Header","UserAgent"]'>
                    <label for="rule_exact_match_1">Exact Match</label>
                </div>
                <div class="col-lg-6 column scope-iprange">
                    <input id="rule_iprange_start_1" type="text" name="iprange_start" pattern="ipv4" placeholder="From IP">
                    <small class="form-error">Please enter a valid IP address</small>
                    <input id="rule_iprange_end_1" type="text" name="iprange_end" pattern="ipv4" placeholder="To IP">
                    <small class="form-error">Please enter a valid IP address</small>
                </div>
                <div class="col-lg-6 column scope-httpmethod">
                    <select id="rule_httpmethod_1" name="scope-httpmethod" class="no-skin">
                                                    <option value="post">POST</option>
                                                    <option value="get">GET</option>
                                                    <option value="head">HEAD</option>
                                                    <option value="put">PUT</option>
                                                    <option value="delete">DELETE</option>
                                                    <option value="patch">PATCH</option>
                                                    <option value="options">OPTIONS</option>
                                            </select>
                    <small class="form-error">HTTP Method is required</small>
                </div>
                <div class="col-lg-6 column scope-country">
                    <select id="rule_country_1" name="scope-country" class="no-skin">
                                                    <option value="AF">Afghanistan</option>
                                                    <option value="AL">Albania</option>
                                                    <option value="DZ">Algeria</option>
                                                    <option value="AS">American Samoa</option>
                                                    <option value="AD">Andorra</option>
                                                    <option value="AO">Angola</option>
                                                    <option value="AI">Anguilla</option>
                                                    <option value="AQ">Antarctica</option>
                                                    <option value="AG">Antigua and Barbuda</option>
                                                    <option value="AR">Argentina</option>
                                                    <option value="AM">Armenia</option>
                                                    <option value="AW">Aruba</option>
                                                    <option value="AU">Australia</option>
                                                    <option value="AT">Austria</option>
                                                    <option value="AZ">Azerbaijan</option>
                                                    <option value="BS">Bahamas</option>
                                                    <option value="BH">Bahrain</option>
                                                    <option value="BD">Bangladesh</option>
                                                    <option value="BB">Barbados</option>
                                                    <option value="BY">Belarus</option>
                                                    <option value="BE">Belgium</option>
                                                    <option value="BZ">Belize</option>
                                                    <option value="BJ">Benin</option>
                                                    <option value="BM">Bermuda</option>
                                                    <option value="BT">Bhutan</option>
                                                    <option value="BO">Bolivia</option>
                                                    <option value="BA">Bosnia and Herzegovina</option>
                                                    <option value="BW">Botswana</option>
                                                    <option value="BV">Bouvet Island</option>
                                                    <option value="BR">Brazil</option>
                                                    <option value="IO">British Indian Ocean Territory</option>
                                                    <option value="BN">Brunei Darussalam</option>
                                                    <option value="BG">Bulgaria</option>
                                                    <option value="BF">Burkina Faso</option>
                                                    <option value="BI">Burundi</option>
                                                    <option value="KH">Cambodia</option>
                                                    <option value="CM">Cameroon</option>
                                                    <option value="CA">Canada</option>
                                                    <option value="CV">Cape Verde</option>
                                                    <option value="KY">Cayman Islands</option>
                                                    <option value="CF">Central African Republic</option>
                                                    <option value="TD">Chad</option>
                                                    <option value="CL">Chile</option>
                                                    <option value="CN">China</option>
                                                    <option value="CX">Christmas Island</option>
                                                    <option value="CC">Cocos (Keeling) Islands</option>
                                                    <option value="CO">Colombia</option>
                                                    <option value="KM">Comoros</option>
                                                    <option value="CG">Congo</option>
                                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                                    <option value="CK">Cook Islands</option>
                                                    <option value="CR">Costa Rica</option>
                                                    <option value="CI">Cote D&apos;Ivoire</option>
                                                    <option value="HR">Croatia</option>
                                                    <option value="CU">Cuba</option>
                                                    <option value="CY">Cyprus</option>
                                                    <option value="CZ">Czech Republic</option>
                                                    <option value="DK">Denmark</option>
                                                    <option value="DJ">Djibouti</option>
                                                    <option value="DM">Dominica</option>
                                                    <option value="DO">Dominican Republic</option>
                                                    <option value="EC">Ecuador</option>
                                                    <option value="EG">Egypt</option>
                                                    <option value="SV">El Salvador</option>
                                                    <option value="GQ">Equatorial Guinea</option>
                                                    <option value="ER">Eritrea</option>
                                                    <option value="EE">Estonia</option>
                                                    <option value="ET">Ethiopia</option>
                                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                                    <option value="FO">Faroe Islands</option>
                                                    <option value="FJ">Fiji</option>
                                                    <option value="FI">Finland</option>
                                                    <option value="FR">France</option>
                                                    <option value="GF">French Guiana</option>
                                                    <option value="PF">French Polynesia</option>
                                                    <option value="TF">French Southern Territories</option>
                                                    <option value="GA">Gabon</option>
                                                    <option value="GM">Gambia</option>
                                                    <option value="GE">Georgia</option>
                                                    <option value="DE">Germany</option>
                                                    <option value="GH">Ghana</option>
                                                    <option value="GI">Gibraltar</option>
                                                    <option value="GR">Greece</option>
                                                    <option value="GL">Greenland</option>
                                                    <option value="GD">Grenada</option>
                                                    <option value="GP">Guadeloupe</option>
                                                    <option value="GU">Guam</option>
                                                    <option value="GT">Guatemala</option>
                                                    <option value="GN">Guinea</option>
                                                    <option value="GW">Guinea-Bissau</option>
                                                    <option value="GY">Guyana</option>
                                                    <option value="HT">Haiti</option>
                                                    <option value="HM">Heard Island and Mcdonald Islands</option>
                                                    <option value="VA">Holy See (Vatican City State)</option>
                                                    <option value="HN">Honduras</option>
                                                    <option value="HK">Hong Kong</option>
                                                    <option value="HU">Hungary</option>
                                                    <option value="IS">Iceland</option>
                                                    <option value="IN">India</option>
                                                    <option value="ID">Indonesia</option>
                                                    <option value="IR">Iran, Islamic Republic of</option>
                                                    <option value="IQ">Iraq</option>
                                                    <option value="IE">Ireland</option>
                                                    <option value="IL">Israel</option>
                                                    <option value="IT">Italy</option>
                                                    <option value="JM">Jamaica</option>
                                                    <option value="JP">Japan</option>
                                                    <option value="JO">Jordan</option>
                                                    <option value="KZ">Kazakhstan</option>
                                                    <option value="KE">Kenya</option>
                                                    <option value="KI">Kiribati</option>
                                                    <option value="KP">Korea, Democratic People&apos;s Republic of</option>
                                                    <option value="KR">Korea, Republic of</option>
                                                    <option value="KW">Kuwait</option>
                                                    <option value="KG">Kyrgyzstan</option>
                                                    <option value="LA">Lao People&apos;s Democratic Republic</option>
                                                    <option value="LV">Latvia</option>
                                                    <option value="LB">Lebanon</option>
                                                    <option value="LS">Lesotho</option>
                                                    <option value="LR">Liberia</option>
                                                    <option value="LY">Libyan Arab Jamahiriya</option>
                                                    <option value="LI">Liechtenstein</option>
                                                    <option value="LT">Lithuania</option>
                                                    <option value="LU">Luxembourg</option>
                                                    <option value="MO">Macao</option>
                                                    <option value="MK">Macedonia, the Former Yugoslav Republic of</option>
                                                    <option value="MG">Madagascar</option>
                                                    <option value="MW">Malawi</option>
                                                    <option value="MY">Malaysia</option>
                                                    <option value="MV">Maldives</option>
                                                    <option value="ML">Mali</option>
                                                    <option value="MT">Malta</option>
                                                    <option value="MH">Marshall Islands</option>
                                                    <option value="MQ">Martinique</option>
                                                    <option value="MR">Mauritania</option>
                                                    <option value="MU">Mauritius</option>
                                                    <option value="YT">Mayotte</option>
                                                    <option value="MX">Mexico</option>
                                                    <option value="FM">Micronesia, Federated States of</option>
                                                    <option value="MD">Moldova, Republic of</option>
                                                    <option value="MC">Monaco</option>
                                                    <option value="MN">Mongolia</option>
                                                    <option value="MS">Montserrat</option>
                                                    <option value="MA">Morocco</option>
                                                    <option value="MZ">Mozambique</option>
                                                    <option value="MM">Myanmar</option>
                                                    <option value="NA">Namibia</option>
                                                    <option value="NR">Nauru</option>
                                                    <option value="NP">Nepal</option>
                                                    <option value="NL">Netherlands</option>
                                                    <option value="AN">Netherlands Antilles</option>
                                                    <option value="NC">New Caledonia</option>
                                                    <option value="NZ">New Zealand</option>
                                                    <option value="NI">Nicaragua</option>
                                                    <option value="NE">Niger</option>
                                                    <option value="NG">Nigeria</option>
                                                    <option value="NU">Niue</option>
                                                    <option value="NF">Norfolk Island</option>
                                                    <option value="MP">Northern Mariana Islands</option>
                                                    <option value="NO">Norway</option>
                                                    <option value="OM">Oman</option>
                                                    <option value="PK">Pakistan</option>
                                                    <option value="PW">Palau</option>
                                                    <option value="PS">Palestinian National Authority</option>
                                                    <option value="PA">Panama</option>
                                                    <option value="PG">Papua New Guinea</option>
                                                    <option value="PY">Paraguay</option>
                                                    <option value="PE">Peru</option>
                                                    <option value="PH">Philippines</option>
                                                    <option value="PN">Pitcairn</option>
                                                    <option value="PL">Poland</option>
                                                    <option value="PT">Portugal</option>
                                                    <option value="PR">Puerto Rico</option>
                                                    <option value="QA">Qatar</option>
                                                    <option value="RE">Reunion</option>
                                                    <option value="RO">Romania</option>
                                                    <option value="RU">Russian Federation</option>
                                                    <option value="RW">Rwanda</option>
                                                    <option value="SH">Saint Helena</option>
                                                    <option value="KN">Saint Kitts and Nevis</option>
                                                    <option value="LC">Saint Lucia</option>
                                                    <option value="PM">Saint Pierre and Miquelon</option>
                                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                                    <option value="WS">Samoa</option>
                                                    <option value="SM">San Marino</option>
                                                    <option value="ST">Sao Tome and Principe</option>
                                                    <option value="SA">Saudi Arabia</option>
                                                    <option value="SN">Senegal</option>
                                                    <option value="CS">Serbia and Montenegro</option>
                                                    <option value="SC">Seychelles</option>
                                                    <option value="SL">Sierra Leone</option>
                                                    <option value="SG">Singapore</option>
                                                    <option value="SK">Slovakia</option>
                                                    <option value="SI">Slovenia</option>
                                                    <option value="SB">Solomon Islands</option>
                                                    <option value="SO">Somalia</option>
                                                    <option value="ZA">South Africa</option>
                                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                    <option value="ES">Spain</option>
                                                    <option value="LK">Sri Lanka</option>
                                                    <option value="SD">Sudan</option>
                                                    <option value="SR">Suriname</option>
                                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                                    <option value="SZ">Swaziland</option>
                                                    <option value="SE">Sweden</option>
                                                    <option value="CH">Switzerland</option>
                                                    <option value="SY">Syrian Arab Republic</option>
                                                    <option value="TW">Taiwan, Province of China</option>
                                                    <option value="TJ">Tajikistan</option>
                                                    <option value="TZ">Tanzania, United Republic of</option>
                                                    <option value="TH">Thailand</option>
                                                    <option value="TL">Timor-Leste</option>
                                                    <option value="TG">Togo</option>
                                                    <option value="TK">Tokelau</option>
                                                    <option value="TO">Tonga</option>
                                                    <option value="TT">Trinidad and Tobago</option>
                                                    <option value="TN">Tunisia</option>
                                                    <option value="TR">Turkey</option>
                                                    <option value="TM">Turkmenistan</option>
                                                    <option value="TC">Turks and Caicos Islands</option>
                                                    <option value="TV">Tuvalu</option>
                                                    <option value="UG">Uganda</option>
                                                    <option value="UA">Ukraine</option>
                                                    <option value="AE">United Arab Emirates</option>
                                                    <option value="GB">United Kingdom</option>
                                                    <option value="US">United States</option>
                                                    <option value="UM">United States Minor Outlying Islands</option>
                                                    <option value="UY">Uruguay</option>
                                                    <option value="UZ">Uzbekistan</option>
                                                    <option value="VU">Vanuatu</option>
                                                    <option value="VE">Venezuela</option>
                                                    <option value="VN">Viet Nam</option>
                                                    <option value="VG">Virgin Islands, British</option>
                                                    <option value="VI">Virgin Islands, U.S.</option>
                                                    <option value="WF">Wallis and Futuna</option>
                                                    <option value="EH">Western Sahara</option>
                                                    <option value="YE">Yemen</option>
                                                    <option value="ZM">Zambia</option>
                                                    <option value="ZW">Zimbabwe</option>
                                            </select>
                    <small class="form-error">Country is required</small>
                </div>
              </div>
                <div class="small-2 text-right column">
                                                    <span class="action">
                                <a href="#" class="rule-condition-copy icon-button" title="Copy this Condition to a new line">
                                    <i class="mdi mdi-content-copy"></i>
                                </a>
                            </span>
                                                                                        </div>
            </div>
                    
                    
                    
                    <div id="rules-action" class="row small-collapse">
                <div class="small-12 medium-4 large-3 columns">
                    <label>Then the action is:</label>
                </div>
                <div class="small-12 medium-8 large-9 columns">
                    <select id="rule_action" name="action" class="no-skin" required>
                                                    <option value="Allow">Allow</option>
                                                    <option value="Block">Block</option>
                                                    <option value="Captcha">Captcha</option>
                                                    <option value="Handshake">Extended Browser Validation</option>
                                                    <option value="Gateway">Browser Validation</option>
                                                    <option value="Monitor">Monitor</option>
                                            </select>
                </div>
            </div>
        </div>


        <div class="column row text-right small-collapse">
            <input type="hidden" name="id" />
            <button id="rule-condition-add" class="button secondary on-bg"><i class="mdi mdi-plus"></i>Add Condition</button>
            <button id="rule-save" type="submit" class="button primary">Save</button>
        </div>
    </form>
</div>



</div></div></div>









@stop

@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
