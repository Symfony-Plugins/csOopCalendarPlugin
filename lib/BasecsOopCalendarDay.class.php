<?php

/*
 * This file is part of the csOopCalendar package.
 * (c) 2006-2007 Josh R Reynolds <reynoldsj@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * Base Symfony OOP Calendar Day Class
 *
 * @package   csOopCalendar
 * @author    Josh R Reynolds <reynoldsj@gmail.com>
 * @version   SVN: $Id:
 **/
class BasecsOopCalendarDay
{
  protected $_attributes = array(
              'title'    => (string) rand(0, 1000),
              'date'     => date('U'),
              'events'   => array(),
              );
  
  public function __construct()
  {
    $args = func_get_args();
    if(isset($args[0]) && is_string($args[0]))
    {
      $this->_attributes['date'] = date('t', strtotime($args[0]));
    }
    if (isset($args[1]))
    {
      if(is_string($args[1]))
      {
        $this->_attributes['title'] = $args[1];
      }
      if(is_array($args[1]))
      {
        //parse options in from array
      }
    }
  }
  
  public function getEvents()
  {
    return $this->_attributes['events'];
  }
  
  public function getIdHash()
  {
    return md5($this->_attributes['title'].
            $this->_attributes['date'].
            rand(1, 1000));
  }
}
?>
