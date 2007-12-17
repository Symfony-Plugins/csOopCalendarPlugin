<?php 
$selector = new csOopCssSelector('event-'.$event->getIdHash())
$selector->addStyle('position' => 'relative');
$selector->addStyle('top' => 
  ($event->getMinutePercent()*(($event->getAttribute('dtstart')/60)%(24*60))).'%');
$selector->addStyle('height' => 
  ($event->getMinutePercent()*($event->getDuration()/60)).'%');
$css->addSelector($selector);
?>