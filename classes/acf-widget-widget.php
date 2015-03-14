<?php

/**
 * Adds Foo_Widget widget.
 */
class ACF_Rpw_Widget extends Widget_Base {

	/**
	 * Limit the excerpt lenght
	 * @type INT
	 */
	public static $el = 55;

	/**
	 * Custom readmore text
	 * @type STRING
	 */
	public static $rt = '';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		$this->text_fields = array( 'css', 'tu', 'ex', 's', 'dd', 'df', 'ds', 'de', 'aut', 'mk', 'ltt', 'np', 'ns', 'thh', 'thw', 'dfth', 'el', 'rt', 'pass' );
		$this->text_areas = array( 'before', 'after', 'before_posts', 'after_posts', 'custom_css' );
		$this->checkboxes = array( 'is', 'ds', /* not needed without specific time'di', */ 'dr', 'dth', 'pt', 'pf', 'ps', 'ltc', 'lttag', 'excerpt', 'is', 'default_styles', 'hp', 'ep' );
		$this->select_fields = array( 'ord', 'orderby', 'ltto', 'tha' );

		parent::__construct(
				'acf_rpw', // Base ID
				__( 'ACF Recent Posts Widget', 'acf_rpw' ), // Name
				array(
			'description' => __( 'Advanced Recent Posts Widget with ACF and meta fields support.', 'acf_rpw' ),
			'class' => 'acf-rpw', ), array(
			'width' => 750,
			'height' => 350
				) // Args
		);
	}

	/**
	 * @to be hooked
	 */
	public function excerpt_length() {
		return ( int ) ACF_Rpw_Widget::$el;
	}

	/**
	 * @to be hooked
	 */
	public function excerpt_more() {
		$link = '';
		if ( !empty( ACF_Rpw_Widget::$rt ) ) {
			$link = ' <a href="' . get_permalink() . '" class="more-link">' . esc_attr( ACF_Rpw_Widget::$rt ) . '</a>';
		}
		return $link;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {

// obtain the initially sanitized variables
		$instance = $this->_sanitize_data( $instance, $instance );

		extract( $instance );
// get the query args
		$query_args = $this->_get_query_args( $instance );

		$cache = array();
		if ( !$this->is_preview() ) {
			$cache = wp_cache_get( 'widget_recent_posts', 'widget' );
		}

		if ( !is_array( $cache ) ) {
			$cache = array();
		}

		if ( !isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $tu, $instance, $this->id_base );

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', $query_args ) );

		if ( $r->have_posts() ) :
			?>
			<?php echo $args['before_widget']; ?>
			<?php
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			// enqueue default widget styles
			if ( isset( $default_styles ) ) {
				wp_enqueue_style( 'acf-rpw-main' );
			}
// If the default style is disabled then use the custom css if it's not empty.
			if ( !isset( $default_styles ) && !empty( $custom_css ) ) {
				echo '<style>' . $custom_css . '</style>';
			}
			?>
			<div class="acf-rpw-block <?php echo $css; ?> <?php echo isset( $default_styles ) ? 'acf-rpw-default' : ''; ?>">
				<?php if ( !empty( $before_posts ) ): ?>
					<div class="acf-rpw-before-whole">
						<?php echo htmlspecialchars_decode( $before_posts ); ?>
					</div>
				<?php endif; ?>
				<ul class="acf-rpw-ul">
					<?php while ( $r->have_posts() ) : $r->the_post(); ?>
						<li class="acf-rpw-li acf-rpw-clearfix">
							<?php if ( has_post_thumbnail() ): ?>
								<a class="acf-rpw-img" rel="bookmark">
									<?php
									$thumb_id = get_post_thumbnail_id(); // Get the featured image id.
									$img_url = wp_get_attachment_url( $thumb_id ); // Get img URL.
									// Display the image url and crop using the resizer.
									$image = acf_rpwe_resize( $img_url, $thw, $thh, true );
									if ( $image ):
										?>
										<image src="<?php echo esc_url( $image ); ?>" class="<?php echo esc_attr( $tha ); ?> acf-rpw-thumb" />
										<?php
									else :
										echo get_the_post_thumbnail( get_the_ID(), array( $thw, $thh ), array(
											'class' => $tha . ' acf-rpw-thumb',
											'alt' => esc_attr( get_the_title() )
												)
										);
									endif;
									?>
								</a>
							<?php elseif ( isset( $dfth ) and ! empty( $dfth ) ): ?>
								<a class="acf-rpw-img" rel="bookmark">
									<image src="<?php echo esc_url( $dfth ); ?>" class="<?php echo esc_attr( $tha ); ?> acf-rpw-thumb" />
								</a>
							<?php endif; ?>
							<h3 class="acf-rpw-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php get_the_title() ? the_title() : the_ID(); ?></a></h3>
							<?php
							// show the date
							if ( isset( $ds ) ):
								?>
								<time class="acf-rpw-time published" datetime="<?php echo get_the_date( 'c' ); ?>"><?php
									// if date relative is to be displayed
									if ( isset( $dr ) ):
										echo sprintf( __( '%s ago', 'acf_rpw' ), human_time_diff( get_the_date( 'U' ), current_time( 'timestamp' ) ) );
									else:
										the_time( isset( $df ) ? $df : ''  );
									endif;
									?></time>
								<?php
							endif;

							// before each post
							if ( !empty( $before ) ):
								?>
								<div class="acf-rpw-before">
									<?php
									echo apply_filters( 'acp_rwp_before', htmlspecialchars_decode( $before ), $instance, $this->id_base );
									?>
								</div>
								<?php
							endif;
							// optionally print the excerpt
							if ( !isset( $excerpt ) ):
								// define custom excerpt length
								if ( isset( $el ) and ! empty( $el ) and is_numeric( $el ) ) {
									ACF_Rpw_Widget::$el = $el;
									add_filter( 'excerpt_length', array( __CLASS__, 'excerpt_length' ), 999 );
								}
								// define custom excerpt more
								if ( isset( $is ) ) {
									ACF_Rpw_Widget::$rt = $rt;
									// make sure custom filter is hooked and not default excerpt is used
									if ( isset( $rt ) and ! empty( $rt ) ) {
										add_filter( 'excerpt_more', array( __CLASS__, 'excerpt_more' ), 999 );
									}
								} else {
									// hide the excerpt
									add_filter( 'excerpt_more', array( __CLASS__, 'excerpt_more' ), 999 );
								}
								?>
								<div class="acf-rpw-excerpt">
									<?php
									// display the excerpt
									echo the_excerpt();
									?>
								</div>
								<?php
								// remove custom excerpt length
								if ( isset( $el ) and ! empty( $el ) and is_numeric( $el ) ) {
									remove_filter( 'excerpt_length', array( __CLASS__, 'excerpt_length' ), 999 );
								}
								// remove custom excerpt more
								remove_filter( 'excerpt_more', array( __CLASS__, 'excerpt_more' ), 999 );
							endif;
							// after each post
							if ( !empty( $after ) ):
								?>
								<div class="acf-rpw-after"> 
									<?php
									echo apply_filters( 'acp_rwp_after', htmlspecialchars_decode( $after ), $instance, $this->id_base );
									?>
								</div>
								<?php
							endif;
							?>
						</li>
					<?php endwhile;
					?>
				</ul>
				<?php if ( !empty( $after_posts ) ): ?>
					<div class="acf-rpw-after-loop">
						<?php echo htmlspecialchars_decode( $after_posts ); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php echo $args['after_widget']; ?>
			<?php
// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;

		if ( !$this->is_preview() ) {
			$cache[$args['widget_id']] = ob_get_flush();
			wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
		$this->form_instance = $instance;
		include( ACF_RWP_INC_PATH . 'form.php' );
	}

	/**
	 * Get query arguments for the WP_Query
	 * 
	 * @param ARRAY_A $instance
	 * @return ARRAY_A
	 */
	public function _get_query_args($instance) {

		extract( $instance );
// number of posts to show
		if ( isset( $np ) and ! empty( $np ) ) {
			$query_args['posts_per_page'] = ( int ) $np;
		}

// offset the posts 
		if ( isset( $ns ) and ! empty( $ns ) ) {
			$query_args['offset'] = ( int ) $ns;
		}

// search by keyword 
		if ( isset( $s ) and ! empty( $s ) ) {
			$query_args['s'] = $s;
		}

// exclude certain posts
		if ( isset( $ex ) and ! empty( $ex ) ) {
			$query_args['post__not_in'] = explode( ',', $ex );
		}

// show posts from certain authors only
		if ( isset( $aut ) and ! empty( $aut ) ) {
			$query_args['author__in'] = explode( ',', $aut );
		}

// Date Related Parameters
		if ( isset( $ds ) and ! empty( $ds ) ) {
			$query_args['date_query'] = array(
				array(
					'after' => $ds,
				),
			);
		}

		if ( isset( $de ) and ! empty( $de ) ) {
			if ( isset( $ds ) and ! empty( $ds ) ) {
// if the date start has already been set, merge the array with new parameters
				$query_args['date_query'][0] = array_merge(
						$query_args['date_query'][0], array(
					'before' => $de, )
				);
			} else {
// create new parameters
				$query_args['date_query'] = array(
					array(
						'before' => $de,
					),
				);
			}
		}

// Do we want to include the posts from the specified period?
		if ( isset( $query_args['date_query'] ) and ! empty( $query_args['date_query'] ) and isset( $di ) ) {
			$query_args['date_query'][0]['inclusive'] = true;
		}

// password parameters
		if ( isset( $pass ) and ! empty( $pass ) ) {
			$query_args['post_password'] = $pass;
		}

// exclude password protected posts
		if ( isset( $ep ) ) {
			$query_args['has_password'] = false;
		}

// render only password protected posts
		if ( isset( $hp ) ) {
			$query_args['has_password'] = true;
		}

// meta key parameter
		if ( isset( $mk ) and ! empty( $mk ) ) {
			$query_args['meta_key'] = $mk;
		}


// add post type parameters
		if ( isset( $pt ) ) {
			$query_args['post_type'] = array();
			foreach ( $pt as $post ) {
				$query_args['post_type'][] = $post;
			}
		}

// add post format parameter
		if ( isset( $pf ) ) {
			$formats = array();
// get all available formats
			$available_formats = get_theme_support( 'post-formats' );

// if the standard post lies within the arguments
// then run extra check as it's not defined inside the query arguments
			if ( in_array( 'standard', $pf ) ) {
// which means that we have selected not only
// standard post type
				$terms = array_diff( $available_formats[0], $pf );
				$terms = array_map( function($term) {
					return 'post-format-' . $term;
				}, $terms );

				$query_args['tax_query'] = array( array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => $terms,
						'operator' => 'NOT IN',
					) );
			} else {
// let's simply output all post formats present
				$formats = array_map( function($term) {
					return 'post-format-' . $term;
				}, $pf );

				$query_args['tax_query'] = array( array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => $formats,
					) );
			}
		}

// add post status parameter
		if ( isset( $ps ) ) {
			$query_args['post_status'] = array();
			foreach ( $ps as $status ) {
				$query_args['post_status'][] = $status;
			}
		}

// add category parameter
		if ( isset( $ltc ) ) {
			$query_args['category__in'] = array();
			foreach ( $ltc as $cat ) {
				$query_args['category__in'][] = $cat;
			}
		}

// add post tag parameter
		if ( isset( $lttag ) ) {
			$query_args['tag__in'] = array();
			foreach ( $lttag as $tag ) {
				$query_args['tag__in'][] = $tag;
			}
		}

		if ( isset( $ltt ) and ! empty( $ltt ) ) {
			$query_args['taxonomy'] = array();
		}

		/**
		 * Taxonomy query.
		 * Prop Miniloop plugin by Kailey Lampert.
		 * and Recent_Posts_Widget_Extended
		 */
		if ( isset( $ltt ) and ! empty( $ltt ) ) {

			parse_str( $ltt, $taxes );

			$operator = 'IN';
			if ( isset( $ltto ) ) {
				$operator = $ltto;
			}
			$tax_query = array();
			foreach ( array_keys( $taxes ) as $k => $slug ) {
				$ids = explode( ',', $taxes[$slug] );
				$tax_query[] = array(
					'taxonomy' => $slug,
					'field' => 'id',
					'terms' => $ids,
					'operator' => $operator
				);
			}

			$query_args['tax_query'] = $tax_query;
		}


// add order parameter 
		if ( isset( $ord ) ) {
			$query_args['order'] = $ord;
		}

// add orderby parameter
		if ( isset( $orderby ) ) {
			$query_args['orderby'] = $orderby;
		}

		return $query_args;
	}

}
