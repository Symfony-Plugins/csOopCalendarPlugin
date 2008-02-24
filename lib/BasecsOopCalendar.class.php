<?php

/*
 * This file is part of the csOopCalendar package.
 * (c) 2006-2007 Josh R Reynolds <reynoldsj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * Base Symfony OOP Calendar Class
 *
 * @package   csOopCalendar
 * @author    Josh R Reynolds <reynoldsj@gmail.com>
 * @version   SVN: $Id:
 **/
class BasecsOopCalendar
{ 
  public function __construct($title = '')
  {    
    $this->_attributes = array(
      'title'             => '',
      'class'             => 'cs-calendar',
      'date_start'        => date('U'), //first date for display
      'date_end'          => date('U'), //last date for display
      'events'            => array(), //holds the event objects
      'display_type'      => 'month', // which stylesheet (day, week, month)
      );
      
    //for logging to symfony
    if (sfConfig::get('sf_logging_enabled'))
    {
      $this->_logger = sfContext::getInstance()->getLogger();
    }
  }
  
  public function setTitle($title)
  {
    //sanitize before setting
    $this->_title = preg_replace('/\W/', '-', $title);
  }
  
  public function addEvent()
  {
    $args = func_get_args();
    //if we got an Event Object
    if(get_class($args[0]) == 'csOopCalendarEvent')
    {
      $this->_logToSymfony('Adding Event from an event object');
      $event = $args[0];
    }
    //otherwise pass on whatever came in to create a new event
    else
    {
      if(is_array($args[0]))
      {
        $this->_logToSymfony('Adding Event from an array');
        $event = new csOopCalendarEvent($args[0]);
      }
      else
      {
        $this->_logToSymfony('Not sure what we got to add an event with, passing along args');
        $event = new csOopCalendarEvent($args);
      }
    }
    if(isset($event))
    {
      $this->_events[] = $event;
      $this->_updateExtremeDates($event);
    }
  }
  
  /**
   * parseFromiCalender
   * 
   *
   * @param string $i_calendar_string string in iCalendar format to parse
   * @return void
   * @author Josh Reynolds
   */
  public function parseFromICalendar($i_calendar_string='')
  {
    //grab all the VEVENTs (parsing ideas from paul on phpclasses.org "ical parser")
    $result = array();
    preg_match_all('/(BEGIN:VEVENT.*?END:VEVENT)/si', 
      $i_calendar_string, $result, PREG_PATTERN_ORDER);
      
    $this->_logToSymfony('csOopCalendar: Parser got '.sizeof($result[0]).' events');
    
    for ($i = 0; $i < count($result[0]); $i++) 
    {        
      //prep the line breaks and explode
      $tmpstring = str_replace("\r","\n",str_replace("\r\n","\n",$result[0][$i]));
      $tmpbyline = explode("\n", $tmpstring);
      
      foreach ($tmpbyline as $item) 
      {
        $tmpholderarray = explode(":",$item);
        if (count($tmpholderarray) >1) 
        {
          $this->_logToSymfony('Inserting property: '.
            trim($tmpholderarray[0]).' = '.trim($tmpholderarray[1]));
          $majorarray[trim($tmpholderarray[0])] = trim($tmpholderarray[1]);
        }
      }
      
      //lets just finish what we started..
      if (preg_match('/DESCRIPTION:(.*)END:VEVENT/si', $result[0][$i], $regs))
      {
        $majorarray['DESCRIPTION'] = str_replace("  ", " ", 
          str_replace("\r\n", "", $regs[1]));
      }
      
      $this->addEvent($majorarray);
      
      unset($majorarray);
    }
  }
  
  public function getIdHash()
  {
    return md5($this->_attributes['title'].
            $this->_attribute['class'].
            $this->_attributes['date_start'].
            $this->_attributes['date_end'].
            $this->_attributes['display_type']);
  }
  
  public function getDays()
  {
    $this->_orderEventsByDate();
    
    $days = array();
    $current_day = '';
    foreach($this->_events as $e)
    {
      if($current_day != date("y-m-d" , $e->getAttribute('dtstart')))
      {
        $day = new csOopCalendarDay($current_day);
        $days[] = $day;
      }
      
      $day->addEvent($e);
    }
    return $days;
  }
  
  protected function _orderEventsByDate()
  {
    //build an array with keys of the dates
    $dates = array();
    foreach($this->_events as $e)
    {
      $dates[$e->getAttribute('dtstart')] = $e;
    }
    
    //sort by the dates
    $dates = ksort($dates);
    
    //rebuild the dates array
    $this->events = array();
    foreach($dates as $date => $event)
    {
      $this->events[] = $event;
    }
  }
  
  protected function _setAttribute($attribute = '', $value)
  {
    if(isset($this->_attributes[$attribute]) 
      && $this->_sameType($this->_attributes[$attribute], $value))
    {
      
    }
  }
  
  protected function _updateExtremeDates($event = csOopCalendarEvent)
  {
    if($this->_attributes['date_start'] > $event->getAttribute('dtstart'))
    {
      $this->_attributes['date_start'] = $event->getAttribute('dtstart');
    }
    elseif($this->_attributes['date_end'] < $event->getAttribute('dtend'))
    {
      $this->_attributes['date_end'] = $event->getAttribute('dtend');
    }
  }
  
  protected function _sameType($first, $second)
   {
     if(is_string($first) && is_string($second))
     {
       return true;
     }
     elseif(is_array($first) && is_array($second))
     {
       return true;
     }
     else
     {
       return false;
     }
   }
   
   public function _logToSymfony($message = '')
   {
      if(isset($this->_logger))
      {
        $this->_logger->info('csOopCalendar: '.$message);
      }
   }
}
?>