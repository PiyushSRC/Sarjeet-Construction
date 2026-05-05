<?php
/**
 * Helpers — bridge ACF (when present) with sensible defaults.
 *
 * Every section template calls sarjeet_field( 'group.subkey' ) and gets a value
 * from ACF options first, falling back to inc/defaults.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function sarjeet_acf_active(): bool {
	return function_exists( 'get_field' );
}

function sarjeet_field( string $path, $post_id = 'option' ) {
	// Per-request memoization: each unique (path, post_id) is resolved at most once per page render.
	static $cache = [];
	$cache_key = $path . '|' . ( is_scalar( $post_id ) ? (string) $post_id : 'opt' );
	if ( array_key_exists( $cache_key, $cache ) ) {
		return $cache[ $cache_key ];
	}

	$defaults = sarjeet_defaults();

	if ( sarjeet_acf_active() ) {
		// ACF stores groups flatly; convention here: top-level field name = group key (hero, contact, brand, etc.)
		// e.g. sarjeet_field('hero.headline_html') reads ACF 'hero' group -> 'headline_html' subfield.
		$parts = explode( '.', $path );
		$top   = array_shift( $parts );
		$value = get_field( $top, $post_id );
		foreach ( $parts as $key ) {
			if ( is_array( $value ) && array_key_exists( $key, $value ) ) {
				$value = $value[ $key ];
			} else {
				$value = null;
				break;
			}
		}
		if ( ! empty( $value ) || $value === '0' || $value === 0 ) {
			return $cache[ $cache_key ] = $value;
		}
	}

	// Fallback: walk defaults.
	$value = $defaults;
	foreach ( explode( '.', $path ) as $key ) {
		if ( is_array( $value ) && array_key_exists( $key, $value ) ) {
			$value = $value[ $key ];
		} else {
			return $cache[ $cache_key ] = null;
		}
	}
	return $cache[ $cache_key ] = $value;
}

/**
 * Render the small geometric service icon inline (matches components-a.jsx Icon).
 */
function sarjeet_icon( string $name, int $size = 56 ): void {
	switch ( $name ) {
		case 'sewer':
			?>
			<svg width="<?php echo esc_attr( $size ); ?>" height="<?php echo esc_attr( $size ); ?>" viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
				<circle cx="28" cy="28" r="20" />
				<circle cx="28" cy="28" r="12" />
				<path d="M8 28 H48" />
				<path d="M28 8 V48" />
				<path d="M14 14 L42 42" />
				<path d="M42 14 L14 42" />
			</svg>
			<?php break;
		case 'water':
			?>
			<svg width="<?php echo esc_attr( $size ); ?>" height="<?php echo esc_attr( $size ); ?>" viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
				<path d="M28 6 C28 6 12 24 12 36 a16 16 0 0 0 32 0 C44 24 28 6 28 6Z" />
				<path d="M20 36 a8 8 0 0 0 16 0" />
			</svg>
			<?php break;
		case 'urban':
			?>
			<svg width="<?php echo esc_attr( $size ); ?>" height="<?php echo esc_attr( $size ); ?>" viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
				<rect x="6" y="22" width="14" height="28" />
				<rect x="22" y="10" width="14" height="40" />
				<rect x="38" y="18" width="12" height="32" />
				<path d="M10 28 H16 M10 34 H16 M10 40 H16" />
				<path d="M26 16 H32 M26 22 H32 M26 28 H32 M26 34 H32 M26 40 H32" />
				<path d="M42 24 H46 M42 30 H46 M42 36 H46 M42 42 H46" />
			</svg>
			<?php break;
	}
}

/**
 * Get the projects list. Uses CPT when posts exist, otherwise the seed array.
 */
