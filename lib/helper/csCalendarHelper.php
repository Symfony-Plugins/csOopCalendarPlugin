<?php 
function cs_calendar_display($calendar = )
{
  $out = "<!--  START CALENDAR DISPLAY-->\n\n";
  $out .= "<div class=\"calendar-wrapper\"\n";
  $out .= "  <div class=\"".$calendar->getClass()."\" id=\"calendar-".$calendar->getIdHash()."\">\n";
  foreach($calendar->getDays() as $day):
    $out .= "    <div class=\"".$day->getClass()."\" id=\"day-".$day->getIDHash()."\">\n";
    
    $out .= "    </div> <!-- END DAY -->\n";
  endforeach;
  $out .= "  </div> <!-- END CALENDAR -->\n";
  $out .= "</div> <!-- END CALENDAR WRAPPER -->\n\n";
  $out .= "<!-- END CALENDAR DISPLAY-->";
}
?>