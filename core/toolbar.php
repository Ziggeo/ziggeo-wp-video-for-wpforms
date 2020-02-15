<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();


	//Adds a small public facing toolbar to make it easy to record and reply back with video on forum
	function ziggeowpforms_smalltoolbbar() {

		$options = get_option('ziggeowpforms');

		if(isset($options['public_recorder']) && (int)$options['public_recorder'] === 1) {
			//Add video recording
			add_action('ziggeowpforms_toolbar_buttons', function() {
				echo ziggeo_create_toolbar_button('ziggeobbbpress-record', 'Record Video', 'video-alt');

				?>
				<script type="text/javascript">
					jQuery('#ziggeobbbpress-record').on('click', function(event) {
						//create the
						ziggeowpformsStartRecording('video');
						event.preventDefault();
					});
				</script>
				<?php
				//@add the JS functionality for the button
			});
		}
		
		//Add audio only recording
		//Add image uploading
		//Add screen recording
		if(isset($options['public_screen']) && (int)$options['public_screen'] === 1) {
			add_action('ziggeowpforms_toolbar_buttons', function() {
				echo ziggeo_create_toolbar_button('ziggeobbbpress-screen', 'Record Screen', 'desktop');
				?>
				<script type="text/javascript">
					jQuery('#ziggeobbbpress-screen').on('click', function(event) {
						//create the
						ziggeowpformsStartRecording('screen');
						event.preventDefault();
					});
				</script>
				<?php
				//@add the JS functionality for the button
			});
		}

		?>
		<div id="ziggeowpforms-toolbar">
			<?php echo do_action('ziggeowpforms_toolbar_buttons'); ?>
		</div>
		<?php
	}

?>