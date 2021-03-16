<?php
/*
Plugin Name: FacetWP Yearly
Version: 2.0.1
Version Boilerplate: 3.0.0
Plugin URI: https://beapi.fr
Description: Add custom yearly source index
Author: Be API Technical team
Author URI: https://beapi.fr
Domain Path: languages
Text Domain: facetwp-yearly

----

Copyright 2021 Be API Technical team (human@beapi.fr)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


// Load composer autoload
if ( file_exists( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
}

// Plugin constants
define( 'BEAPI_FACETWP_YEARLY_VERSION', '2.0.1' );
define( 'BEAPI_FACETWP_YEARLY_MIN_PHP_VERSION', '7.0' );
define( 'BEAPI_FACETWP_YEARLY_VIEWS_FOLDER_NAME', 'facetwp-yearly' );

// Plugin URL and PATH
define( 'BEAPI_FACETWP_YEARLY_URL', plugin_dir_url( __FILE__ ) );
define( 'BEAPI_FACETWP_YEARLY_DIR', plugin_dir_path( __FILE__ ) );
define( 'BEAPI_FACETWP_YEARLY_PLUGIN_DIRNAME', basename( rtrim( dirname( __FILE__ ), '/' ) ) );

// Check PHP min version
if ( version_compare( PHP_VERSION, BEAPI_FACETWP_YEARLY_MIN_PHP_VERSION, '<' ) ) {
	require_once BEAPI_FACETWP_YEARLY_DIR . 'classes/Compatibility.php';

	// Possibly display a notice, trigger error
	add_action( 'admin_init', [ 'Beapi\Facetwp\Date\Compatibility', 'admin_init' ] );

	// Stop execution of this file
	return;
}

add_action( 'plugins_loaded', 'init_facetwp_yearly_plugin' );
/**
 * Init the plugin
 */
function init_facetwp_yearly_plugin() {
	// Client
	\Beapi\Facetwp\Date\Main::get_instance();
	\Beapi\Facetwp\Date\Yearly::get_instance();
}