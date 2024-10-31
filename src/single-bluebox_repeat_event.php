<?php

get_header();

$typeOfEvent = get_field('type_of_event');

if ($typeOfEvent === 'recurring') {
  $dateToShow = get_field('date');
} else {
  $dateToShow = get_field('date');
}
?>

<div class="bluebox-repeat-container">
    <div class="bluebox-repeat-event-section-single">
    <h1><?php the_title(); ?></h1>
    <?php if ($typeOfEvent === 'recurring') {
      $activeDays = get_field('day'); ?>
            <p>Days: <?php echo bluebox_repeat_event_get_day_string($activeDays); ?></p>
        <?php
    } ?>
    <?php if ($typeOfEvent === 'single') { ?>
                <p>Date: <?php echo date('jS F Y', DateTime::createFromFormat('d/m/Y', $dateToShow)->getTimestamp()); ?></p>
    <?php } ?>
    <p>Time: 
    <?php echo get_field('time_start'); ?>
        - 
    <?php echo get_field('time_end'); ?></p>
    </div>
    <?php the_content(); ?>
</div>

<?php get_footer();
