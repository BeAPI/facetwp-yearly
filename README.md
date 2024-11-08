# FacetWP -Yearly

Custom yearly source index

# Requirements

- Require [WordPress](https://wordpress.org/) 4.7+ / Tested up to 5.6.1
- Require PHP 5.6
- [FacetWP](https://facetwp.com/) 3.7.2+

# Installation

First activate FacetWP.
Then activate FacetWP - Yearly to create your yearly facet.

Create a Facet, for example a Select Facet, and choose "Yearly archive" as Data source.

<img width="700" alt="image" src="https://github.com/BeAPI/facetwp-yearly/assets/7976501/77717727-29a0-4b5d-b357-7659ddd1ba45">

# Tips

By default, years are sorted by chronological order. If you want to reverse this order, you can use this snippet : 
```
add_filter( 'facetwp_facet_orderby', 'sort_yearly_desc', 10, 2 );

function sort_yearly_desc( $orderby, $facet ) {
	if ( 'YOUR_FACET_NAME' === $facet['name'] ) {
		$orderby = 'f.facet_display_value DESC';
	}
	return $orderby;
}
```
