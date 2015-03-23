<div class="acf-rpw-columns-3">
	<?php
	echo parent::gti( 'tu', __( 'Title', 'acf_rpw' ) );
	echo parent::gti( 'css', __( 'CSS Class', 'acf_rpw' ) );
	echo parent::gtc( 'is', __( 'Ignore sticky posts', 'acf_rpw' ), array( 'ignore' => __( 'Ignore', 'acf_rpw' ) ) );
	echo parent::gti( 's', __( 'Search Keyword', 'acf_rpw' ), __( 'If specified it will limit posts satisfying the search query.', 'acf_rpw' ) );
	echo parent::gti( 'ex', __( 'Exclude', 'acf_rpw' ), __( 'Specify comma separated post ids.', 'acf_rpw' ) );
	echo parent::gtc( 'dd', __( 'Display Date', 'acf_rpw' ), array( __( 'Display Date', 'acf_rpw' ) ) );
	echo parent::gti( 'df', __( 'Date Format', 'acf_rpw' ), __( 'Specify any custom date format - <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">reference</a>.', 'acf_rpw' ) );
	echo parent::gtc( 'dr', __( 'Date Relative', 'acf_rpw' ), array( 'Date Relative' ), __( 'Checked - ignores the date format. Displays date in relateive format ex: 2 minutes ago.', 'acf_rpw' ) );
	echo parent::gti( 'ds', __( 'Date Start', 'acf_rpw' ), __( 'Start date of posts to render. Posts during that day are not included.', 'acf_rpw' ), 'picker' );
	echo parent::gti( 'de', __( 'Date End', 'acf_rpw' ), __( 'End date of posts to render. Posts during that day are not included.', 'acf_rpw' ), 'picker' );
	echo parent::gti( 'pass', __( 'Password', 'acf_rpw' ), __( 'If not empty, only post with specific password will be shown.', 'acf_rpw' ) );
	echo parent::gtc( 'hp', __( 'Show password protected posts only?', 'acf_rpw' ), array( __( 'Has Password', 'acf_rpw' ) ) );
	echo parent::gtc( 'ep', __( 'Exclude password protected posts?', 'acf_rpw' ), array( __( 'No Password', 'acf_rpw' ) ), __( 'Has lowest priority over the other password fields!', 'acf_rpw' ) );
	// not needed without specific time echo parent::gtc(   'di', 'Date Inclusive', 'If set includes the start and end posts in the loop.' , array( 'include' => 'include' ) );
	?>
</div>

<div class="acf-rpw-columns-3">

	<?php
	// print the Post Types Checkboxes
	echo parent::gtc( 'pt', __( 'Post Types', 'acf_rpw' ), array_combine( get_post_types( array( 'public' => true ), 'names' ), get_post_types( array( 'public' => true ), 'names' ) ) );

	// print the post formats checkboxes
	if ( current_theme_supports( 'post-formats' ) ):
		$post_formats = get_theme_support( 'post-formats' );
		if ( is_array( $post_formats[0] ) ):
			array_push( $post_formats[0], 'standard' );
			echo parent::gtc( 'pf', __( 'Post Formats', 'acf_rpw' ), array_combine( $post_formats[0], $post_formats[0] ), __( 'Displays specific or multiple post formats', 'acf_rpw' ) );
		endif;
	endif;

	// print the post statuses
	echo parent::gtc( 'ps', __( 'Post Statuses', 'acf_rpw' ), array_combine( get_available_post_statuses(), get_available_post_statuses() ) );
	// allow inputting authors
	echo parent::gti( 'aut', __( 'Authors', 'acf_rpw' ), __( 'Comma separated list of author ids. Ex. 1,2,3,4', 'acf_rpw' ) );
	echo parent::gts( 'ord', __( 'Order', 'acf_rpw' ), array( 'ASC' => __( 'Ascending', 'acf_rpw' ), 'DESC' => __( 'Descending', 'acf_rpw' ) ) );
	?>
	<?php
	echo parent::gts( 'orderby', __( 'Orderby', 'acf_rpw' ), array(
		'ID' => 'ID',
		'author' => 'Author',
		'title' => 'Title',
		'date' => 'Date',
		'modified' => 'Modified',
		'rand' => 'Random',
		'comment_count' => 'Comment Count',
		'menu_order' => 'Menu Order',
		'meta_value' => 'Meta Value',
		'meta_value_num' => __( 'Meta Value Numeric', 'acf_rpw' ) ), __( 'If meta order is specified the next field cannot be empty.', 'acf_rpw' ) );
	echo parent::gti( 'mk', __( 'Meta Key', 'acf_rpw' ), __( 'Fetch only posts having the Meta Key. Required if Meta Value or Meta Value Numeric was selected above.', 'acf_rpw' ) );
	?>


	<?php
	// obtain the categories list
	$categories = array();
	foreach ( get_terms( 'category' ) as $cat ) {
		$categories[$cat->term_id] = $cat->name;
	}
	echo parent::gtc( 'ltc', __( 'Limit to Category', 'acf_rpw' ), $categories );
	?>

	<?php
	// obtain the categories list
	$tags = array();
	foreach ( get_terms( 'post_tag' ) as $tag ) {
		$tags[$tag->term_id] = $tag->name;
	}

	echo parent::gtc( 'lttag', __( 'Limit to Tag', 'acf_rpw' ), $tags );
	echo parent::gti( 'ltt', __( 'Limit to taxonomy', 'acf_rpw' ), __( 'Ex: category=1,2,4&amp;post-tag=6,12.', 'acf_rpw' ) );
	echo parent::gts( 'ltto', __( 'Operator', 'acf_rpw' ), array( 'IN' => __( 'IN', 'acf_rpw' ), 'NOT IN' => __( 'NOT IN', 'acf_rpw' ) ), __( '"IN" includes posts from the taxonomies, NOT IN excludes posts from these taxonomies.', 'acf_rpw' ) );
	?>
