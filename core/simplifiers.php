<?php

//A way to check if we already added this button. If we did, we do not add it again
function ziggeowpforms_is_field_present($fields, $group, $type) {
	if(isset($fields[$group], $fields[$group]['fields'])) {

		for($i = 0, $c = count($fields[$group]['fields']); $i < $c; $i++) {
			if($fields[$group]['fields'][$i]['type'] === $type) {
				return true;
			}
		}

	}

	return false;
}


//The function that will help us create the buttons in the form builder
function ziggeowpforms_create_builder_option_field($field_id, $field_class_mark, $label, $field_info) {
	?>
	<div class="wpforms-field-option-row wpforms-field-option-row-<?php echo $field_class_mark; ?>"
			id="wpforms-field-option-row-<?php echo $field_id; ?>-<?php echo $field_class_mark; ?>"
			data-field-id="<?php echo $field_id; ?>">
		<label for="wpforms-field-option-<?php echo $field_id; ?>-<?php echo $field_class_mark; ?>"><?php echo $label; ?></label>
		<?php

			// Being safe
			$field_info['placeholder'] = (isset($field_info['placeholder'])) ? $field_info['placeholder'] : '';
			$field_info['type'] = (isset($field_info['type'])) ? $field_info['type'] : '';
			$field_info['class'] = (isset($field_info['class'])) ? $field_info['class'] : '';
			$field_info['value'] = (isset($field_info['value'])) ? $field_info['value'] : '';
			$field_info['name'] = (isset($field_info['name'])) ? $field_info['name'] : '';


			if($field_info['html_type'] === 'input') {
				?>
				<input type="<?php echo $field_info['type']; ?>"
						class="<?php echo $field_info['class']; ?>"
						id="wpforms-field-option-<?php echo $field_id; ?>-<?php echo $field_class_mark; ?>"
						name="fields[<?php echo $field_id; ?>][<?php echo $field_info['name'] ?>]"
						placeholder="<?php echo $field_info['placeholder']; ?>"
						<?php
							if($field_info['type'] === 'checkbox') {
								if($field_info['value'] === true) {
									?>checked="checked"<?php
								}
							}
							else {
								?>value="<?php echo $field_info['value']; ?>"<?php
							}
						?>
						>
				<?php
			}
			elseif($field_info['html_type'] === 'select') {
				?>
				<select class="<?php echo $field_info['class']; ?>"
						id="wpforms-field-option-<?php echo $field_id; ?>-<?php echo $field_class_mark; ?>"
						name="fields[<?php echo $field_id; ?>][<?php echo $field_info['name'] ?>]"
						value="<?php echo $field_info['value']; ?>">
					<?php
						for($i = 0, $c = count($field_info['options']); $i < $c; $i++) {
							$t_value = str_replace(' ', '_', strtolower($field_info['options'][$i]));
							?>
								<option value="<?php echo $t_value; ?>"
									<?php
									if($field_info['value'] === $t_value) {
										echo 'selected="SELECTED"';
									}
									?>><?php
									echo $field_info['options'][$i];
								?></option>
							<?php
						}
					?>
				</select>
				<?php
			}
			elseif($field_info['html_type'] === 'textarea') {
				?>
				<textarea type="<?php echo $field_info['type']; ?>"
						class="<?php echo $field_info['class']; ?>"
						id="wpforms-field-option-<?php echo $field_id; ?>-<?php echo $field_class_mark; ?>"
						name="fields[<?php echo $field_id; ?>][<?php echo $field_info['name'] ?>]"
						placeholder="<?php echo $field_info['placeholder']; ?>"
						value="<?php echo $field_info['value']; ?>">
				</textarea>
				<?php
			}
		?>
	</div>
	<?php
}

//Create the section in the builder field properties
function ziggeowpforms_create_builder_option_section($section_name, $section_title, $field_id, $status = 'open') {

	if(!defined('ZIGGEO_FOUND')) { define('ZIGGEO_FOUND', true); }
	echo ziggeo_p_get_lazyload_activator();
	if(!defined('ZIGGEO_FOUND_POST')) { define('ZIGGEO_FOUND_POST', true); }

	if($status === 'open') {
		?>
			<div class="wpforms-field-option-group wpforms-field-option-group-<?php echo $section_name; ?> wpforms-hide" id="wpforms-field-option-<?php echo $section_name; ?>-<?php echo $field_id; ?>">
				<a href="#" class="wpforms-field-option-group-toggle"><?php echo $section_title; ?> <i class="fa fa-angle-right"></i></a>
				<div class="wpforms-field-option-group-inner">
		<?php
	}
	else {
		?>
				</div>
			</div>
		<?php
	}
}

