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
  protected $_attributes = array(
              'title'             = '',
              'class'             = 'cs-calendar',
              'date_start'        = date('U'), //first date for display
              'date_end'          = date('U'), //last date for display
              'events'            = array(), //holds the event objects
              'display_type'      = 'month'; // which stylesheet (day, week, month)
              );
  
  public function __construct($title = '')
  {
    $this->_title = srt_replace(/\W/, '-', $title);
  }
  
  public function addEvent()
  {
    $args = func_get_args();
    //if we got an Event Object
    if(get_class($args[0]) == 'csOopCalendarEvent')
    {
      $event = $args[0]
    }
    //otherwise pass on whatever came in to create a new event
    else
    {
      if(is_array($args[0]))
      {
        $event = new csOopCalendarEvent($args[0]);
      }
      else
      {
        $event = new csOopCalendarEvent($args);
      }
    }
    if(isset($event))
    {
      $this->_events[] = $event;
      $this->_updateExtremeDates($event);
    }
  }
  
  public function parseFromiCalendar($i_calendar_string='')
  {
    //take the string of iCalendar data and parse it
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
  
  protected function _updateExtremeDates($event = new csOopCalendarEvent())
  {
    if($this->getAttribute('date_start') > $event->getAttribute('dtstart'))
    {
      $this->_attributes['date_start'] = $event->getAttribute('dtstart');
    }
    elseif($this->getAttribute('date_end') < $event->getAttribute('dtend'))
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
}
?>