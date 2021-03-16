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
		add_filter( 'facetwp_facet_sources', [ 'Beapi\Facetwp\Date\Yearly', 'yearly_sources' ] );
		add_filter( 'facetwp_indexer_post_facet', [ 'Beapi\Facetwp\Date\Yearly', 'yearly_indexer' ], 10, 2 );
	}

	/**
	 * Load the plugin translation
	 */
	public function init_translations(): void {
		// Load translations
		load_plugin_textdomain( 'facetwp-yearly', false, BEAPI_FACETWP_YEARLY_PLUGIN_DIRNAME . '/languages' );
	}
}