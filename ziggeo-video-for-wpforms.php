<?php
/*
Plugin Name: Ziggeo Video for WPForms
Plugin URI: https://ziggeo.com/integrations/wordpress
Description: Add the Powerful Ziggeo video service platform to your WPForms form builder and forms
Author: Ziggeo
Version: 1.3
Author URI: https://ziggeo.com
*/

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();


//rooth path
define('ZIGGEOWPFORMS_ROOT_PATH', plugin_dir_path(__FILE__) );

//Setting up the URL so that we can get/built on it later on from the plugin root
define('ZIGGEOWPFORMS_ROOT_URL', plugins_url('', __FILE__) . '/');

//plugin version - this way other plugins can get it as well and we will be updating this file for each version change as is
define('ZIGGEOWPFORMS_VERSION', '1.3');

//Include files
include_once(ZIGGEOWPFORMS_ROOT_PATH . 'core/run.php');

?>