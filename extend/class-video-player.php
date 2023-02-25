<?php

// Video field

class WPForms_Field_Video_Player extends WPForms_Field {

	public function init() {

		// Define field type information.
		$this->name  = 'Video Player';
		$this->type  = 'video-player';
		$this->group = 'ziggeo';
		$this->icon  = 'fa-play';
		$this->order = 2;

		// Define additional field properties.
		add_filter( 'wpforms_field_properties_video-player', array( $this, 'field_properties' ), 5, 3 );
		//Add the button into the proper section
		add_filter( 'wpforms_builder_fields_buttons', array( $this, 'field_button' ), 20 );
	}

	////////////////////////////////
	// **** **** PUBLIC **** **** //
	////////////////////////////////

	// Define additional field properties
	public function field_properties( $properties, $field, $form_data ) {
		//$field['video_token'];
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
		?>
		<div id="ziggeowpforms-videoplayer-<?php echo $field['id']; ?>" class="ziggeowpforms_placeholder"></div>
		<script>
			window.addEventListener('load', function() {
				ziggeowpformsCreateIframeEmbedding('ziggeowpforms-videoplayer-<?php echo $field['id']; ?>', 'ziggeoplayer',
					'<?php echo ziggeowpforms_get_player_code($field); ?>'
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

			// Video Token
			ziggeowpforms_create_builder_option_field($field['id'], 'videotoken', 'Video Token', [
				'html_type' 	=> 'input',
				'type'			=> 'text',
				'class'			=> '',
				'name'			=> 'video_token',
				'value'			=> isset($field['video_token']) ? $field['video_token'] : '',
				'placeholder'	=> 'Enter video token for playback'
			]);


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

			// Player theme
			ziggeowpforms_create_builder_option_field($field['id'], 'theme', 'Player Theme', [
				'html_type' 	=> 'select',
				'class'			=> 'ziggeowpforms-player-option',
				'name'			=> 'theme',
				'value'			=> isset($field['theme']) ? $field['theme'] : '',
				'placeholder'	=> 'Select the theme',
				'options'		=> array('Default', 'Modern', 'Cube', 'Space', 'Minimalist', 'Elevate', 'Theatre')
			]);

			// Player theme color
			ziggeowpforms_create_builder_option_field($field['id'], 'themecolor', 'Player Theme Color', [
				'html_type' 	=> 'select',
				'class'			=> 'ziggeowpforms-player-option',
				'name'			=> 'theme_color',
				'value'			=> isset($field['theme_color']) ? $field['theme_color'] : '',
				'placeholder'	=> 'Select the theme color',
				'options'		=> array('Blue', 'Green', 'Red')
			]);

			// Player width
			if(!isset($field['width']) || $field['width'] === '') {
				$field['width'] = '100%';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'width', 'Player Width', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'text',
				'name'			=> 'width',
				'value'			=> isset($field['width']) ? $field['width'] : '100%',
				'placeholder'	=> 'Set player width on your form'
			]);

			// Player height
			if(!isset($field['height']) || $field['height'] === '') {
				$field['height'] = '100%';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'height', 'Player Height', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'text',
				'name'			=> 'height',
				'value'			=> isset($field['height']) ? $field['height'] : null,
				'placeholder'	=> 'Set player height on your form'
			]);

			//Do we want to make a popup?
			if(!isset($field['popup'])) {
				$field['popup'] = false;
			}
			else {
				$field['popup'] = true;
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'popup', 'Player as a popup?', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'checkbox',
				'name'			=> 'popup',
				'value'			=> $field['popup']
			]);

			// Popup width
			if(!isset($field['popup_width']) || $field['popup_width'] === '') {
				$field['popup_width'] = '640';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'popup_width', 'Player Popup Width', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'number',
				'name'			=> 'popup_width',
				'value'			=> isset($field['popup_width']) ? $field['popup_width'] : '100%',
				'placeholder'	=> 'Set player popup width on your form'
			]);

			// Popup height
			if(!isset($field['popup_height']) || $field['popup_height'] === '') {
				$field['popup_height'] = '480';
			}

			ziggeowpforms_create_builder_option_field($field['id'], 'popup_height', 'Player Popup Height', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'number',
				'name'			=> 'popup_height',
				'value'			=> isset($field['popup_height']) ? $field['popup_height'] : '',
				'placeholder'	=> 'Set player popup height on your form'
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

			// Effect profile
			ziggeowpforms_create_builder_option_field($field['id'], 'effect_profiles', 'Use specific effect stream', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'text',
				'name'			=> 'effect_profiles',
				'value'			=> isset($field['effect_profiles']) ? $field['effect_profiles'] : '',
				'placeholder'	=> 'Effect Profile Token'
			]);

			// Video Profile
			ziggeowpforms_create_builder_option_field($field['id'], 'video_profile', 'Use specific video profile stream', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'text',
				'name'			=> 'video_profile',
				'value'			=> isset($field['video_profile']) ? $field['video_profile'] : '',
				'placeholder'	=> 'Video Profile Token'
			]);

			// Client Auth
			ziggeowpforms_create_builder_option_field($field['id'], 'client_auth', 'Use client auth token', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'text',
				'name'			=> 'client_auth',
				'value'			=> isset($field['client_auth']) ? $field['client_auth'] : '',
				'placeholder'	=> 'Add Client Auth token'
			]);

			// Server Auth
			ziggeowpforms_create_builder_option_field($field['id'], 'server_auth', 'Use server auth token', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-player-option',
				'type'			=> 'text',
				'name'			=> 'server_auth',
				'value'			=> isset($field['server_auth']) ? $field['server_auth'] : '',
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
	}

	// Field preview inside the builder.
	public function field_preview( $field ) {

		// Define data.
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';

		// Label.
		$this->field_preview_option( 'label', $field );

		//Lets get and print the player code
		echo '<ziggeoplayer ' . ziggeowpforms_get_player_code($field) . '></ziggeoplayer>';

		// Description.
		$this->field_preview_option( 'description', $field );

		// Support for lazy load option
		if(!defined('ZIGGEO_FOUND')) {
			define('ZIGGEO_FOUND', true);
		}

		echo ziggeo_p_get_lazyload_activator();

		if(!defined('ZIGGEO_FOUND_POST')) {
			define('ZIGGEO_FOUND_POST', true);
		}
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

new WPForms_Field_Video_Player();