function sarjeet_projects(): array {
	// Stable sort: ongoing projects first, then completed, then unknown.
	// Also renumbers the `n` label (P/01, P/02, ...) so it reflects the displayed order.
	$sort_by_status = static function ( array &$list ) {
		$rank = static function ( $y ) {
			$y = strtolower( trim( (string) ( $y ?? '' ) ) );
			if ( $y === 'ongoing' )   return 0;
			if ( $y === 'completed' ) return 1;
			return 2;
		};
		usort( $list, static function ( $a, $b ) use ( $rank ) {
			return $rank( $a->year ?? '' ) <=> $rank( $b->year ?? '' );
		} );
		foreach ( $list as $i => $p ) {
			$p->n = str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT );
		}
	};

	$posts = get_posts( [
		'post_type'      => 'project',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	] );

	if ( empty( $posts ) ) {
		// Seed-data fallback — mirrors data.js.
		$seeded = [];
		foreach ( sarjeet_defaults()['projects'] as $p ) {
			$slug = $p['slug'] ?? sanitize_title( $p['title'] );
			$seeded[] = (object) [
				'ID'         => 0,
				'slug'       => $slug,
				'permalink'  => home_url( '/?project_view=' . rawurlencode( $slug ) ),
				'n'          => $p['n'],
				'title'      => $p['title'],
				'cat'        => $p['cat'],
				'cat_slug'   => sanitize_title( $p['cat'] ),
				'loc'        => $p['loc'],
				'value'      => $p['value'],
				'year'       => $p['year'],
				'length'     => $p['length'],
				'capacity'   => $p['capacity'],
				'client'     => $p['client'],
				'partners'   => $p['partners'] ?? [],
				'specs'      => $p['specs'] ?? [],
				'desc'       => $p['desc'],
				'shape'      => $p['shape'],
				'img'        => $p['img'],
			];
		}
		$sort_by_status( $seeded );
		return $seeded;
	}

	$out = [];
	$i = 0;
	foreach ( $posts as $post ) {
		$i++;
		$terms = get_the_terms( $post->ID, 'project_category' );
		$cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
		$cat_slug = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';

		$thumb_id  = get_post_thumbnail_id( $post->ID );
		$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'sarjeet_card' ) : '';

		$out[] = (object) [
			'ID'        => $post->ID,
			'slug'      => $post->post_name,
			'permalink' => get_permalink( $post ),
			'n'         => str_pad( (string) $i, 2, '0', STR_PAD_LEFT ),
			'title'     => get_the_title( $post ),
			'cat'       => $cat,
			'cat_slug'  => $cat_slug,
			'loc'       => (string) get_post_meta( $post->ID, 'project_location', true ),
			'value'     => (string) get_post_meta( $post->ID, 'project_value', true ),
			'year'      => (string) get_post_meta( $post->ID, 'project_year', true ),
			'length'    => (string) get_post_meta( $post->ID, 'project_length', true ),
			'capacity'  => (string) get_post_meta( $post->ID, 'project_capacity', true ),
			'client'    => (string) get_post_meta( $post->ID, 'project_client', true ),
			'desc'      => get_the_excerpt( $post ),
			'shape'     => (string) ( get_post_meta( $post->ID, 'project_shape', true ) ?: 'default' ),
			'img'       => $thumb_url,
		];
	}
	$sort_by_status( $out );
	return $out;
}

/**
 * Look up a single project by slug from sarjeet_projects().
 */
function sarjeet_project_by_slug( string $slug ) {
	$slug = sanitize_title( $slug );
	if ( $slug === '' ) return null;
	foreach ( sarjeet_projects() as $p ) {
		if ( ( $p->slug ?? '' ) === $slug ) return $p;
	}
	return null;
}

/**
 * Pick up to N other projects (excluding $exclude_slug) for the "related" block.
 */
function sarjeet_related_projects( string $exclude_slug = '', int $limit = 3, string $cat = '' ): array {
	$out = [];
	foreach ( sarjeet_projects() as $p ) {
		if ( ( $p->slug ?? '' ) === $exclude_slug ) continue;
		if ( $cat !== '' && ( $p->cat ?? '' ) !== $cat ) continue;
		$out[] = $p;
		if ( count( $out ) >= $limit ) break;
	}
	// If we don't have enough in the same category, top up from any.
	if ( count( $out ) < $limit ) {
		foreach ( sarjeet_projects() as $p ) {
			if ( ( $p->slug ?? '' ) === $exclude_slug ) continue;
			if ( in_array( $p, $out, true ) ) continue;
			$out[] = $p;
			if ( count( $out ) >= $limit ) break;
		}
	}
	return $out;
}

/**
 * Project-detail virtual page: ?project_view=<slug> loads our custom template
 * without requiring a CPT post. Works for the seed-data fallback used in
 * sarjeet_projects().
 */
add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'project_view';
	$vars[] = 'view';
	return $vars;
} );

add_filter( 'template_include', function ( $template ) {
	$slug = get_query_var( 'project_view' );
	if ( $slug ) {
		$candidate = locate_template( 'template-project-detail.php' );
		if ( $candidate ) return $candidate;
	}
	$view = get_query_var( 'view' );
	if ( $view === 'all-projects' ) {
		$candidate = locate_template( 'template-all-projects.php' );
		if ( $candidate ) return $candidate;
	}
	if ( $view === 'all-clients' ) {
		$candidate = locate_template( 'template-all-clients.php' );
		if ( $candidate ) return $candidate;
	}
	if ( $view === 'contact' ) {
		$candidate = locate_template( 'template-contact.php' );
		if ( $candidate ) return $candidate;
	}
	if ( in_array( $view, [ 'privacy', 'terms', 'disclaimer', 'cookies' ], true ) ) {
		$candidate = locate_template( 'template-legal.php' );
		if ( $candidate ) return $candidate;
	}
	// Unknown ?view= value → 404 (don't silently fall back to home, hurts SEO)
	if ( ! empty( $view ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
	return $template;
} );

function sarjeet_project_categories(): array {
	$names = [];
	$terms = get_terms( [ 'taxonomy' => 'project_category', 'hide_empty' => false ] );
	if ( ! is_wp_error( $terms ) && $terms ) {
		foreach ( $terms as $t ) {
			$names[] = $t->name;
		}
	}
	if ( ! $names ) {
		$names = [ 'Sewerage', 'Water Supply', 'Urban' ];
	}
	return array_merge( [ 'All' ], $names );
}
