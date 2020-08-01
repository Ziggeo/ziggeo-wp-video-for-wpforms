<?php

// Video field

class WPForms_Field_Video_Recorder extends WPForms_Field {

	public function init() {

		// Define field type information.
		$this->name  = 'Video Recorder';
		$this->type  = 'video-recorder';
		$this->group = 'ziggeo';
		$this->icon  = 'fa-video-camera';
		$this->order = 1;

		// Define additional field properties.
		add_filter( 'wpforms_field_properties_video-recorder', array( $this, 'field_properties' ), 5, 3 );
		//Add the button into the proper section
		add_filter( 'wpforms_builder_fields_buttons', array( $this, 'field_button' ), 20 );
	}

	////////////////////////////////
	// **** **** PUBLIC **** **** //
	////////////////////////////////

	// Define additional field properties
	public function field_properties( $properties, $field, $form_data ) {

		return $properties;
	}

	// Field display on the form front-end.
	public function field_display( $field, $deprecated, $form_data ) {

		// Define data.
		$primary = $field['properties']['inputs']['primary'];

		$primary['data']['form-id']  = $form_data['id'];
		$primary['data']['field-id'] = $field['id'];

		// Primary field.
		// This way we can save the code we got back into a field and make it work with all the usual form conditions while our embedding field is shown andn not saved
		printf(
			'<input type="hidden" %s %s>',
			wpforms_html_attributes( $primary['id'], $primary['class'], $primary['data'], $primary['attr'] ),
			$primary['required']
		);

		//Custom tags
		$wpf_tags = 'data-wpf-custom-tags=""';

		if(isset($field['wpf_custom_tags'])) {
			$wpf_tags = 'data-wpf-custom-tags="' . $field['wpf_custom_tags'] . '"';
		}

		?>
		<div id="ziggeowpforms-videorecorder-<?php echo $field['id']; ?>" class="ziggeowpforms_placeholder"></div>
		<script>
			window.addEventListener('load', function() {
				ziggeowpformsCreateIframeEmbedding('ziggeowpforms-videorecorder-<?php echo $field['id']; ?>', 'ziggeorecorder',
					'<?php echo $wpf_tags . ' ' . ziggeowpforms_get_recorder_code($field); ?>'
				);
			});
		</script>
		<?php
	}

	///////////////////////////////
	// **** **** ADMIN **** **** //
	///////////////////////////////

	//Adds the button in the editor
	public function field_button( $fields ) {

		//check if it is added already
		if(!ziggeowpforms_is_field_present($fields, $this->group, $this->type)) {
			// Add field information to fields array.
			$fields[ $this->group ]['fields'][] = array(
				'order' => $this->order,
				'name'  => $this->name,
				'type'  => $this->type,
				'icon'  => $this->icon
			);
		}

		// Wipe hands clean.
		return $fields;
	}

