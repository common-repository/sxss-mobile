<?php

	/*

		todo:

			.overlay custom color


	*/

	################################
	# Check for sxss Mobile Plugin #
	################################

	$sxss_m_activated = function_exists('sxss_mobile_plugin');

	# Check for sxss Mobile Plugin
	if( true == $sxss_m_activated )
	{
		# Get Theme Options
		$sxss_m_option = sxss_m_get_settings();

		# Enable Sidebar
		sxss_m_enable_sidebar();
	}
	else
	{
		$sxss_m_option["sxss_m_menu_header"] = false;
		$sxss_m_option["sxss_m_menu_sidebar"] = false;
		$sxss_m_option["sxss_m_theme_color"] = "33, 69, 131";

		function sxss_plain_register_menu() {
			register_nav_menus(
				array(
		    		'top-menu' => __( 'Header Menu', 'sxss_plain' )
		    	)
		  	);
		}

		add_action( 'init', 'sxss_plain_register_menu' );
	}
	
	# In Both Cases
	$sxss_m_option["theme_path"] = get_template_directory_uri();

	##########################
	# Custom Theme Functions #
	##########################

	if ( ! isset( $content_width ) ) $content_width = 900;

	function sxss_plain_get_menu_name_by_location($location)
	{
	    if( false == has_nav_menu( $location ) ) return false;

	    $menus = get_nav_menu_locations();

	    return wp_get_nav_menu_object( $menus[$location] )->name;
	}	

	function sxss_plain_head_nav( $menu )
	{
		global $sxss_m_activated;

		# If Plugin Is Activated
		if( false != $menu )
		{
			$menus = wp_get_nav_menu_items( $menu );

			$return = '';

			$i = 0;

			foreach( $menus as $menu )
			{
				if( 0 != $i ) $return .= ' | ';

				$return .= '<a href="' . $menu->url . '">' . $menu->title . '</a>';

				$i++;
			}

			return $return;
		}

		# If Plugin isn't Activated
		else
		{
			# Get menu name for specific location
			$menu_check = sxss_plain_get_menu_name_by_location( 'top-menu' );

			# If there is no menu for this location
			if( false == $menu_check )
			{
				return __('Please create a menu in the Dashboard ( Themes > Menu )','sxss_plain');
			}

			else
			{
				$menus = wp_get_nav_menu_items( $menu_check );

				$return = '';

				$i = 0;

				foreach( $menus as $menu )
				{
					if( 0 != $i ) $return .= ' | ';

					$return .= '<a href="' . $menu->url . '?mobile">' . $menu->title . '</a>';

					$i++;
				}

				return $return;
			}
		}

	}

	# Include Theme Style Sheet
	function sxss_plain_style()
	{
		wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	}

	add_action( 'wp_enqueue_scripts', 'sxss_plain_style' );

	# Enqueue Comment Reply Script
	if ( is_singular() ) wp_enqueue_script( "comment-reply" );

	# Featured Image Support
	add_theme_support( 'post-thumbnails' );

	function sxss_plain_get_featured_image()
	{
		global $post;

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );

		return $image[0];
	}

	function sxss_plain_header_image()
	{
		if ( true == is_singular() && true == has_post_thumbnail() )
		{
			$image = sxss_plain_get_featured_image();

			?>

			<style> #header { background-image: url('<?php echo $image; ?>'); } </style>

			<?php
		}
	}

	add_action( 'wp_head', 'sxss_plain_header_image' );

	# RSS Feeds
	add_theme_support( 'automatic-feed-links' );

	# Custom Theme Color
	function sxss_plain_custom_theme_color( )
	{
		global $sxss_m_option;

		echo '
			<style>

			#todotop {
			    background: rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 1) !important;
			}

			.overlay {
			    background: rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 0.5) !important;
			}

			.homewithout {
			    border: 1px solid rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 0.5 );
			}

			.post a {
				color: rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 0.5) !important;
			}

			.tags a {
				background: rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 0.15) !important;
			}

			.tags a:hover {
				background: rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 0.25) !important;
			}

			</style>
		';
	}

	add_action('wp_head', 'sxss_plain_custom_theme_color');

	# Admin Notice
	if( false == $sxss_m_activated )
	{
		function sxss_plain_activate_plugin_notice()
		{
		    echo '<div class="updated"><p>Hey! Plase install and / or activate the <a target="_blank" title="sxss Mobile" href="http://wordpress.org/plugins/sxss-mobile/">sxss Mobile</a> plugin to enable all features.</p></div>';
		}

		add_action('admin_notices', 'sxss_plain_activate_plugin_notice');
	}

	function my_comments_callback( $comment, $args, $depth ) 
	{
		$GLOBALS['comment'] = $comment;

		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment">

				<div class="comment-content"><?php comment_text(); ?></div>

				<p><?php echo "Comment authors age: ".get_comment_meta( $comment->comment_ID, 'age', true ); ?></p>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
			</div>
		</li>
		<?php
	}

?>