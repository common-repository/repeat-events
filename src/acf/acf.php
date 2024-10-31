<?php

include_once BLUEBOX_REPEAT_PLUGIN_DIR . '/includes/acf/acf.php';

add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url($url)
{
  return BLUEBOX_REPEAT_PLUGIN_URL . '/includes/acf/';
}

add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point($path)
{
  $path = BLUEBOX_REPEAT_PLUGIN_DIR . 'src/acf/groups';
  return $path;
}

add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point($paths)
{
  unset($paths[0]);
  $paths[] = BLUEBOX_REPEAT_PLUGIN_DIR . 'src/acf/groups';
  return $paths;
}
