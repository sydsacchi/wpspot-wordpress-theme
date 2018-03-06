<?php
/**
 * wpspot Theme Customizer
 *
 * @package wpspot
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wpspot_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'wpspot_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'wpspot_customize_partial_blogdescription',
		) );
	}
	
	// footer text
	$txtcolors[] = array(
		'slug'=>'footer_text', 
		'default' => '#fff',
		'label' => 'Footer Text'
	);
	
	$wp_customize->add_section('custom_footer_text', array (
		'title' => __('Footer Text/Credits', 'wpspot'),
		'priority' => 500
	));
	
	$wp_customize->add_setting('footer_text_block', array(
		'default' => __('Copyright &copy; 2017 · Zero Theme · By: <a href="https://www.sidneysacchi.com" target="_blank" title="Web Design &amp; Web Consulting">Sidney Sacchi</a>' , 'wpspot'),
		#'sanitize_callback' => 'sanitize_text'		
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'custom_footer_text', array( 
		'label' => __( 'Footer Text/Credits', 'wpspot' ), 
		'section' => 'custom_footer_text', 
		'settings' => 'footer_text_block', 		
		'type' => 'text'
		))); 
		
	// Sanitize text 
	
		function sanitize_text( $text ) {
		return sanitize_text_field( $text ); 
		}
	
}
add_action( 'customize_register', 'wpspot_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function wpspot_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function wpspot_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wpspot_customize_preview_js() {
	wp_enqueue_script( 'wpspot-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'wpspot_customize_preview_js' );

function remove_customizer_settings( $wp_customize ){
  $wp_customize->remove_section('header_image');

}
add_action( 'customize_register', 'remove_customizer_settings', 20 );