//returns back the player code based on the field data
function ziggeowpforms_get_player_code($field) {
	$code = '';

	//if video token is present, lets add it
	if(isset($field['video_token'])) {
		$code .= ' ziggeo-video="' . $field['video_token'] . '" ';
	}

	//if theme is set
	if(isset($field['theme'])) {
		$code .= ' ziggeo-theme="' . $field['theme'] . '" ';
	}

	//if theme color is set
	if(isset($field['theme_color'])) {
		$code .= ' ziggeo-themecolor="' . $field['theme_color'] . '" ';
	}

	//if width is set
	if(isset($field['width'])) {
		$code .= ' ziggeo-width="' . $field['width'] . '" ';
	}

	//if height is set
	if(isset($field['height']) && $field['height'] !== '100%') {
		$code .= ' ziggeo-height="' . $field['height'] . '" ';
	}

	//if popup is set
	if(isset($field['popup'])) {
		$code .= ' ziggeo-popup="' . $field['popup'] . '" ';

		//if popup_width is set
		if(isset($field['popup_width'])) {
			$code .= ' ziggeo-popup-width="' . $field['popup_width'] . '" ';
		}

		//if popup_height is set
		if(isset($field['popup_height'])) {
			$code .= ' ziggeo-popup-height="' . $field['popup_height'] . '" ';
		}
	}

	//if effect profile is present
	if(isset($field['effect_profiles'])) {
		$code .= ' ziggeo-effect-profile="' . $field['effect_profiles'] . '" ';
	}

	//if video profile is present
	if(isset($field['video_profile'])) {
		$code .= ' ziggeo-video-profile="' . $field['video_profile'] . '" ';
	}

	//if client auth is present
	if(isset($field['client_auth'])) {
		$code .= ' ziggeo-client-auth="' . $field['client_auth'] . '" ';
	}

	//if client auth is present
	if(isset($field['server_auth'])) {
		$code .= ' ziggeo-server-auth="' . $field['server_auth'] . '" ';
	}

	//Lets return it
	return $code;
}

//function to return the recorder parameters code using provided field data
function ziggeowpforms_get_recorder_code($field) {
	$code = '';

	//if theme is set
	if(isset($field['theme'])) {
		$code .= ' ziggeo-theme="' . $field['theme'] . '" ';
	}

	//if theme color is set
	if(isset($field['theme_color'])) {
		$code .= ' ziggeo-themecolor="' . $field['theme_color'] . '" ';
	}

	//if width is set
	if(isset($field['width'])) {
		$code .= ' ziggeo-width="' . $field['width'] . '" ';
	}

	//if height is set
	if(isset($field['height']) && $field['height'] !== '100%') {
		$code .= ' ziggeo-height="' . $field['height'] . '" ';
	}

	//if popup is set
	if(isset($field['popup'])) {
		$code .= ' ziggeo-popup="' . $field['popup'] . '" ';

		//if popup_width is set
		if(isset($field['popup_width'])) {
			$code .= ' ziggeo-popup-width="' . $field['popup_width'] . '" ';
		}

		//if popup_height is set
		if(isset($field['popup_height'])) {
			$code .= ' ziggeo-popup-height="' . $field['popup_height'] . '" ';
		}
	}

	//if faceoutline is set
	if(isset($field['faceoutline'])) {
		$code .= ' ziggeo-faceoutline="true" ';
	}


	//if recording_width is set
	if(isset($field['recording_width'])) {
		$code .= ' ziggeo-recordingwidth="' . $field['recording_width'] . '" ';
	}

	//if recording_height is set
	if(isset($field['recording_height'])) {
		$code .= ' ziggeo-recordingheight="' . $field['recording_height'] . '" ';
	}

	//if timelimit is set
	if(isset($field['recording_time_max'])) {
		$code .= ' ziggeo-timelimit="' . $field['recording_time_max'] . '" ';
	}

	//if mintimelimit is set
	if(isset($field['recording_time_min'])) {
		$code .= ' ziggeo-mintimelimit="' . $field['recording_time_min'] . '" ';
	}

	//if countdown is set
	if(isset($field['recording_countdown'])) {
		$code .= ' ziggeo-countdown="' . $field['recording_countdown'] . '" ';
	}

	//if recordings (number of allowed recordings) is set
	if(isset($field['recording_amount'])) {
		$code .= ' ziggeo-recordings="' . $field['recording_amount'] . '" ';
	}

	//if effect profile is present
	if(isset($field['effect_profiles'])) {
		$code .= ' ziggeo-effect-profile="' . $field['effect_profiles'] . '" ';
	}

	//if video profile is present
	if(isset($field['video_profile'])) {
		$code .= ' ziggeo-video-profile="' . $field['video_profile'] . '" ';
	}

	//if meta profile is present
	if(isset($field['meta_profile'])) {
		$code .= ' ziggeo-meta-profile="' . $field['meta_profile'] . '" ';
	}

	//if client auth is present
	if(isset($field['client_auth'])) {
		$code .= ' ziggeo-client-auth="' . $field['client_auth'] . '" ';
	}

	//if client auth is present
	if(isset($field['server_auth'])) {
		$code .= ' ziggeo-server-auth="' . $field['server_auth'] . '" ';
	}

	//Lets return it
	return $code;
}

