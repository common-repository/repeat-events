<?php

/**
 * Plugin Name: Repeat Events
 * Plugin URI:
 * Version:           1.0.0
 * Description: A simple repeating events listing plugin to create SEO optimised events.
 * Author:            Bluebox Digital
 * Author URI:        https://blueboxdigital.co.uk
 *
 */
define('BLUEBOX_REPEAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BLUEBOX_REPEAT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BLUEBOX_REPEAT_VERSION', '1.0.0');

require_once 'src/acf/acf.php';
require_once 'src/inc/helper.php';
require_once 'src/functions.php';
