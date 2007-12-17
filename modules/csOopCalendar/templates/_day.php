<!-- START DAY DISPLAY -->
<div class="day" id="day-<?php echo $day->getIdHash(); ?>">
  <div class="wrapper" id="day-wrapper-<?php echo $day->getIdHash(); ?>">
    <?php $events = $day->getEvents(); ?>
    <?php if(sizeof($events) > 0): ?>
      <div class="events" id="events-<?php echo $day->getIdHash(); ?>">
        <div class="wrapper" id="events-wrapper-<?php echo $day->getIdHash(); ?>">
          <?php foreach($events as $event): ?>
            <?php include_partial('csOopCalendar/event', array('event' => $event, 'css' => $css)); ?>
          <?php endforeach; ?>
        </div> <!-- events-wrapper-<?php echo $day->getIdHash(); ?> -->
      </div> <!-- events-<?php echo $day->getIdHash(); ?> -->
    <?php else: ?>
      <span class="error" id="error-<?php echo $day->getIdHash(); ?>">
        No Events Found...
      </span> <!-- error-<?php echo $day->getIdHash(); ?> -->
    <?php endif; ?>
  </div> <!-- day-wrapper-<?php echo $day->getIdHash(); ?> -->
</div> <!-- day-<?php echo $day->getIdHash(); ?> -->
<!-- END DAY DISPLAY -->