	// Field options panel inside the builder
	public function field_options( $field ) {
		// Basic field options.
		//wpforms/includes/fields/class-base.php

		// Options open markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'open',
			)
		);
			// Label.
			$this->field_option( 'label', $field );

			// Hide label.
			$this->field_option( 'label_hide', $field );

			// Description.
			$this->field_option( 'description', $field );

			// Required toggle.
			// If played to end, it will be seen as done
			$this->field_option( 'required', $field );

		// Options close markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'close',
			)
		);

		// Style related options

		ziggeowpforms_create_builder_option_section('style', 'Style Options', $field['id'], 'open');

			// Recorder theme
			ziggeowpforms_create_builder_option_field($field['id'], 'theme', 'Recorder Theme', [
				'html_type' 	=> 'select',
				'class'			=> 'ziggeowpforms-recorder-option',
				'name'			=> 'theme',
				'value'			=> $field['theme'],
				'placeholder'	=> 'Select the theme',
				'options'		=> array('Default', 'Modern', 'Cube', 'Space', 'Minimalist', 'Elevate', 'Theatre')
			]);

			// Recorder theme color
			ziggeowpforms_create_builder_option_field($field['id'], 'themecolor', 'Recorder Theme Color', [
				'html_type' 	=> 'select',
				'class'			=> 'ziggeowpforms-recorder-option',
				'name'			=> 'theme_color',
				'value'			=> $field['theme_color'],
				'placeholder'	=> 'Select the theme color',
				'options'		=> array('Blue', 'Green', 'Red')
			]);

			// Recorder width
			if(!isset($field['width']) || $field['width'] === '') {
				$field['width'] = '100%';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'width', 'Recorder Width', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'width',
				'value'			=> $field['width'],
				'placeholder'	=> 'Set recorder width on your form'
			]);

			// Recorder height
			if(!isset($field['height']) || $field['height'] === '') {
				$field['height'] = '100%';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'height', 'Recorder Height', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'height',
				'value'			=> $field['height'],
				'placeholder'	=> 'Set recorder height on your form'
			]);

			//Do we want to make a popup?
			if(!isset($field['popup'])) {
				$field['popup'] = false;
			}
			else {
				$field['popup'] = true;
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'popup', 'Recorder as a popup?', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'checkbox',
				'name'			=> 'popup',
				'value'			=> $field['popup']
			]);

			// Popup width
			if(!isset($field['popup_width']) || $field['popup_width'] === '') {
				$field['popup_width'] = '640';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'popup_width', 'Recorder Popup Width', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'number',
				'name'			=> 'popup_width',
				'value'			=> $field['popup_width'],
				'placeholder'	=> 'Set recorder popup width on your form'
			]);

			// Popup height
			if(!isset($field['popup_height']) || $field['popup_height'] === '') {
				$field['popup_height'] = '480';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'popup_height', 'Recorder Popup Height', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'number',
				'name'			=> 'popup_height',
				'value'			=> $field['popup_height'],
				'placeholder'	=> 'Set recorder popup height on your form'
			]);

			//Do we want to show the face outline?
			if(!isset($field['faceoutline'])) {
				$field['faceoutline'] = false;
			}
			else {
				$field['faceoutline'] = true;
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'faceoutline', 'Use faceoutline during recording?', [
				'html_type' 	=> 'input',
				'class'			=> '',
				'type'			=> 'checkbox',
				'name'			=> 'faceoutline',
				'value'			=> $field['faceoutline']
			]);

		ziggeowpforms_create_builder_option_section('style', 'Style Options', $field['id'], 'close');


		// Advanced field options

		// Options open markup.
		$this->field_option(
			'advanced-options',
			$field,
			array(
				'markup' => 'open',
			)
		);

			// Recording width
			if(!isset($field['recording_width']) || $field['recording_width'] === '') {
				$field['recording_width'] = '640';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'recording_width', 'Recording Width', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'number',
				'name'			=> 'recording_width',
				'value'			=> $field['recording_width'],
				'placeholder'	=> 'Set recording width'
			]);

			// Recording height
			if(!isset($field['recording_height']) || $field['recording_height'] === '') {
				$field['recording_height'] = '480';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'recording_height', 'Recording Height', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'number',
				'name'			=> 'recording_height',
				'value'			=> $field['recording_height'],
				'placeholder'	=> 'Set recording height'
			]);

			// Recording timelimit
			if(!isset($field['recording_time_max']) || $field['recording_time_max'] === '') {
				$field['recording_time_max'] = '0';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'recording_time_max', 'Max time of recording', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'number',
				'name'			=> 'recording_time_max',
				'value'			=> $field['recording_time_max'],
				'placeholder'	=> '0 = unlimited'
			]);

			// Recording mintimelimit
			if(!isset($field['recording_time_min']) || $field['recording_time_min'] === '') {
				$field['recording_time_min'] = '0';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'recording_time_min', 'Min time of recording', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'number',
				'name'			=> 'recording_time_min',
				'value'			=> $field['recording_time_min'],
				'placeholder'	=> '0 = unlimited'
			]);

			// Recording countdown
			if(!isset($field['recording_countdown']) || $field['recording_countdown'] === '') {
				$field['recording_countdown'] = '3';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'recording_countdown', 'Seconds before recording starts', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'number',
				'name'			=> 'recording_countdown',
				'value'			=> $field['recording_countdown'],
				'placeholder'	=> '3'
			]);

			// Number of recordings allowed
			if(!isset($field['recording_amount']) || $field['recording_amount'] === '') {
				$field['recording_amount'] = '0';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'recording_amount', 'Number of recordings allowed', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'number',
				'name'			=> 'recording_amount',
				'value'			=> $field['recording_amount'],
				'placeholder'	=> '0 = unlimited'
			]);

			// Effect profile
			ziggeowpforms_create_builder_option_field($field['id'], 'effect_profiles', 'Apply effect profile', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'effect_profiles',
				'value'			=> $field['effect_profiles'],
				'placeholder'	=> 'Effect Profile Token'
			]);

			// Video profiles
			ziggeowpforms_create_builder_option_field($field['id'], 'video_profile', 'Apply video profile', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'video_profile',
				'value'			=> $field['video_profile'],
				'placeholder'	=> 'Video Profile Token'
			]);

			// Meta profiles
			ziggeowpforms_create_builder_option_field($field['id'], 'meta_profile', 'Apply meta profile', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'meta_profile',
				'value'			=> $field['meta_profile'],
				'placeholder'	=> 'Meta Profile Token'
			]);

			// Client auth
			ziggeowpforms_create_builder_option_field($field['id'], 'client_auth', 'Use client auth token', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'client_auth',
				'value'			=> $field['client_auth'],
				'placeholder'	=> 'Add Client Auth token'
			]);

			// Server auth
			ziggeowpforms_create_builder_option_field($field['id'], 'server_auth', 'Use server auth token', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'server_auth',
				'value'			=> $field['server_auth'],
				'placeholder'	=> 'Add Server Auth token'
			]);

		// Options close markup.
		$this->field_option(
			'advanced-options',
			$field,
			array(
				'markup' => 'close',
			)
		);


		ziggeowpforms_create_builder_option_section('style', 'Data Options', $field['id'], 'open');

			// Title
			ziggeowpforms_create_builder_option_field($field['id'], 'video_title', 'Video Title', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'video_title',
				'value'			=> $field['video_title'],
				'placeholder'	=> 'Video title'
			]);

			// Description
			ziggeowpforms_create_builder_option_field($field['id'], 'video_description', 'Video Description', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'video_description',
				'value'			=> $field['video_description'],
				'placeholder'	=> 'Video Description'
			]);

			// Tags
			ziggeowpforms_create_builder_option_field($field['id'], 'video_tags', 'Video Tags', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'video_tags',
				'value'			=> $field['video_tags'],
				'placeholder'	=> 'Video Tags'
			]);

			// JSON Data
			ziggeowpforms_create_builder_option_field($field['id'], 'custom_data', 'Custom (JSON format only) data', [
				'html_type' 	=> 'textarea',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'custom_data',
				'value'			=> $field['custom_data'],
				'placeholder'	=> 'Custom data'
			]);

			if(!isset($field['wpf_custom_tags'])) {
				$field['wpf_custom_tags'] = '';
			}

			// Custom Tags
			ziggeowpforms_create_builder_option_field($field['id'], 'wpf_custom_tags', 'Use custom tags based on fields on form', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-recorder-option',
				'type'			=> 'text',
				'name'			=> 'wpf_custom_tags',
				'value'			=> $field['wpf_custom_tags'],
				'placeholder'	=> 'Set custom tags'
			]);


		ziggeowpforms_create_builder_option_section('style', 'Data Options', $field['id'], 'close');

	}

	// Field preview inside the builder.
	public function field_preview( $field ) {

		// Define data.
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';

		// Label.
		$this->field_preview_option( 'label', $field );

		//Lets get and print the recorder code
		echo '<ziggeorecorder ' . ziggeowpforms_get_recorder_code($field) . ' ></ziggeorecorder>';

		// Description.
		$this->field_preview_option( 'description', $field );
	}

	// Formats and sanitizes field when submitted on public side
	public function format( $field_id, $field_submit, $form_data ) {

		$field = $form_data['fields'][ $field_id ];
		$name  = ! empty( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';

		// Sanitize.
		$value = sanitize_text_field( $field_submit );

		wpforms()->process->fields[ $field_id ] = array(
			'name'  => $name,
			'value' => $value,
			'id'    => absint( $field_id ),
			'type'  => $this->type,
		);
	}
}

new WPForms_Field_Video_Recorder();
