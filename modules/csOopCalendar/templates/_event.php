<!-- START EVENT DISPLAY -->
<div class="event" id="event-<?php echo $event->getIdHash(); ?>">
  <?php include_partial('csOopCalendar/event_position', 
    array('event' => $event, 'css' => $css)); ?>
  <div class="container" id="event-container-<?php echo $event->getIdHash(); ?>">
    <?php $date_types = $event->getDateTypes(); ?>
    <?php $date_format = $event->getDateFormat(); ?>
    <?php $init_date = $event->getInitDate(); ?>
    <?php foreach($event->getAttributes() as $attribute => $value): ?>
      <div class="attribute $attribute" id="attribute-$attribute-<?php echo $event->getIdHash(); ?>">
        <label>
          <?php echo $event->getAttributeHumanName($attribute).' '; ?>
        </label>
        <span class="value $attribute" id="value-$attribute-<?php echo $event->getIdHash(); ?>"
          <?php if(array_search($attribute, $date_types)): ?>
            <?php echo date($date_format, stringtotime($value)); ?>
            <?php if(date($date_format, stringtotime($value) == $init_date): ?>
              <?php $css->addSelector("attribute-$attribute-".$event->getIdHash())->
                addStyle('display', 'none'); ?>
            <?php endif; ?>
          <?php elseif(is_array($value)): ?>
            <?php echo implode(', ', $value); ?>
            <?php if(sizeof($value) == 0): ?>
              <?php $css->addSelector("attribute-$attribute-".$event->getIdHash())->
                addStyle('display', 'none'); ?>
            <?php endif; ?>
          <?php else: ?>
            <?php echo $value; ?>
            <?php if($value == ''): ?>
              <?php $css->addSelector("attribute-$attribute-".$event->getIdHash())->
                addStyle('display', 'none'); ?>
            <?php endif; ?>
          <?php endif; ?>
      </div> <!-- event-attribute-$attribute-<?php echo $event->getIdHash(); ?> -->
    <?php endforeach; ?>
  </div> <!-- event-container-<?php echo $event->getIdHash(); ?> -->
</div> <!-- event-<?php echo $event->getIdHash(); ?> -->
<!-- END EVENT DISPLAY -->
