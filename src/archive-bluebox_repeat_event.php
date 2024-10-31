<?php

get_header();

//Display all event items
$today = date('Ymd');

$posts = get_posts([
  'post_type' => 'bluebox_repeat_event',
  'posts_per_page' => -1,
  'meta_query' => [
    'relation' => 'OR',
    [
      'key' => 'date',
      'value' => $today,
      'compare' => '>=',
    ],
    [
      'key' => 'type_of_event',
      'value' => 'series',
      'compare' => '=',
    ],
    [
      'relation' => 'AND',
      [
        'key' => 'type_of_event',
        'value' => 'recurring',
        'compare' => '=',
      ],
      [
        'relation' => 'OR',
        [
          'key' => 'start_date',
          'value' => $today,
          'type' => 'DATE',
          'compare' => '<=',
        ],
        [
          'key' => 'end_date',
          'value' => $today,
          'type' => 'DATE',
          'compare' => '>=',
        ],
      ],
    ],
  ],
  'orderby' => 'date',
  'order' => 'ASC',
]);

$sortedPosts = [];
foreach ($posts as $post) {
  setup_postdata($post);
  $postId = get_the_ID();

  $activeDays = get_field('day', $postId);
  $typeOfEvent = get_field('type_of_event');

  $newPost = [
    'ID' => $postId,
    'time_start' => get_field('time_start', $postId),
    'day_str' => bluebox_repeat_event_get_day_string($activeDays),
    'event_type' => $typeOfEvent,
  ];

  $date = get_field('date', $postId);

  if ($typeOfEvent === 'recurring') {
    $eventStartDate = get_field('start_date', $postId);
    $eventStartDate = date('d/m/Y', strtotime('-1 day', DateTime::createFromFormat('d/m/Y', $eventStartDate)->getTimestamp()));
    $eventEndDate = get_field('end_date', $postId);
    $startDate = DateTime::createFromFormat('d/m/Y', $eventStartDate);
    $endDate = DateTime::createFromFormat('d/m/Y', $eventEndDate);

    //Loop through start_date and end_dates, incrementing by 1 week
    $interval = DateInterval::createFromDateString('1 week');
    $period = new DatePeriod($startDate, $interval, $endDate);
    foreach ($period as $dt) {
      $dateInLoop = $dt->format('Y-m-d');
      //Add new item for each day of the week:
      foreach ($activeDays as $key => $value) {
        $dateCopy = new DateTime($dateInLoop);
        $dateCopy->modify('next ' . $value);
        $date = $dateCopy->format('d/m/Y');

        if ($dateCopy->getTimestamp() < $endDate->getTimestamp() && $dateCopy->getTimestamp() >= strtotime($today)) {
          $dayToAdd = $newPost;
          $dayToAdd['date'] = $date;
          $sortedPosts[] = $dayToAdd;
        }
      }
    }
  } elseif ($typeOfEvent === 'series') {
    $seriesDates = get_field('dates_of_events', $postId);
    foreach ($seriesDates as $date) {
      if (DateTime::createFromFormat('d/m/Y', $date['date'])->getTimestamp() >= strtotime($today)) {
        $dayToAdd = $newPost;
        $dayToAdd['date'] = $date['date'];
        $sortedPosts[] = $dayToAdd;
      }
    }
  } else {
    $newPost['date'] = $date;
    $sortedPosts[] = $newPost;
  }
}

//Sort the dates based on date and start time:
usort($sortedPosts, function ($a, $b) {
  $dateA = DateTime::createFromFormat('d/m/Y g:i a', $a['date'] . $a['time_start']);
  $dateB = DateTime::createFromFormat('d/m/Y g:i a', $b['date'] . $b['time_start']);
  return $dateA >= $dateB;
});
?>
<div class="bluebox-repeat-container">
  <?php foreach ($sortedPosts as $sortedPost) {

    $dateToShow = $sortedPost['date'];
    $postId = $sortedPost['ID'];
    $post = get_post($postId);
    setup_postdata($post);
    ?>
    <a href="<?php the_permalink(); ?>">
      <div class="bluebox-repeat-event-section">
        <h3><?php the_title(); ?></h3>
        <?php if ($sortedPost['event_type'] === 'recurring') { ?>
              <p>Days: <?php echo $sortedPost['day_str']; ?></p>
            <?php } ?>
        <p>Date: <?php echo date('jS F Y', DateTime::createFromFormat('d/m/Y', $dateToShow)->getTimestamp()); ?></p>
        <p>Time: 
        <?php echo get_field('time_start', $postId); ?>
            - 
        <?php echo get_field('time_end', $postId); ?></p>
      </div>
    </a>
    <?php
  } ?>
</div>

<?php get_footer();
