<!-- START CALENDAR DISPLAY -->
<?php $css = new csOopCss(); ?>
<div class="calendar" id="calendar-<?php echo $calendar->getIdHash(); ?>">
  <div class="wrapper" id="calendar-wrapper-<?php echo $calendar->getIdHash(); ?>">
    <?php $days = $calendar->getDays(); ?>
    <?php if(sizeof($days) > 0): ?>
      <div class="days" id="days-<?php echo $calendar->getIdHash(); ?>">
        <div class="wrapper" id="days-wrapper-<?php echo $calendar->getIdHash(); ?>">
          <?php foreach($days as $day): ?>
            <?php include_partial('csOopCalendar/day', array('day' => $day, 'css' => $css)); ?>
          <?php endforeach; ?>
        </div> <!-- days-wrapper-<?php echo $calendar->getIdHash(); ?> -->
      </div> <!-- days-<?php echo $calendar->getIdHash(); ?> -->
    <?php else: ?>
      <span class="error" id="days-error-<?php echo $calendar->getIdHash(); ?>">
        No days found...
      </span> <!-- days-error-<?php echo $calendar->getIdHash(); ?> -->
    <?php endif; ?>
  </div> <!-- calendar-wrapper-<?php echo $calendar->getIdHash(); ?> -->
</div> <!-- calendar-<?php echo $calendar->getIdHash(); ?> -->

<!-- END CALENDAR DISPLAY -->
