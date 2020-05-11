<?php


     function dateFormatting($attr,$xlabel=NULL) {
    if(Session::has('current_time_zone')){
       $current_time_zone = Session::get('current_time_zone');
       $utc = strtotime($attr)-date('Z'); // Convert the time zone to GMT 0. If the server time is what ever no problem.

       $attr = $utc+$current_time_zone; // Convert the time to local time

       if($xlabel=="hour")
       {
       		$attr = date("D, hA", $attr+3600);
       }
       elseif($xlabel=="15m")
       {
          $attr = date("D, h:iA", $attr+900);
       }
       elseif($xlabel=="30m")
       {
          $attr = date("D, h:iA", $attr+1800);
       }
       elseif($xlabel=="day")
       {
       		$attr = date("D, M j ", $attr);
       }
       elseif($xlabel=="minute")
       {
       		$attr = date("D, h:iA", $attr+60);
       }

        elseif($xlabel=="Formated")
       {
          $attr = date("D, jS M, Y h:iA", $attr);
       }


       elseif($xlabel=="logsAnalysis")
       {
          $attr = date("m/d/Y h:i A", $attr);
       }
       

       else
       {
       		$attr = date("l, hA", $attr);
       }
       
    }
    return $attr;
	}
