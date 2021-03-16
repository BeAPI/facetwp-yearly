<?php

namespace Beapi\Facetwp\Date;

class Yearly {

	use Singleton;

	protected function init(): void {
		add_filter( 'facetwp_facet_sources', [ $this , 'yearly_sources' ] );
		add_filter( 'facetwp_indexer_post_facet', [ $this, 'yearly_indexer' ], 10, 2 );
	}
	/**
	 * Add new source "yearly"
	 *
	 * @param array $sources
	 *
	 * @return array|mixed
	 * @author Alexandre Sadowski
	 */
	public function yearly_sources( $sources = [] ) {
		$sources['yearly_archive'] = [
			'label'   => __( 'Yearly archive', 'facetwp-yearly' ),
			'choices' => [ 'yearly_archive' => __( 'Yearly archive', 'facetwp-yearly' ) ],
			'weight'  => 40,
		];

		return $sources;
	}

	/**
	 * Index Yearly date
	 *
	 * @param bool $bypass
	 * @param array $defaults
	 *
	 * @return bool
	 */
	public function yearly_indexer( $bypass, $defaults ) {
		$params = $defaults['defaults'];

		if ( 'yearly_archive' !== $params['facet_source'] ) {
			return $bypass;
		}

		$current_object = get_post( (int) $params['post_id'] );

		$new_params = wp_parse_args(
			[
				'facet_value'         => date_i18n( 'Y', strtotime( $current_object->post_date ) ),
				'facet_display_value' => date_i18n( 'Y', strtotime( $current_object->post_date ) ),
			],
			$params
		);

		/**
		 * Filters values send to the indexer.
		 *
		 * @param array $new_params Associative array of parameters.
		 * @param string $current_object \WP_Post
		 *
		 */
		$new_params = apply_filters( 'facetp2p_p2p_index_params', $new_params, $current_object );
		FWP()->indexer->index_row( $new_params );

		return true;
	}
}