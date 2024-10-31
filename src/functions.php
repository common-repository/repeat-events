<?php

add_action('init', function () {
  if (post_type_exists('event') || post_type_exists('events')) {
    $queryVar = 'repeat-event';
    $rewrite = 'repeat-events';
  } else {
    $queryVar = 'event';
    $rewrite = 'events';
  }
  register_post_type('bluebox_repeat_event', [
    'labels' => [
      'name' => __('Events', 'textdomain'),
      'singular_name' => __('Event', 'textdomain'),
    ],
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-calendar-alt',
    'position' => 10,
    'rewrite' => ['slug' => $rewrite, 'with_front' => true],
    'query_var' => $queryVar,
  ]);
});

add_filter('template_include', function ($template) {
  if (is_post_type_archive('bluebox_repeat_event')) {
    $theme_files = ['archive-bluebox_repeat_event.php', 'repeat-events/archive-bluebox_repeat_event.php'];
    $exists_in_theme = locate_template($theme_files, false);
    if ($exists_in_theme != '') {
      return $exists_in_theme;
    } else {
      return BLUEBOX_REPEAT_PLUGIN_DIR . '/src/archive-bluebox_repeat_event.php';
    }
  }
  if (is_singular('bluebox_repeat_event')) {
    $theme_files = ['single-bluebox_repeat_event.php', 'repeat-events/single-bluebox_repeat_event.php'];
    $exists_in_theme = locate_template($theme_files, false);
    if ($exists_in_theme != '') {
      return $exists_in_theme;
    } else {
      return BLUEBOX_REPEAT_PLUGIN_DIR . '/src/single-bluebox_repeat_event.php';
    }
  }
  return $template;
});

add_action('wp_enqueue_scripts', function () {
  if (is_singular('bluebox_repeat_event') || is_post_type_archive('bluebox_repeat_event')) {
    wp_enqueue_style('bluebox_repeat_event_single_css', BLUEBOX_REPEAT_PLUGIN_URL . 'src/css/style.css', false, BLUEBOX_REPEAT_VERSION);
  }
});
