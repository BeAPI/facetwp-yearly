<?php

namespace Beapi\Facetwp\Date;

class Yearly extends \FacetWP_Facet {

	use Singleton;

	public function __construct() {
		$this->label = __( 'Yearly archive', 'facetwp-yearly' );
	}

	/**
	 * Generate the facet HTML
	 *
	 * @param $params
	 *
	 * @return string
	 * @author Alexandre Sadowski
	 */
	public function render( $params ): string {
		$output          = '';
		$facet           = $params['facet'];
		$values          = (array) $params['values'];
		$selected_values = (array) $params['selected_values'];

		$label_any = empty( $facet['label_any'] ) ? __( 'Any', 'facetwp-yearly' ) : sprintf( __( '%s', 'facetwp-yearly' ),
			$facet['label_any'] );

		$output .= '<select class="facetwp-yearly">';
		$output .= sprintf( '<option value="">%s</option>', esc_html( $label_any ) );

		foreach ( $values as $result ) {
			$selected = in_array( $result['facet_value'], $selected_values ) ? ' selected' : '';

			$display_value = date_i18n( 'Y', strtotime( $result['facet_display_value'] ) );
			// Determine whether to show counts
			$show_counts = apply_filters( 'facetwp_facet_dropdown_show_counts', true );
			if ( $show_counts ) {
				$display_value .= sprintf( ' (%s)', $result['counter'] );
			}

			$output .= sprintf( '<option value="%s" %s>%s</option>',
				esc_attr( $result['facet_value'] ),
				$selected,
				esc_html( $display_value ) );
		}

		$output .= '</select>';

		return $output;
	}

	/**
	 * Load the available choices
	 */
	function load_values( $params ) {
		global $wpdb;

		$facet = $params['facet'];

		// Where
		$where_clause = $params['where_clause'];

		// Orderby
		$orderby = 'f.facet_display_value DESC';
		if ( 'asc' === $facet['orderby'] ) {
			$orderby = 'f.facet_display_value ASC';
		}

		// Limit
		$limit = 10;
		if ( absint( $facet['count'] ) > 0 ) {
			$limit = absint( $facet['count'] );
		}

		$orderby      = apply_filters( 'facetwp_facet_orderby', $orderby, $facet );
		$where_clause = apply_filters( 'facetwp_facet_where', $where_clause, $facet );

		$sql = "
        SELECT DATE_FORMAT(f.facet_value, '%Y') as facet_value, f.facet_display_value, COUNT(*) AS counter
        FROM {$wpdb->prefix}facetwp_index f
        WHERE f.facet_name = '{$facet['name']}' $where_clause
        GROUP BY DATE_FORMAT(f.facet_value, '%Y')
        ORDER BY $orderby
        LIMIT $limit";

		return $wpdb->get_results( $sql, ARRAY_A );
	}

	/**
	 * Filter the query based on selected values
	 *
	 * @param $params
	 *
	 * @return mixed|void
	 */
	public function filter_posts( $params ) {
		global $wpdb;

		$output          = [];
		$facet           = $params['facet'];
		$selected_values = $params['selected_values'];

		// Convert the selected value into an array
		// "0000" => array( year );
		$dates = $selected_values;

		$sql = $wpdb->prepare( "SELECT DISTINCT post_id
            FROM {$wpdb->prefix}facetwp_index
            WHERE facet_name = %s
            AND YEAR(facet_value) = %d
            ORDER BY facet_value DESC",
			$facet['name'],
			absint( $dates[0] )
		);

		$output = $wpdb->get_col( $sql );

		return $output;
	}

	/**
	 * Output admin settings HTML
	 */
	public function settings_html() {
		$sources = FWP()->helper->get_data_sources();?>
		<div class="facetwp-row type-yearly">
			<div>
				<?php esc_html_e( 'Default label', 'facetwp-yearly' ); ?>:
				<div class="facetwp-tooltip">
					<span class="icon-question">?</span>
					<div class="facetwp-tooltip-content">
						<?php esc_html_e( 'Customize the first option label (default: "Any")', 'facetwp-yearly'); ?>
					</div>
				</div>
			</div>
			<div>
				<input type="text" class="facet-label-any" value="<?php _e( 'Any', 'facetwp-yearly' ); ?>"/>
			</div>
		</div>
		<div class="facetwp-row type-yearly">
			<div>
				<?php esc_html_e( 'Archive order', 'facetwp-yearly' ); ?>:
				<div class="facetwp-tooltip">
					<span class="icon-question">?</span>
					<div class="facetwp-tooltip-content">
						<?php esc_html_e( 'Customize the archives order (default: "Newest to Oldest")', 'facetwp-yearly'); ?>
					</div>
				</div>
			</div>
			<div>
				<select class="facet-orderby">
					<option value="desc" selected><?php esc_html_e( 'Newest to Oldest', 'facetwp-yearly' ); ?></option>
					<option value="asc"><?php esc_html_e( 'Oldest to newest', 'facetwp-yearly' ); ?></option>
				</select>
			</div>
		</div>
		<div class="facetwp-row type-yearly">
			<div>
				<?php esc_html_e( 'Count', 'facetwp-yearly' ); ?>:
				<div class="facetwp-tooltip">
					<span class="icon-question">?</span>
					<div class="facetwp-tooltip-content">
						<?php esc_html_e( 'The maximum number of facet choices to show', 'facetwp-yearly' ); ?>
					</div>
				</div>
			</div>
			<div><input type="text" class="facet-count" value="10"/></div>
		</div>
	<?php }

	/**
	 * Output any front-end scripts
	 */
	public function front_scripts() {
		FWP()->display->assets['index.js'] = BEAPI_FACETWP_YEARLY_URL . '/assets/js/index.js';
	}
}