<?php
/**
 * Shortcodes for the MU HR Comp plugin
 *
 * @package MU HR Comp
 */

/**
 * Shortcode to display the Position Starting Letter list
 *
 * @return string
 */
function mu_hrcomp_letters_shortcode() {

	if ( get_transient( 'mu_hrcomp_letters' ) ) {
		$letters = get_transient( 'mu_hrcomp_letters' );
	} else {
		global $wpdb;

		$letters = $wpdb->get_results( 'SELECT DISTINCT LEFT(post_title, 1) as letter FROM ' . $wpdb->get_blog_prefix() . 'posts WHERE post_type = "mu-position" ORDER BY letter;' );

		set_transient( 'mu_hrcomp_letters', $letters, 60 * 60 * 24 );
	}

	$html = '<div class="flex flex-wrap space-x-1 justify-center">';
	foreach ( $letters as $letter ) {
		$html .= '<a href="?alpha=' . $letter->letter . '" class="text-base lg:text-lg mb-2 py-2 px-3 bg-gray-100 text-gray-700 hover:bg-white hover:text-gray-800 no-underline hover:underline">' . $letter->letter . '</a>';
	}
	$html .= '</div>';

	wp_reset_postdata();

	return $html;

}
add_shortcode( 'mu_hrcomp_letters', 'mu_hrcomp_letters_shortcode' );

/**
 * Shortcode to display listing of Positions.
 *
 * @return string Shortcode output.
 */
function mu_hrcomp_shortcode() {
	if ( get_query_var( 'alpha' ) ) {
		$alpha_letter = esc_attr( get_query_var( 'alpha' ) );
	} else {
		$alpha_letter = 'A';
	}

	$args = array(
		'post_type'      => 'mu-position',
		'posts_per_page' => -1,
		'orderby'        => array(
			'title' => 'ASC',
		),
		'extend_where'   => "(post_title like '" . $alpha_letter . "%')",
	);

	$alpha_query = new WP_Query( $args );

	$html  = '<table class="table w-full table-striped table-bordered table-services">';
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th>Current Title</th>';
	$html .= '<th class="w-1/6 text-center">New Pay Grade</th>';
	$html .= '<th class="w-1/6 text-center">Salary Range Min</th>';
	$html .= '<th class="w-1/6 text-center">Salary Range Mid</th>';
	$html .= '<th class="w-1/6 text-center">Salary Range Max</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';

	if ( $alpha_query->have_posts() ) {
		while ( $alpha_query->have_posts() ) {
			$alpha_query->the_post();
			$html .= '<tr>';

			if ( get_field( 'position_upload_pdf', get_the_id() ) ) {
				$html .= '<td><a href="' . esc_url( get_field( 'position_upload_pdf' )['url'], get_the_id() ) . '">' . get_the_title() . '</a></td>';
			} elseif ( get_field( 'position_url_pdf', get_the_id() ) ) {
				$html .= '<td><a href="' . esc_url( get_field( 'position_url_pdf' ), get_the_id() ) . '">' . get_the_title() . '</a></td>';
			} else {
				$html .= '<td>' . get_the_title() . '</td>';
			}

			$html .= '<td>' . get_field( 'position_new_pay_grade', get_the_id() ) . '</td>';
			$html .= '<td>' . get_field( 'position_salary_min', get_the_id() ) . '</td>';
			$html .= '<td>' . get_field( 'position_salary_mid', get_the_id() ) . '</td>';
			$html .= '<td>' . get_field( 'position_salary_max', get_the_id() ) . '</td>';
			$html .= '';
		}
	} else {
		$html .= '<tr>';
		$html .= '<td colspan="5">No positions start with the letter ' . esc_attr( $alpha_letter ) . '.</td>';
		$html .= '</tr>';
	}

	$html .= '</tbody>';
	$html .= '</table>';

	wp_reset_postdata();

	return $html;
}
add_shortcode( 'mu_hr_compensation', 'mu_hrcomp_shortcode' );
