<?php

add_action( 'acf/init', 'mu_hrcomp_fields_init' );

function mu_hrcomp_fields_init() {
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_61aa635a2daf9',
			'title' => 'Position',
			'fields' => array(
				array(
					'key' => 'field_61aa638f6e677',
					'label' => 'Upload PDF',
					'name' => 'position_upload_pdf',
					'type' => 'file',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_61aa63c9ad99a',
								'operator' => '!=empty',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => 'pdf',
				),
				array(
					'key' => 'field_61aa746d68d29',
					'label' => 'PDF URL',
					'name' => 'position_url_pdf',
					'type' => 'url',
					'instructions' => 'Only use this for previous entires. For new positions you should upload the PDF directly.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
				),
				array(
					'key' => 'field_61aa63c9ad99a',
					'label' => 'New Pay Grade',
					'name' => 'position_new_pay_grade',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						1 => '1',
						2 => '2',
						3 => '3',
						4 => '4',
						5 => '5',
						6 => '6',
						7 => '7',
						8 => '8',
						9 => '9',
					),
					'default_value' => false,
					'allow_null' => 1,
					'multiple' => 0,
					'ui' => 0,
					'return_format' => 'value',
					'ajax' => 0,
					'placeholder' => '',
				),
				array(
					'key' => 'field_61aa63d4d649a',
					'label' => 'Salary Minimum',
					'name' => 'position_salary_min',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_61aa63e02ca53',
					'label' => 'Salary Mid',
					'name' => 'position_salary_mid',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_61aa63e72cc47',
					'label' => 'Salary Max',
					'name' => 'position_salary_max',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'mu-position',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		));

		endif;

}