//function to return the videowall parameters code using provided field data
function ziggeowpforms_get_videowall_code($field) {
	$code = '';

	//Walls are processed on backend and entire code is placed on the front page, unlike the player and recorder which are processed by JavaScript.
	$wall = '[ziggeovideowall ';

	if(isset($field['design'])) {
		$wall .= 'wall_design="' . $field['design'] . '" ';
	}
	if(isset($field['videos_per_page']) && $field['videos_per_page'] !== '') {
		$wall .= 'videos_per_page="' . $field['videos_per_page'] . '" ';
	}
	if(isset($field['videos_to_show']) && $field['videos_to_show'] !== '') {
		$wall .= 'videos_to_show="' . $field['videos_to_show'] . '" ';
	}
	if(isset($field['message']) && $field['message'] !== '') {
		$wall .= 'message="' . $field['message'] . '" ';
	}
	if(isset($field['no_videos']) && $field['no_videos'] !== '') {
		$wall .= 'on_no_videos="' . $field['no_videos'] . '" ';
	}
	if(isset($field['show_videos']) && $field['show_videos'] !== '') {
		$wall .= 'show_videos="' . $field['show_videos'] . '" ';
	}
	if(isset($field['show']) && $field['show'] !== '') {
		$wall .=	'show="' . $field['show'] . '" ';
	}
	if(isset($field['autoplay']) && $field['autoplay'] !== '') {
		$wall .= 'autoplay="' . $field['autoplay'] . '" ';
	}
	if(isset($field['title']) && $field['title'] !== '') {
		$wall .= 'title="' . $field['title'] . '" ';
	}
	if(isset($field['videowidth']) && $field['videowidth'] !== '') {
		$wall .= 'video_width="' . $field['videowidth'] . '" ';
	}
	if(isset($field['videoheight']) && $field['videoheight'] !== '') {
		$wall .= 'video_height="' . $field['videoheight'] . '" ';
	}
	if(isset($field['template_name']) && $field['template_name'] !== '') {
		$wall .= 'template_name="' . $field['template_name'] . '" ';
	}

	$replace = array();
	$replace[] = array(
		'from'	=> '<',
		'to'	=> '&lt;'
	);
	$replace[] = array(
		'from'	=> '>',
		'to'	=> '&gt;'
	);

	$code = ziggeo_clean_text_values(ziggeo_line_min(videowallsz_content_parse_videowall($wall, false)), $replace);

	//Lets return it
	return $code;
}

//Checks if the current request is made within the form builder or outside of it
function ziggeowpforms_is_builder() {
	//Same way WPForms is doing checks
	// wpforms-lite/includes/admin/admin.php:273
	// wpforms-lite/includes/functions.php:1924
	if ( empty( $_REQUEST['page'] ) || strpos( $_REQUEST['page'], 'wpforms' ) === false ) {
		//Actual link is page=wpforms-builder so if there are any complains we can make this a bit more specific
		return false;
	}

	return true;
}