</div>

<div class="acf-rpw-columns-3 acf-rpw-column-last">

	<?php echo parent::gti( 'np', __( 'Number of posts to show', 'acf_rpw' ), 'Use -1 to list all posts.' ); ?>
	<?php echo parent::gti( 'ns', __( 'Number of posts to skip', 'acf_rpw' ), 'Ignored if -1 is specified above.' ); ?>
	<?php
	// thumbnail related settings
	if ( current_theme_supports( 'post-thumbnails' ) ) {
		?>
		<div class="small">
			<?php
			parent::gtc( 'dth', __( 'Display Thumbnail', 'acf_rpw' ), array( 'display' => __( 'Display', 'acf_rpw' ) ) );
			parent::gti( 'thh', __( 'Thumbnail Height', 'acf_rpw' ) );
			parent::gti( 'thw', __( 'Thumbnail Width', 'acf_rpw' ) );
			parent::gts( 'tha', __( 'Thumbnail Alignment', 'acf_rpw' ), array(
				'acf-rpw-left' => __( 'Left', 'acf_rpw' ),
				'acf-rpw-right' => __( 'Right', 'acf_rpw' ),
				'acf-rpw-middle' => __( 'Middle', 'acf_rpw' )
					)
			);
			?>
		</div>
		<?php
		parent::gti( 'dfth', __( 'Default Thumbnail', 'acf_rpw' ), 'Specify full, valid image URL here. Ex: http://placehold.it/50x50/f0f0f0/ccc. All of the above apply to thumbnails but not to ACF image field type. Use CSS "acf-img" class to reference these.' );
		?>

	<?php } ?>
	<?php
	echo parent::gtc( 'excerpt', __( 'Show excerpt', 'acf_rpw' ), array( 'ignore' => __( 'Ignore', 'acf_rpw' ) ) );
	echo parent::gti( 'el', __( 'Excerpt Length', 'acf_rpw' ), 'Limits the excerpt to specified number of words.' );
	echo parent::gtc( 'is', __( 'Display Readmore', 'acf_rpw' ), array( __( 'Readmore', 'acf_rpw' ) ) );
	?>
<?php echo parent::gti( 'rt', __( 'Readmore text', 'acf_rpw' ), 'Leave empty for default "... Continue Reading" text. If full excerpt is printed, this text will not appear.' ); ?>
</div>

<div class="clear"></div>
<div class="acf-rpw-block">
	<?php
	echo parent::gt( 'before', __( 'HTML or text before each post.', 'acf_rpw' ), __( 'You can use any HTML and meta / ACF keys here. [acf field_key] will render the corresponding ACF field\'s value. Meta can be obtained via [meta field_key].', 'acf_rpw' ) );
	echo parent::gt( 'after', __( 'HTML or text after each post.', 'acf_rpw' ), __( 'You can use any HTML and meta / ACF keys here. [acf field_key] will render the corresponding ACF field\'s value. Meta can be obtained via [meta field_key].', 'acf_rpw' ) );

	echo parent::gt( 'before_posts', __( 'HTML or text before the whole loop.', 'acf_rpw' ), __( 'You can use any HTML here, the markup appears after the widget container opening and after the title.', 'acf_rpw' ) );
	echo parent::gt( 'after_posts', __( 'HTML or text after the whole loop.', 'acf_rpw' ), __( 'You can use any HTML here, the markup appears before the widget container closing.', 'acf_rpw' ) );
	echo parent::gtc( 'default_styles', __( 'Use Default Styles', 'acf_rpw' ), array( 'default' ) );
	echo parent::gt( 'custom_css', __( 'Custom CSS', 'acf_rpw' ), __( 'Disabling default CSS will let you type in any CSS here.', 'acf_rpw' ) );
	?>
</div>