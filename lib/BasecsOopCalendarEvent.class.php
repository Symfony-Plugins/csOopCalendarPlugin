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
   public function __construct()
   {
     //for logging to symfony
      if (sfConfig::get('sf_logging_enabled'))
      {
        $this->_logger = sfContext::getInstance()->getLogger();
      }
      
     //build all the fixtures we need
     $this->_init_date     = date('U');
     $this->_attributes    = array(
       'CLASS'       => 'event', //event class
       'CREATED'     => $this->_init_date, //created at date
       'DESCRIPTION' => '', //text description
       'DTSTART'     => $this->_init_date, //start timestamp
       'GEO'         => '', //not sure what this is...
       'LAST_MOD'    => $this->_init_date, //last modification
       'LOCATION'    => '', //location string
       'ORGANIZER'   => '', //organizer
       'SUMMARY'     => 'event', //summary
       'TRANSP'      => '', //also not sure...
       'UID'         => '', 
       'URL'         => '', //where to get details
       'RECURID'     => '', //what other ones is this related to
       'DTEND'       => $this->_init_date, //end timestamp
       'DURATION'    => 0, //duration in seconds
       'ATTACH'      => array(),
       'ATTENDEE'    => array(),
       'CATEGORIES'  => array(),
       'COMMENT'     => array(),
       'CONTACT'     => array(),
       'EXDATE'      => array(),
       'EXRULE'      => array(),
       'RSTATUS'     => array(),
       'RELATED'     => array(),
       'RESOURCES'   => array(),
       'RDATE'       => array(),
       'RRULE'       => array(),
       'XPROP'       => array(),
       );
     $this->_human_names   = array(
       'CLASS'       => 'Class', //event class
       'CREATED'     => 'Created At', //created at date
       'DESCRIPTION' => 'Description', //text description
       'DTSTART'     => 'Event Start', //start timestamp
       'GEO'         => 'Geographic Location', //not sure what this is...
       'LAST_MOD'    => 'Last Modified', //last modification
       'LOCATION'    => 'Location', //location string
       'ORGANIZER'   => 'Organizer', //organizer
       'SUMMARY'     => 'Summary', //summary
       'TRANSP'      => 'Transp', //also not sure...
       'UID'         => 'UID', 
       'URL'         => 'URL', //where to get details
       'RECURID'     => 'Recurrence ID', //what other ones is this related to
       'DTEND'       => 'Event End', //end timestamp
       'DURATION'    => 'Duration', //duration in seconds
       'ATTACH'      => 'Attachments',
       'ATTENDEE'    => 'Attendee',
       'CATEGORIES'  => 'Category',
       'COMMENT'     => 'Comment',
       'CONTACT'     => 'Contact',
       'EXDATE'      => 'ExDate',
       'EXRULE'      => 'ExRule',
       'RSTATUS'     => 'RStatus',
       'RELATED'     => 'Related',
       'RESOURCES'   => 'Resources',
       'RDATE'       => 'RDate',
       'RRULE'       => 'RRule',
       'XPROP'       => 'XProp',
       );
     $this->_date_format   = 'U';
     $this->_dates         = array(
       'CREATED',
       'DTSTART',
       'LAST_MOD',
       'DTEND');
     $this->_minute_percent  = 100/(60*24); //100%/(minutes*hours);
     $this->_timezone = '';
     
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
         $this->parseFromArray($args[0]);
       }
     }
   }
   
   public function __toString($format = "icalendar")
   {
     switch($format)
     {
        case 'icalendar':
          $out = $this->_buildICalendar();
          break;
     }
     return $out;
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
       $this->_logger->info('csOopCalendarEvent: Parsing Attribute = '.$attribute.' = '.$value);
       $this->_parseAttribute($attribute, $value);
     }
   }
   
   public function setAttribute($attribute='', $value)
   {
     if(isset($this->_attributes[$attribute]) &&
       $this->_sameType($this->_attributes[$attribute], $value))
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
       $this->_logger->info('csOopCalendarEvent: Adding attribute - '.$attribute.' = '.$value);
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
   
   /**
    * _parseAttribute
    * Takes an attribute and value and parses into internal arrays.
    * If the attribute has a timezone on it (ie DTSTART;TZID=US/CENTRAL)
    * then we set the timezone for using in output later.
    * Currently assumes that timezones for all dates in event are same.
    *
    * @param string $attribute The name of the attribute we are setting
    * @param string $value 
    * @return void
    * @author Josh Reynolds
    */
   
   protected function _parseAttribute($attribute = '', $value='')
    {
     //timezones with dates are formatted like DTSTART;TZID=US/CENTRAL
     $attribute_array = explode(';', $attribute);
     $this->_logger->info('csOopCalendarEvent: Trying to add attribute - '.$attribute_array[0].' = '.$value);
     
     //Check for attribute existence against local array
     if(isset($this->_attributes[$attribute_array[0]]))
     {
       //If the full attribute name passed in matches (shortcut for non-dates)
       if(isset($this->_attributes[$attribute]))
       {
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
       //if there is something in the second position of this array, 
       // it was a date with a timezone
       elseif(isset($attribute_array[1]) && in_array($attribute_array[0], $this->_dates))
       {
         $this->_logToSymfony('Timezone is: '.$attribute_array[1]);
         $this->_timezone = $attribute_array[1];
         $this->setAttribute($attribute_array[0], $value);
       }
     }
    }
   
   /**
    * _buildICalendar
    * Builds a string in the iCalendar format for output purposes.
    * Called by toString('icalendar') for printing object event.
    * Timezones are currently assumed to be the same across all date
    * attributes for events.
    *
    * @return void
    * @author Josh Reynolds
    */
   
   protected function _buildICalendar()
    {
      $out = "BEGIN:VEVENT\n";
      foreach($this->_attributes as $key => $value)
      {
        //if key is a date, append the timezone
        $keyout = (in_array($key, $this->_dates)) ? $key.';'.$this->_timezone : $key;
        //if value is a string, just add it
        if(is_string($value) && $value != '')
        {
          $out .= strtoupper($keyout).":".strtoupper($value)."\n";
        }
        //for now if value is an array, ignore it
        elseif(is_array($value))
        {
          //do something about arrays here...
        }
      }
      $out .= "END:EVENT\n";
      return $out;
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
         $this->_logger->info('csOopCalendarEvent: '.$message);
       }
    }
}
?>
