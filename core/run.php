<?php

//
//	This file represents the integration module for WPForms and Ziggeo
//

// Index
//	1. Hooks
//		1.1. ziggeo_list_integration
//		1.2. plugins_loaded
//	2. Functionality
//		2.1. ziggeowpforms_get_version()
//		2.2. ziggeowpforms_init()
//		2.3. ziggeowpforms_run()

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

//Show the entry in the integrations panel
add_filter('ziggeo_list_integration', function($integrations) {

	$current = array(
		//This section is related to the plugin that we are combining with the Ziggeo, not the plugin/module that does it
		'integration_title'		=> 'WPForms', //Name of the plugin
		'integration_origin'	=> 'https://wpforms.com', //Where you can download it from

		//This section is related to the plugin or module that is making the connection between Ziggeo and the other plugin.
		'title'					=> 'Ziggeo Video for WPForms', //the name of the module
		'author'				=> 'Ziggeo', //the name of the author
		'author_url'			=> 'https://ziggeo.com/', //URL for author website
		'message'				=> 'Add video to form builder and your forms', //Any sort of message to show to customers
		'status'				=> true, //Is it turned on or off?
		'slug'					=> 'ziggeo-video-for-wpforms', //slug of the module
		//URL to image (not path). Can be of the original plugin, or the bridge
		'logo'					=> ZIGGEOWPFORMS_ROOT_URL . 'assets/images/logo.png',
		'version'				=> ZIGGEOWPFORMS_ROOT_PATH
	);

	//Check current Ziggeo version
	if(ziggeowpforms_run() === true) {
		$current['status'] = true;
	}
	else {
		$current['status'] = false;
	}

	$integrations[] = $current;

	return $integrations;
});

add_action('plugins_loaded', function() {
	ziggeowpforms_run();
});

//Checks if the WPForms exists and returns the version of it
function ziggeowpforms_get_version() {

	if(!defined( 'WPFORMS_VERSION' ) ) {
		return 0;
	}

	return WPFORMS_VERSION;
}

//Include all of the needed plugin files
function ziggeowpforms_include_plugin_files() {

	//Add the URL to jQuery
	add_action('ziggeo_add_to_ziggeowp_object', function() {
		?>
		url_jquery: "<?php echo includes_url() . 'js/jquery/jquery.js'; ?>",
		<?php
	});

	//Include the files only if we are running this plugin
	include_once(ZIGGEOWPFORMS_ROOT_PATH . 'core/simplifiers.php');
	include_once(ZIGGEOWPFORMS_ROOT_PATH . 'core/assets.php');

	//Fields specific
	require_once(ZIGGEOWPFORMS_ROOT_PATH . 'extend/class-video-recorder.php');
	require_once(ZIGGEOWPFORMS_ROOT_PATH . 'extend/class-video-player.php');
	require_once(ZIGGEOWPFORMS_ROOT_PATH . 'extend/class-video-template.php');

	//Check if there is VideoWalls plugin present or not and include additional field(s) if so
	if(defined('VIDEOWALLSZ_VERSION')) {
		require_once(ZIGGEOWPFORMS_ROOT_PATH . 'extend/class-video-wall.php');
	}
}

//We add all of the hooks we need
function ziggeowpforms_init() {

	//Lets include all of the files we need
	ziggeowpforms_include_plugin_files();

	add_filter( 'wpforms_builder_fields_buttons', function($fields) {

		$tmp = array(
			'ziggeo' => array(
			'group_name'	=> 'Ziggeo Fields',
			'fields'		=> array()
			)
		);

		$fields = array_slice($fields, 0, 1, true) + $tmp + array_slice($fields, 1, count($fields)-1, true);

		return $fields;
	}, 8);
}

//Function that we use to run the module 
function ziggeowpforms_run() {

	//Needed during activation of the plugin
	if(!function_exists('ziggeo_get_version')) {
		return false;
	}

	//Check current Ziggeo version
	if( version_compare(ziggeo_get_version(), '2.0') >= 0 &&
		//check the WPForms version
		version_compare(ziggeowpforms_get_version(), '1.5.7') >= 0) {

		if(ziggeo_integration_is_enabled('ziggeo-video-for-wpforms')) {
			ziggeowpforms_init();
			return true;
		}
	}

	return false;
}


?>