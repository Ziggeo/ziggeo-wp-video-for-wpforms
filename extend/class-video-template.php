<?php

// Video field

class WPForms_Field_Video_Template extends WPForms_Field {

	public function init() {

		// Define field type information.
		$this->name  = 'Ziggeo Templates';
		$this->type  = 'video-template';
		$this->group = 'ziggeo';
		$this->icon  = 'fa-code';
		$this->order = 3;

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
		<div class="ziggeowpforms-templates"></div>
		<script>
			createIframeEmbedding('ziggeowpforms-templates', 'ziggeotemplate',
									'<?php echo ziggeo_p_content_parse_templates( '[ziggeo ' . ziggeo_p_template_params($field['template_name']), '[ziggeo ' . ziggeo_p_template_params($field['template_name']) ); ?>'
			);
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
		/*
		 * Basic field options.
		 */

		//owpforms/includes/fields/class-base.php

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
			$this->field_option( 'required', $field );

		// Options close markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'close',
			)
		);

		// Advanced field options

		// Options open markup.
		$this->field_option(
			'advanced-options',
			$field,
			array(
				'markup' => 'open',
			)
		);

		//Show the templates
		$list = ziggeo_p_templates_index();
		$templates = array();
		if($list) {
			foreach($list as $template_id => $template_code)
			{
				if($template_id !== '') {
					$templates[] = $template_id;
				}
			}
		}

		if(count($templates) == 0) {
			$templates[] = 'No Templates Found';
		}

		// Templates
		ziggeowpforms_create_builder_option_field($field['id'], 'template_name', 'Select Template', [
			'html_type' 	=> 'select',
			'class'			=> 'ziggeowpforms-template-option',
			'name'			=> 'template_name',
			'value'			=> $field['template_name'],
			'options'		=> $templates
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

new WPForms_Field_Video_Template();
