<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://github.com/nirav4491
 * @since      1.0.0
 *
 * @package    Cs_Custom_Permalinks
 * @subpackage Cs_Custom_Permalinks/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cs_Custom_Permalinks
 * @subpackage Cs_Custom_Permalinks/public
 * @author     Nirav Mehta <nirmehta4491@gmail.com>
 */
class Cs_Custom_Permalinks_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function cscp_enqueue_styles_scripts() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cs-custom-permalinks-public.css', array(), $this->version, 'all' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cs-custom-permalinks-public.js', array( 'jquery' ), $this->version, false );
	}
	/**
	 * Function to define all init functions.
	 *
	 * @since 1.0.0
	 */
	public function cscp_init_functions() {
		cscp_custom_song_post_type();
		// global $wp_rewrite;
		// $wp_rewrite->add_permastruct('song', '/%customname%/', false);
		// $wp_rewrite->flush_rules();
	}
	/**
	 * Function to return change permalink structure.
	 *
	 * @param String $permalink This variable holds the permalink value.
	 * @param Object $post This variable holds post object.
	 * @since 1.0.0
	 */
	public function cscp_change_structure_permalink( $permalink, $post ) {
		if ( 'song' === $post->post_type ) {
			$permalink = str_replace( '%customname%/', 'niravmehta/'. $post->post_name, $permalink );
		}
		return $permalink;
		
	}
	/**
	 * Function to return adddin parse requests
	 *
	 * @param Array $query This variable holds the query array.
	 * @since 1.0.0
	 */
	public function cscp_generate_request( $query ) {
		debug( $query );
		die;
		global $wpdb;
		$requested_url = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING );
		if ( ! empty( $requested_url ) ) {
			$original_url = null;
			$url     = wp_parse_url( get_bloginfo( 'url' ) );
			$url     = isset( $url['path'] ) ? $url['path'] : '';
			$request = ltrim( substr( $requested_url, strlen( $url ) ), '/' );
			$pos     = strpos( $request, '?' );
			if ( $pos ) {
				$request = substr( $request, 0, $pos );
			}
			if ( ! $request ) {
				return $query;
			}
			$slug_exploded = explode( '/', $request );
			$slug_array    = array_filter( $slug_exploded );
			$slug          = end( $slug_array );
			$post_data     = $wpdb->get_results( $wpdb->prepare( 'SELECT `ID` FROM '. $wpdb->posts . ' WHERE `post_name`=%s', $slug  ), ARRAY_A );
			debug( $post_data );
			die;
		}
	}
	
}
