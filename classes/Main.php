<?php

namespace Beapi\Facetwp\Date;

/**
 * The purpose of the main class is to init all the plugin base code like :
 *  - Taxonomies
 *  - Post types
 *  - Shortcodes
 *  - Posts to posts relations etc.
 *  - Loading the text domain
 *
 * Class Main
 * @package Beapi\Facetwp\Date
 */
class Main {
	/**
	 * Use the trait
	 */
	use Singleton;

	protected function init() {
		add_action( 'init', [ $this, 'init_translations' ] );
		add_action( 'facetwp_facet_types', [ $this, 'register_custom_facet' ] );
	}

	/**
	 * Load the plugin translation
	 */
	public function init_translations(): void {
		// Load translations
		load_plugin_textdomain( 'facetwp-yearly', false, BEAPI_FACETWP_YEARLY_PLUGIN_DIRNAME . '/languages' );
	}

	/**
	 * @param $facet_types
	 *
	 * @return mixed
	 * @author Alexandre Sadowski
	 */
	public function register_custom_facet( $facet_types ) {
		$facet_types['yearly'] = new Yearly();

		return $facet_types;
	}
}