<?php

/**
 * Register new Customizer elements.
 *
 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
 */
add_action( 'customize_register', 'mai_register_customizer_content_archives_settings', 20 );
function mai_register_customizer_content_archives_settings( $wp_customize ) {

	/* ************ *
	 * Mai Settings *
	 * ************ */

	$section        = 'mai_content_archives';
	$settings_field = 'genesis-settings';
	$post_type      = 'post';

	// Section.
	$wp_customize->add_section(
		$section,
		array(
			'title'    => __( 'Mai Content Archives', 'mai-pro-engine' ),
			'priority' => '35',
		)
	);

	// Columns.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'columns' ),
		array(
			'default'           => $args['columns'],
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'columns',
		array(
			'label'       => __( 'Columns', 'mai-pro-engine' ),
			'description' => __( 'Display content in multiple columns.', 'mai-pro-engine' ),
			'section'     => $section,
			'settings'    => _mai_customizer_get_field_name( $settings_field, 'columns' ),
			'priority'    => 10,
			'type'        => 'select',
			'choices'     => array(
				'1' => __( 'None', 'mai-pro-engine' ),
				'2' => __( '2', 'mai-pro-engine' ),
				'3' => __( '3', 'mai-pro-engine' ),
				'4' => __( '4', 'mai-pro-engine' ),
				'6' => __( '6', 'mai-pro-engine' ),
			),
		)
	);

	// Content Type.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'content_archive' ),
		array(
			'default'           => $args['content_archive'],
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'content_archive',
		array(
			'label'    => __( 'Content', 'mai-pro-engine' ),
			'section'  => $section,
			'settings' => _mai_customizer_get_field_name( $settings_field, 'content_archive' ),
			'priority' => 10,
			'type'     => 'select',
			'choices'  => array(
				'none'     => __( 'No content', 'mai-pro-engine' ),
				'full'     => __( 'Entry content', 'genesis' ),
				'excerpts' => __( 'Entry excerpts', 'genesis' ),
			),
		)
	);

	// Content Limit.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'content_archive_limit' ),
		array(
			'default'           => $args['content_archive_limit'],
			'type'              => 'option',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'content_archive_limit',
		array(
			'label'           => __( 'Limit content to how many characters?', 'mai-pro-engine' ),
			'description'     => __( '(0 for no limit)', 'mai-pro-engine' ),
			'section'         => $section,
			'settings'        => _mai_customizer_get_field_name( $settings_field, 'content_archive_limit' ),
			'priority'        => 10,
			'type'            => 'number',
			'active_callback' => function() use ( $wp_customize, $settings_field ) {
				return ( 'none' != $wp_customize->get_setting( _mai_customizer_get_field_name( $settings_field, 'content_archive' ) )->value() );
			},
		)
	);

	// Featured Image - heading only.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'archive_featured_image_heading' ),
		array(
			'default' => '',
			'type'    => 'option',
		)
	);
	$wp_customize->add_control(
		new Mai_Customize_Control_Content( $wp_customize,
			'archive_featured_image_heading',
			array(
				'label'    => __( 'Featured Image', 'mai-pro-engine' ),
				'section'  => $section,
				'settings' => _mai_customizer_get_field_name( $settings_field, 'archive_featured_image_heading' ),
			)
		)
	);

	// Featured Image.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'content_archive_thumbnail' ),
		array(
			'default'           => _mai_customizer_sanitize_one_zero( $args['content_archive_thumbnail'] ),
			'type'              => 'option',
			'sanitize_callback' => '_mai_customizer_sanitize_one_zero',
		)
	);
	$wp_customize->add_control(
		'content_archive_thumbnail',
		array(
			'label'    => __( 'Display the featured image', 'genesis' ),
			'section'  => $section,
			'settings' => _mai_customizer_get_field_name( $settings_field, 'content_archive_thumbnail' ),
			'type'     => 'checkbox',
		)
	);

	// Image Location.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'image_location' ),
		array(
			'default'           => $args['image_location'],
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'image_location',
		array(
			'label'    => __( 'Image Location', 'genesis' ),
			'section'  => $section,
			'settings' => _mai_customizer_get_field_name( $settings_field, 'image_location' ),
			'priority' => 10,
			'type'     => 'select',
			'choices'  => array(
				'background'     => __( 'Background Image', 'mai-pro-engine' ),
				'before_entry'   => __( 'Before Entry', 'mai-pro-engine' ),
				'before_title'   => __( 'Before Title', 'mai-pro-engine' ),
				'after_title'    => __( 'After Title', 'mai-pro-engine' ),
				'before_content' => __( 'Before Content', 'mai-pro-engine' ),
			),
			'active_callback' => function() use ( $wp_customize, $settings_field ) {
				return (bool) $wp_customize->get_setting( _mai_customizer_get_field_name( $settings_field, 'content_archive_thumbnail' ) )->value();
			},
		)
	);

	// Image Size.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'image_size' ),
		array(
			'default'           => $args['image_size'],
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'image_size',
		array(
			'label'           => __( 'Image Size', 'genesis' ),
			'section'         => $section,
			'settings'        => _mai_customizer_get_field_name( $settings_field, 'image_size' ),
			'priority'        => 10,
			'type'            => 'select',
			'choices'         => _mai_customizer_get_image_sizes_config(),
			'active_callback' => function() use ( $wp_customize, $settings_field ) {
				return (bool) $wp_customize->get_setting( _mai_customizer_get_field_name( $settings_field, 'content_archive_thumbnail' ) )->value();
			},
		)
	);

	// Image Alignment.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'image_alignment' ),
		array(
			'default'           => $args['image_alignment'],
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'image_alignment',
		array(
			'label'    => __( 'Image Alignment', 'genesis' ),
			'section'  => $section,
			'settings' => _mai_customizer_get_field_name( $settings_field, 'image_alignment' ),
			'priority' => 10,
			'type'     => 'select',
			'choices'  => array(
				''            => __( '- None -', 'genesis' ),
				'aligncenter' => __( 'Center', 'genesis' ),
				'alignleft'   => __( 'Left', 'genesis' ),
				'alignright'  => __( 'Right', 'genesis' ),
			),
			'active_callback' => function() use ( $wp_customize, $settings_field ) {
				// Showing featured image and background is not image location.
				return (bool) ( $wp_customize->get_setting( _mai_customizer_get_field_name( $settings_field, 'content_archive_thumbnail' ) )->value() && ( 'background' != $wp_customize->get_setting( _mai_customizer_get_field_name( $settings_field, 'image_location' ) )->value() ) );
			},
		)
	);

	// Read More link - heading only.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'more_link_heading' ),
		array(
			'default' => '',
			'type'    => 'option',
		)
	);
	$wp_customize->add_control(
		new Mai_Customize_Control_Content( $wp_customize,
			'more_link_heading',
			array(
				'label'    => __( 'Read More Link', 'mai-pro-engine' ),
				'section'  => $section,
				'settings' => _mai_customizer_get_field_name( $settings_field, 'more_link_heading' ),
			)
		)
	);

	// More Link
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'more_link' ),
		array(
			'default' => 0,
			'type'    => 'option',
		)
	);
	$wp_customize->add_control(
		'more_link',
		array(
			'label'    => __( 'Display the Read More link', 'mai-pro-engine' ),
			'section'  => $section,
			'settings' => _mai_customizer_get_field_name( $settings_field, 'more_link' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

	// Entry Meta.
	if ( post_type_supports( $post_type, 'genesis-entry-meta-before-content' ) || post_type_supports( $post_type, 'genesis-entry-meta-after-content' ) ) {

		$remove_meta_choices = array();

		if ( post_type_supports( $post_type, 'genesis-entry-meta-before-content' ) ) {
			$remove_meta_choices['post_info'] = __( 'Remove Post Info', 'mai-pro-engine' );
		}

		if ( post_type_supports( $post_type, 'genesis-entry-meta-after-content' ) ) {
			$remove_meta_choices['post_meta'] = __( 'Remove Post Meta', 'mai-pro-engine' );
		}

		$wp_customize->add_setting(
			_mai_customizer_get_field_name( $settings_field, 'remove_meta' ),
			array(
				'default'           => $args['remove_meta'],
				'type'              => 'option',
				'sanitize_callback' => '_mai_customizer_multicheck_sanitize_key',
			)
		);
		$wp_customize->add_control(
			new Mai_Customize_Control_Multicheck( $wp_customize,
				'remove_meta',
				array(
					'label'    => __( 'Entry Meta', 'mai-pro-engine' ),
					'section'  => $section,
					'settings' => _mai_customizer_get_field_name( $settings_field, 'remove_meta' ),
					'priority' => 10,
					'choices'  => $remove_meta_choices,
				)
			)
		);

		// Entry Meta single.
		$remove_meta_single_key = sprintf( 'remove_meta_%s', $post_type );

		$wp_customize->add_setting(
			_mai_customizer_get_field_name( $settings_field, $remove_meta_single_key ),
			array(
				'default'           => $args[$remove_meta_single_key],
				'type'              => 'option',
				'sanitize_callback' => '_mai_customizer_multicheck_sanitize_key',
			)
		);
		$wp_customize->add_control(
			new Mai_Customize_Control_Multicheck( $wp_customize,
				$remove_meta_single_key,
				array(
					'label'    => __( 'Entry Meta', 'mai-pro-engine' ),
					'section'  => $section,
					'settings' => _mai_customizer_get_field_name( $settings_field, $remove_meta_single_key ),
					'priority' => 10,
					'choices'  => $remove_meta_choices,
				)
			)
		);

	}

	// Posts Per Page (saves/manages WP core option).
	$wp_customize->add_setting(
		'posts_per_page',
		array(
			'default'           => get_option( 'posts_per_page' ),
			'type'              => 'option',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'posts_per_page',
		array(
			'label'    => __( 'Posts Per Page', 'mai-pro-engine' ),
			'section'  => $section,
			'settings' => 'posts_per_page',
			'priority' => 10,
			'type'     => 'number',
		)
	);

	// Posts Nav.
	$wp_customize->add_setting(
		_mai_customizer_get_field_name( $settings_field, 'posts_nav' ),
		array(
			'default'           => 'numeric',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'posts_nav',
		array(
			'label'    => __( 'Pagination', 'genesis' ),
			'section'  => $section,
			'settings' => _mai_customizer_get_field_name( $settings_field, 'posts_nav' ),
			'priority' => 10,
			'type'     => 'select',
			'choices'  => array(
				'prev-next' => __( 'Previous / Next', 'genesis' ),
				'numeric'   => __( 'Numeric', 'genesis' ),
			),
		)
	);

}
