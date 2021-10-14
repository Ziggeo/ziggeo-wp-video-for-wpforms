<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

function ziggeowpforms_global() {

	add_action('wp_print_footer_scripts', function() {
		//Output the function that provides us with the code we can use for iframes
		?>
		<script>
			function ziggeowpformsGetIframeHeaderCode() {
				<?php
					$code = ziggeo_p_assets_get_raw();
					$code[] = array(
						'css'	=> ZIGGEOWPFORMS_ROOT_URL . 'assets/css/styles.css'
					);
				?>
				return '' +
				<?php
					for($i = 0, $c = count($code); $i < $c; $i++) {
						if(isset($code[$i]['js'])) {
							?>
							'<' + 'script src="<?php echo $code[$i]['js']; ?>"></' + 'script' + '>' +
							<?php
						}
						if(isset($code[$i]['css'])) {
							?>
							'<' + 'link rel="stylesheet" href="<?php echo $code[$i]['css'];?>" media="all" />' +
							<?php
						}
					}
					?>
					'';
			}
		</script>
		<?php
	});

	//local assets
	wp_register_style('ziggeowpforms-css', ZIGGEOWPFORMS_ROOT_URL . 'assets/css/styles.css', array());
	wp_enqueue_style('ziggeowpforms-css');

	wp_register_script('ziggeowpforms-js', ZIGGEOWPFORMS_ROOT_URL . 'assets/js/codes.js', array());
	wp_enqueue_script('ziggeowpforms-js');
}

//Load the admin scripts (and local)
function ziggeowpforms_admin() {

	ziggeowpforms_global();

	if(version_compare(ziggeowpforms_get_version(), '1.6.8.1') >= 0) {
		wp_register_style('ziggeowpforms-admin-css', ZIGGEOWPFORMS_ROOT_URL . 'assets/css/admin-styles.css', array());
		wp_enqueue_style('ziggeowpforms-admin-css');
	}

	wp_register_script('ziggeowpforms-adminjs', ZIGGEOWPFORMS_ROOT_URL . 'assets/js/admin-codes.js', array());
	wp_enqueue_script('ziggeowpforms-adminjs');
}

add_action('wp_enqueue_scripts', "ziggeowpforms_global");
add_action('admin_enqueue_scripts', "ziggeowpforms_admin");


?>