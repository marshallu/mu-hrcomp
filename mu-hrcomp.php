<?php
/**
 * MU HR Comp
 *
 * Compensation Listing for HR
 *
 * @package  MU HR Comp
 *
 * Plugin Name:  MU HR Comp
 * Plugin URI: https://www.marshall.edu
 * Description: Compensation Listing for HR
 * Version: 1.0
 * Author: Christopher McComas
 */

if ( ! class_exists( 'ACF' ) ) {
	return new WP_Error( 'broke', __( 'Advanced Custom Fields is required for this plugin.', 'my_textdomain' ) );
}

require plugin_dir_path( __FILE__ ) . '/acf-fields.php';
require plugin_dir_path( __FILE__ ) . '/shortcodes.php';

/**
 * Register a custom post type called "mu-position."
 *
 * @see get_post_type_labels() for label keys.
 */
function mu_hrcomp_post_type() {
	$labels = array(
		'name'                  => _x( 'Positions', 'Post type general name', 'mu-hrcomp' ),
		'singular_name'         => _x( 'Position', 'Post type singular name', 'mu-hrcomp' ),
		'menu_name'             => _x( 'HR Compensation', 'Admin Menu text', 'mu-hrcomp' ),
		'name_admin_bar'        => _x( 'Position', 'Add New on Toolbar', 'mu-hrcomp' ),
		'add_new'               => __( 'Add New', 'mu-hrcomp' ),
		'add_new_item'          => __( 'Add New Position', 'mu-hrcomp' ),
		'new_item'              => __( 'New Position', 'mu-hrcomp' ),
		'edit_item'             => __( 'Edit Position', 'mu-hrcomp' ),
		'view_item'             => __( 'View Position', 'mu-hrcomp' ),
		'all_items'             => __( 'All Positions', 'mu-hrcomp' ),
		'search_items'          => __( 'Search Positions', 'mu-hrcomp' ),
		'parent_item_colon'     => __( 'Parent Positions:', 'mu-hrcomp' ),
		'not_found'             => __( 'No Positions found.', 'mu-hrcomp' ),
		'not_found_in_trash'    => __( 'No Positions found in Trash.', 'mu-hrcomp' ),
		'featured_image'        => _x( 'Position Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'mu-hrcomp' ),
		'set_featured_image'    => _x( 'Set image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'mu-hrcomp' ),
		'remove_featured_image' => _x( 'Remove image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'mu-hrcomp' ),
		'use_featured_image'    => _x( 'Use as image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'mu-hrcomp' ),
		'archives'              => _x( 'Position archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'mu-hrcomp' ),
		'insert_into_item'      => _x( 'Insert into Position', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'mu-hrcomp' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Position', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'mu-hrcomp' ),
		'filter_items_list'     => _x( 'Filter Positions list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4', 'mu-hrcomp' ),
		'items_list_navigation' => _x( 'Positions list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4', 'mu-hrcomp' ),
		'items_list'            => _x( 'Positions list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4', 'mu-hrcomp' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'position' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 52,
		'supports'           => array( 'title', 'custom-fields', 'page-attributes', 'revisions' ),
		'menu_icon'          => 'dashicons-money-alt',
	);

	register_post_type( 'mu-position', $args );
}

/**
 * Proper way to enqueue scripts and styles
 */
function mu_hrcomp_scripts() {
	wp_enqueue_style( 'mu-hrcomp', plugin_dir_url( __FILE__ ) . 'css/mu-hrcomp.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/mu-hrcomp.css' ), 'all' );
}
add_action( 'wp_enqueue_scripts', 'mu_hrcomp_scripts' );

/**
 * Flush rewrites whenever the plugin is activated.
 */
function mu_hrcomp_activate() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mu_hrcomp_activate' );

/**
 * Flush rewrites whenever the plugin is deactivated, also unregister 'mu-position' post type.
 */
function mu_hrcomp_deactivate() {
	unregister_post_type( 'mu-position' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mu_hrcomp_deactivate' );

/**
 * Change placeholder text on Create/Edit Profile page to 'Enter Position Title Here'
 *
 * @param string $title Current Post title.
 */
function mu_hrcomp_change_title_placeholder_text( $title ) {
	$screen = get_current_screen();

	if ( 'mu-position' === $screen->post_type ) {
		return 'Enter Position Title Here';
	}
}
add_filter( 'enter_title_here', 'mu_hrcomp_change_title_placeholder_text' );

/**
 * Remove YoastSEO metaboxes from Positions
 */
function mu_hrcomp_remove_seo_metaboxes() {
	remove_meta_box( 'wpseo_meta', 'mu-position', 'normal' );
}
add_action( 'add_meta_boxes', 'mu_hrcomp_remove_seo_metaboxes', 11 );

/**
 * Remove Date, Last Modified, and Yoast SEO Columns from Position listing page
 *
 * @param type $columns Default WordPress post columns.
 */
function mu_hrcomp_set_custom_columns( $columns ) {

	unset( $columns['date'] );

	if ( ! is_super_admin() ) {
		unset( $columns['modified'] );
	}

	unset( $columns['wpseo-score'] );
	unset( $columns['wpseo-score-readability'] );
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );
	unset( $columns['wpseo-links'] );
	unset( $columns['wpseo-linked'] );
	$columns['paygrade']   = __( 'Pay Grade', 'mu-hrcomp' );
	$columns['salary_min'] = __( 'Salary Min', 'mu-hrcomp' );
	$columns['salary_mid'] = __( 'Salary Mid', 'mu-hrcomp' );
	$columns['salary_max'] = __( 'Salary Max', 'mu-hrcomp' );
	$columns['date']       = __( 'Date' );

	return $columns;
}
add_filter( 'manage_mu-position_posts_columns', 'mu_hrcomp_set_custom_columns' );

/**
 * Add value from meta field to academic year column.
 *
 * @param array   $column Default WordPress post columns.
 * @param integer $post_id The ID of the post.
 */
function mu_hrcomp_custom_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'paygrade':
			echo esc_attr( get_post_meta( $post_id, 'position_new_pay_grade', true ) );
			break;
		case 'salary_min':
			echo esc_attr( get_post_meta( $post_id, 'position_salary_min', true ) );
			break;
		case 'salary_mid':
			echo esc_attr( get_post_meta( $post_id, 'position_salary_mid', true ) );
			break;
		case 'salary_max':
			echo esc_attr( get_post_meta( $post_id, 'position_salary_max', true ) );
			break;
	}
}
add_action( 'manage_mu-position_posts_custom_column', 'mu_hrcomp_custom_columns', 10, 2 );

/**
 * Remove View link on Dashboard for Position.
 *
 * @param array $actions The array of actions on the post row.
 * @return array
 */
function mu_hrcomp_remove_view_action( $actions ) {
	if ( 'mu-position' === get_post_type() ) {
		unset( $actions['view'] );
	}
	return $actions;
}
add_filter( 'post_row_actions', 'mu_hrcomp_remove_view_action', 10, 1 );

/**
 * Add 'alpha' to the acceptable URL parameters
 *
 * @param array $vars The array of acceptable URL parameters.
 * @return array
 */
function mu_hrcomp_url_parameters( $vars ) {
	$vars[] = 'alpha';
	return $vars;
}
add_filter( 'query_vars', 'mu_hrcomp_url_parameters' );

/**
 * Redirect the Position custom post type to the PDF
 *
 */
function mu_hrcomp_redirect_position_to_pdf() {
	if ( 'mu-position' === get_post_type() ) {
		if ( get_field( 'position_upload_pdf', get_queried_object_id() ) ) {
			wp_redirect( get_field( 'position_upload_pdf' )['url'], get_queried_object_id() );
			die();
		} elseif ( get_field( 'position_url_pdf', get_queried_object_id() ) ) {
			wp_redirect( get_field( 'position_url_pdf', get_queried_object_id() ) );
			die();
		} else {
			wp_redirect( get_site_url() );
			die();
		}
	}
}
add_action( 'template_redirect', 'mu_hrcomp_redirect_position_to_pdf' );

add_action( 'init', 'mu_hrcomp_post_type' );
