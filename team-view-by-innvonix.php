<?php
/**
 * Plugin Name: Team View by Innvonix Technologies
 * Description: Team Members plugin to provide Team view in different layouts also as slider or grid view. New material colors shortcodes and many more. Other options will be provided very soon in new update.
 * Author: Innvonix Technologies
 * Author URI: http://www.innvonix.com
 * Requires at least: 4.4
 * Tested up to: 4.7
 * Version:   1.0
 * Text Domain: innvonix-team
 * Domain Path: /i18n/languages/
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * package team-view-by-innvonix
 * Copyright Copyright (c) 2017, Innvonix Technologies LLP.

 */

class Invx_Team_Member {

	/**
	 * PHP5 constructor method.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Set the constants needed by the plugin. */
		add_action('plugins_loaded', array(&$this, 'constants'), 1);

		/* Load the functions files. */
		add_action('plugins_loaded', array(&$this, 'includes'), 3);

		/* Load the admin files. */
		add_action('plugins_loaded', array(&$this, 'admin'), 4);

		/* Register activation hook. */
		register_activation_hook(__FILE__, array(&$this, 'activation'));

	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function constants() {

		/* Set constant path to the plugin directory. */
		define('TEAM_VIEW_INVX_DIR', trailingslashit(plugin_dir_path(__FILE__)));

		/* Set the constant path to the plugin directory URI. */
		define('TEAM_VIEW_INVX_URI', trailingslashit(plugin_dir_url(__FILE__)));

		/* Set the constant path to the includes directory. */
		define('TEAM_VIEW_INVX_INCLUDES', TEAM_VIEW_INVX_DIR . trailingslashit('includes'));

		/* Set the constant path to the admin directory. */
		define('TEAM_VIEW_INVX_ADMIN', TEAM_VIEW_INVX_DIR . trailingslashit('admin'));
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function includes() {
		require_once TEAM_VIEW_INVX_INCLUDES . 'functions.php';
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function admin() {
		require_once TEAM_VIEW_INVX_ADMIN . 'admin.php';
	}

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	function activation() {

		/* Get the administrator role. */
		$role = get_role('administrator');

		/* If the administrator role exists, add required capabilities for the plugin. */
		if (!empty($role)) {

			$role->add_cap('manage_team');
			$role->add_cap('create_teams');
			$role->add_cap('edit_teams');
		}
	}
}

new Invx_Team_Member();

/* Add stylesheet and javascript files with wp_enqueue_scripts hook */
add_action('wp_enqueue_scripts', 'invx_team_view_style');
function invx_team_view_style(){
	$date = time();
	wp_enqueue_style('invx-team-style', TEAM_VIEW_INVX_URI . 'assets/css/style.css?'.$date.'',array(),'1.0','all');
	wp_enqueue_style('font-awesome-invx-team', TEAM_VIEW_INVX_URI . 'assets/css/font-awesome.css', array(), '4.7.0', 'all');
	wp_enqueue_style('owl-carousel-invx-team', TEAM_VIEW_INVX_URI . 'assets/css/owl.carousel.css', array(), '1.3.3', 'all');
	wp_enqueue_style('owl-theme-invx-team', TEAM_VIEW_INVX_URI . 'assets/css/owl.theme.css', array(), '1.3.3', 'all');
	wp_enqueue_style('owl-transitions-invx-team', TEAM_VIEW_INVX_URI . 'assets/css/owl.transitions.css', array(), '1.3.2', 'all');
	wp_enqueue_style('bootstrap-invx-team', TEAM_VIEW_INVX_URI . 'assets/css/bootstrap.min.css', array(), '3.3.7', 'all');
	wp_enqueue_script('owl-carousel-min', TEAM_VIEW_INVX_URI . 'assets/js/owl.carousel.min.js', array('jquery'), '1.0', true);
}


/* Adding custom template for member view from includes folder */

add_filter('single_template', 'invx_team_view_page_templates');

function invx_team_view_page_templates($single) {
	global $wp_query, $post;

	if ($post->post_type == "member_team") {
		if (file_exists(TEAM_VIEW_INVX_INCLUDES . '/member-template.php')) {
			return TEAM_VIEW_INVX_INCLUDES . 'member-template.php';
		}
	}
	return $single;
}
?>