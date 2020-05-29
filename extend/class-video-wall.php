<?php

// Video field

class WPForms_Field_Video_Wall extends WPForms_Field {

	public function init() {

		// Define field type information.
		$this->name  = 'Video Wall';
		$this->type  = 'video-wall';
		$this->group = 'ziggeo';
		$this->icon  = 'fa-caret-square-o-right';
		$this->order = 4;

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

		?>
		<?php
			// Primary field.
			// This way we can save the code we got back into a field and make it work with all the usual form conditions while our embedding field is shown andn not saved
			printf(
				'<input type="hidden" %s %s>',
				wpforms_html_attributes( $primary['id'], $primary['class'], $primary['data'], $primary['attr'] ),
				$primary['required']
			);

		?>
		<div id="ziggeowpforms-videowall-<?php echo $field['id']; ?>" class="ziggeowpforms_placeholder"></div>
		<script>
			window.addEventListener('load', function() {
				createIframeEmbedding('ziggeowpforms-videowall-<?php echo $field['id']; ?>', 'ziggeovideowall',
										'<?php echo ziggeowpforms_get_videowall_code($field); ?>'
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

			//video wall title
			ziggeowpforms_create_builder_option_field($field['id'], 'title', 'VideoWall title', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'title',
				'value'			=> $field['title'],
				'placeholder'	=> 'Select the title',
				'type'			=> 'text'
			]);

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

			if(!isset($field['design'])) {
				$field['design'] = 'slide_wall';
			}

			// VideoWall design
			ziggeowpforms_create_builder_option_field($field['id'], 'design', 'VideoWall Design', [
				'html_type' 	=> 'select',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'design',
				'value'			=> $field['design'],
				'placeholder'	=> 'Select the design',
				'options'		=> array('Default', 'Show Pages', 'Slide Wall', 'Mosaic Grid', 'Chessboard Grid')
			]);

			// Videos width
			ziggeowpforms_create_builder_option_field($field['id'], 'videowidth', 'VideoWall videos width', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'videowidth',
				'value'			=> $field['videowidth'],
				'placeholder'	=> 'Leave empty for auto',
				'type'			=> 'text'
			]);

			// Videos height
			ziggeowpforms_create_builder_option_field($field['id'], 'videoheight', 'VideoWall videos height', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'videoheight',
				'value'			=> $field['videoheight'],
				'placeholder'	=> 'Leave empty for auto',
				'type'			=> 'text'
			]);

			// Videos per page
			ziggeowpforms_create_builder_option_field($field['id'], 'videos_per_page', 'Number of videos per page', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'videos_per_page',
				'value'			=> $field['videos_per_page'],
				'placeholder'	=> 'Leave empty for auto',
				'type'			=> 'text'
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

			if(!isset($field['autoplay']) || $field['autoplay'] === '') {
				$field['autoplay'] = false;
			}

			// Should videos be autoplayed
			ziggeowpforms_create_builder_option_field($field['id'], 'autoplay', 'Autoplay videos as wall is shown', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'autoplay',
				'value'			=> $field['autoplay'],
				'type'			=> 'checkbox'
			]);

			if(!isset($field['show']) || $field['show'] === '') {
				$field['show'] = false;
			}

			// Should the videos be shown right of or hidden until submission?
			ziggeowpforms_create_builder_option_field($field['id'], 'show', 'Show wall on page load', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'show',
				'value'			=> $field['show'],
				'type'			=> 'checkbox'
			]);

			// What happens when there are no videos?
			ziggeowpforms_create_builder_option_field($field['id'], 'no_videos', 'What should happen if there are no videos?', [
				'html_type' 	=> 'select',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'no_videos',
				'value'			=> $field['no_videos'],
				'options'		=> ['ShowMessage', "ShowTeamplate", "HideWall"]
			]);

			// What message should we show?
			ziggeowpforms_create_builder_option_field($field['id'], 'message', 'What message should be shown?', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'message',
				'value'			=> $field['message'],
				'type'			=> 'text'
			]);

			// What template should be used?
			ziggeowpforms_create_builder_option_field($field['id'], 'template_name', 'Write template name to use', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'template_name',
				'value'			=> $field['template_name'],
				'type'			=> 'text'
			]);

			// What type of videos to look for?
			ziggeowpforms_create_builder_option_field($field['id'], 'show_videos', 'Type of videos to show', [
				'html_type' 	=> 'select',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'show_videos',
				'value'			=> $field['show_videos'],
				'options'		=> array('Default', 'All', 'Approved', 'Rejected', 'Pending')
			]);

			// What videos to search for?
			ziggeowpforms_create_builder_option_field($field['id'], 'videos_to_show', 'Type of videos to show', [
				'html_type' 	=> 'input',
				'class'			=> 'ziggeowpforms-videowall-option',
				'name'			=> 'template_name',
				'value'			=> $field['videos_to_show'],
				'type'			=> 'text',
				'placeholder'	=> 'POST ID or other video tag'
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

		?>
		<div class="ziggeowpforms-videowall">
			<p>Videowalls are not rendered within the builder.</p>
		</div>
		<?php

		// Description.
		$this->field_preview_option( 'description', $field );
	}
}

new WPForms_Field_Video_Wall();
