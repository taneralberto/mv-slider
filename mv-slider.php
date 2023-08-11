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

if( ! defined( 'ABSPATH' )) {
    // die( 'Bla bla bla' );
    exit;
}

if ( ! class_exists( 'MV_Slider' ) ) {
    class MV_Slider {
        function __construct() {
            $this->define_constants();
        }

        public function define_constants() {
            define( 'MV_SLIDER_PATH', plugin_dir_path( __FILE__ ) );
            define( 'MV_SLIDER_URL', plugin_dir_url( __FILE__ ) );
            define( 'MV_SLIDER_VERSION', '1.0.0' );
        }

        public static function activate() {
            // flush_rewrite_rules();
            update_option( 'rewrite_rules', '' );

        }

        public static function deactivate() {
            flush_rewrite_rules();
        }

        public static function unistall() {

        }
    }
}

if ( class_exists( 'MV_Slider') ) {
    register_activation_hook( __FILE__, array( 'MV_Slider', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'MV_Slider', 'deactivate' ) );
    register_unistall_hook( __FILE__, array( 'MV_Slider', 'unistall' ) );

    $mv_slider = new MV_Slider();
}