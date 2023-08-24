<?php

/**
 * Plugin Name: MV Slider
 * Plugin URI: https://www.wordpress.org/mv-slider
 * Description: Plugin's description here.
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Taner Alberto
 * Author URI: https://www.taneralberto.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mv-slider
 * Domain Path: /languages
 */

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see < http://www.gnu.org/licenses/ >.
*/

if ( ! defined( 'ABSPATH' ) ) {
	// die( 'Bla bla bla' );
	exit;
}

if ( ! class_exists( 'MV_Slider' ) ) {
	class MV_Slider {
		function __construct() {
			$this->define_constants();
			$this->load_textdomain();

			add_action( 'admin_menu', array( $this, 'add_menu' ) );

			/**
			 * @context 2-lines
			 * @group MV_Slider_Post_Type
			 * @tag custom post type, cpt
			 * @step 4
			 *
			 * Se requiere el archivo donde está creada la clase del Custom
			 * Post Type y se instancia para ejecutar su constructor.
			 */
			require_once( MV_SLIDER_PATH . 'post-types/class.mv-slider-cpt.php' );
			$MV_Slider_Post_Type = new MV_Slider_Post_Type();

			require_once( MV_SLIDER_PATH . 'class.mv-slider-settings.php' );
			$MV_Slider_Settings = new MV_Slider_Settings();

			require_once( MV_SLIDER_PATH . 'shortcodes/class.mv-slider-shortcode.php' );
			$MV_Slider_Shortcode = new MV_Slider_Shortcode();

			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'regsiter_admin_scripts' ) );
		}

		public function define_constants() {
			define( 'MV_SLIDER_PATH', plugin_dir_path( __FILE__ ) );
			define( 'MV_SLIDER_URL', plugin_dir_url( __FILE__ ) );
			define( 'MV_SLIDER_VERSION', '1.0.0' );
		}

		public static function activate() {
			// flush_rewrite_rules();
			// Update permalinks, erasing rewrite_rules's field on database
			update_option( 'rewrite_rules', '' );
		}

		public static function deactivate() {
			flush_rewrite_rules();
			unregister_post_type( 'mv-slider' );
		}

		public static function uninstall() {
			delete_option( 'mv_slider_options' );
			$posts = get_posts(
				array(
					'post_type' => 'mv-slider',
					'number_posts' => -1,
					'post_status' => 'any'
				)
			);

			foreach( $posts as $post ) {
				wp_delete_post( $post->ID, true );
			}

		}

		public function load_textdomain() {
			load_plugin_textdomain(
				'mv-slider',
				false,
				dirname( plugin_basename( __FILE__ ) ) . '/languages/',
			);
		}

		public function add_menu() {
			add_menu_page(
				'MV Slider Options',
				'MV Slider',
				'manage_options',
				'mv_slider_admin',
				array( $this, 'mv_slider_settings_page' ),
				'dashicons-images-alt2'
			);

			add_submenu_page(
				'mv_slider_admin',
				esc_html__( 'Manage Slides', 'mv-slider' ),
				esc_html__( 'Manage Slides', 'mv-slider' ),
				'manage_options',
				'edit.php?post_type=mv-slider',
				null,
				null
			);

			add_submenu_page(
				'mv_slider_admin',
				esc_html__( 'Add New Slide', 'mv-slider' ),
				esc_html__( 'Add New Slide', 'mv-slider' ),
				'manage_options',
				'post-new.php?post_type=mv-slider',
				null,
				null
			);

			add_submenu_page(
				'mv_slider_admin',
				esc_html__( 'Settings', 'mv-slider' ),
				esc_html__( 'Settings', 'mv-slider' ),
				'manage_options',
				'mv_slider_settings',
				array( $this, 'mv_slider_settings_page' ),
				null
			);
		}

		public function mv_slider_settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( isset( $_GET['settings-updated'] ) ) {
				add_settings_error( 'mv_slider_options', 'mv_slider_message', esc_html__( 'Settings Saved Successfully', 'mv-slider' ), 'success' );
			}
			settings_errors( 'mv_slider_options' );
			require( MV_SLIDER_PATH . 'views/settings-page.php' );
		}

		public function register_scripts() {
			wp_register_script( 'mv-slider-main-jq', MV_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js', array( 'jquery' ), MV_SLIDER_VERSION, true );
			wp_register_style( 'mv-slider-main-css', MV_SLIDER_URL . 'vendor/flexslider/flexslider.css', array(), MV_SLIDER_VERSION, 'all' );
			wp_register_style( 'mv-slider-style-css', MV_SLIDER_URL . 'assets/css/frontend.css', array(), MV_SLIDER_VERSION, 'all' );
		}

		public static function add_extra_code_to_script() {
			$show_bullets = isset( MV_Slider_Settings::$options['mv_slider_bullets'] ) && MV_Slider_Settings::$options['mv_slider_bullets'] == 1 ? 'true' : 'false';
			wp_enqueue_script( 'mv-slider-options-js', MV_SLIDER_URL . 'vendor/flexslider/flexslider.js', array( 'jquery' ), MV_SLIDER_VERSION, true );
			wp_add_inline_script( 'mv-slider-options-js', 'var SHOW_BULLETS = ' . $show_bullets, 'before' );
		}

		public function regsiter_admin_scripts() {
			global $typenow;
			if ( $typenow == 'mv-slider' ) {
				wp_enqueue_style( 'mv-slider-admin', MV_SLIDER_URL . 'assets/css/admin.css' );
			}
		}
	}
}

if ( class_exists( 'MV_Slider' ) ) {
	register_activation_hook( __FILE__, array( 'MV_Slider', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'MV_Slider', 'deactivate' ) );
	register_uninstall_hook( __FILE__, array( 'MV_Slider', 'uninstall' ) );

	$mv_slider = new MV_Slider();
}