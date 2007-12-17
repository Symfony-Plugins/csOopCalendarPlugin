<?php

/*
 * This file is part of the csOopCalendar package.
 * (c) 2007-2008 Josh R Reynolds <reynoldsj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * Base Symfony OOP Calendar Event Class
 *
 * @package   csCalendar
 * @author    Josh R Reynolds <reynoldsj@gmail.com>
 * @version   SVN: $Id:
 **/
class BasecsOopCalendarEvent
{
  //class attributes based on the RFC 2445 iCalendar Spec
  //found at http://tools.ietf.org/html/rfc2445
  protected $_init_date     = date('U'),
            $_attributes    = array(
              'class'       => 'event', //event class
              'created'     => $this->_init_date, //created at date
              'description' => '', //text description
              'dtstart'     => $this->_init_date, //start timestamp
              'geo'         => '', //not sure what this is...
              'last_mod'    => $this->_init_date, //last modification
              'location'    => '', //location string
              'organizer'   => '', //organizer
              'summary'     => 'event', //summary
              'transp'      => '', //also not sure...
              'uid'         => '', 
              'url'         => '', //where to get details
              'recurid'     => '', //what other ones is this related to
              'dtend'       => $this->_init_date, //end timestamp
              'duration'    => 0, //duration in seconds
              'attach'      => array(),
              'attendee'    => array(),
              'categories'  => array(),
              'comment'     => array(),
              'contact'     => array(),
              'exdate'      => array(),
              'exrule'      => array(),
              'rstatus'     => array(),
              'related'     => array(),
              'resources'   => array(),
              'rdate'       => array(),
              'rrule'       => array(),
              'xprop'       => array(),
              ),
            $_human_names   = array(
              'class'       => 'Class', //event class
              'created'     => 'Created At', //created at date
              'description' => 'Description', //text description
              'dtstart'     => 'Event Start', //start timestamp
              'geo'         => 'Geographic Location', //not sure what this is...
              'last_mod'    => 'Last Modified', //last modification
              'location'    => 'Location', //location string
              'organizer'   => 'Organizer', //organizer
              'summary'     => 'Summary', //summary
              'transp'      => 'Transp', //also not sure...
              'uid'         => 'UID', 
              'url'         => 'URL', //where to get details
              'recurid'     => 'Recurrence ID', //what other ones is this related to
              'dtend'       => 'Event End', //end timestamp
              'duration'    => 'Duration', //duration in seconds
              'attach'      => 'Attachments',
              'attendee'    => 'Attendee',
              'categories'  => 'Category',
              'comment'     => 'Comment',
              'contact'     => 'Contact',
              'exdate'      => 'ExDate',
              'exrule'      => 'ExRule',
              'rstatus'     => 'RStatus',
              'related'     => 'Related',
              'resources'   => 'Resources',
              'rdate'       => 'RDate',
              'rrule'       => 'RRule',
              'xprop'       => 'XProp',
              ),
            $_date_format   = 'U',
            $_dates         = array(
              'created',
              'dtstart',
              'last_mod',
              'dtend').
            $_minute_percent  = 100/(60*24); //100%/(minutes*hours)
              
   public function __construct()
   {
     $args = func_get_args();
     
     //if we got any arguments, parse them
     if(isset($args[0]))
     {
       //if we got a bunch of individual string arguments, 
       // they should be in order based on the iCalendar standard
       if(is_string($args[0]))
       {
         foreach($this->_attributes as $attribute => $value)
         {
           $key = array_search($attribute, $args);
           if($key != false)
           {
             $this->parseAttribute($attribute, $value);
           }
         }
       }
       elseif(is_array($args[0]))
       {
         $this->parseFromArray();
       }
     }
     
   }
   
   public function getIdHash()
   {
     return md5($this->_attributes['class'].
       $this->_attributes['created'].
       $this->_attributes['dtstart'].
       $this->_attributes['dtend'].
       $this->_attributes['description'].
       $this->_attributes['summary'].
       date('U'));
   }
   
   public function getAttributeHumanName($attribute='')
   {
     return (array_key_exists($attribute, $this->_human_names)) ? 
      $this->_human_names[$attribute] : 'Attribute Not Found...';
   }
   
   public function getInitDate()
   {
     return $this->_init_date;
   }
   
   public function setDateFormat($value='U')
   {
     $this->_date_format = $value;
   }
   
   public function getDateFormat()
   {
     return $this->_date_format;
   }
   
   public function getMinutePercent()
   {
     return $this->_minute_percent;
   }
   
   public function parseFromArray($array = array())
   {
     foreach($array as $attribute => $value)
     {
       $this->parseAttribute($attribute, $value);
     }
   }
   
   public function parseFromiCalendar($i_calendar='')
   {
    //do the parsing from the icalendar string here
    
    //not yet implemented
   }
   
   protected function parseAttribute($attribute = '', $value='')
   {
    //find out if this is a valid attribute 
    if(isset($this->_attributes[$attribute]))
    {
      //figture out what type of attribute it is
      if(is_string($this->_attributes[$attribute]) && is_string($value))
      {
        $this->setAttribute($attribute, $value);
      }
      elseif(is_array($this->_attributes[$attribute]) && is_array($value))
      {
        foreach($value as $v)
        {
          $this->addAttribute($attribute, $value);
        }
      }
    }
   }
   
   public function setAttribute($attribute='', $value)
   {
     if($isset($this->_attributes[$attribute]) && $this->_sameType($this->_attributes[$attribute], $value))
     {
       $this->_attributes[$attribute] = $value;
     }
   }
   
   public function addAttribute($attribute='', $value)
   {
     if(isset($this->_attributes[$attribute]) 
       && is_array($this->_attributes[$attribute])
       && is_string($value))
     {
       $this->_attributes[$attribute][] = $value;
     }
   }
   
   public function getAttribute($attribute = '')
   {
     if(array_key_exists($attribute, $this->_attributes))
     {
       return $this->_attributes[$attribute];
     }
     else
     {
       return false;
     }
   }
   
   public function getDuration($unit = 'minutes')
   {
     $duration = 0;
     switch($unit)
     {
       case 'minutes':
         
         break;
       case 'hours':
         
         break;
     }
     return ($duration > 0) ? $duration : 0;
   }
   
   public function getAttributes()
   {
     return $this->_attributes;
   }

   public function toArray()
   {
     return $this->_attributes;
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
