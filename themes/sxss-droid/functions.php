<?php

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

		function sxss_droid_register_menu() {
			register_nav_menus(
				array(
		    		'top-menu' => __( 'Header Menu', 'sxss_droid' )
		    	)
		  	);
		}

		add_action( 'init', 'sxss_droid_register_menu' );
	}
	
	# In Both Cases
	$sxss_m_option["theme_path"] = get_template_directory_uri();

	##########################
	# Custom Theme Functions #
	##########################

	if ( ! isset( $content_width ) ) $content_width = 900;

	function sxss_droid_get_menu_name_by_location($location)
	{
	    if( false == has_nav_menu( $location ) ) return false;

	    $menus = get_nav_menu_locations();

	    return wp_get_nav_menu_object( $menus[$location] )->name;
	}	

	function sxss_droid_head_nav( $menu )
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
			$menu_check = sxss_droid_get_menu_name_by_location( 'top-menu' );

			# If there is no menu for this location
			if( false == $menu_check )
			{
				return __('Please create a menu in the Dashboard ( Themes > Menu )','sxss_droid');
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
	function sxss_droid_style()
	{
		wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	}

	add_action( 'wp_enqueue_scripts', 'sxss_droid_style' );

	# Enqueue Comment Reply Script
	if ( is_singular() ) wp_enqueue_script( "comment-reply" );

	# Featured Image Support
	add_theme_support( 'post-thumbnails' );

	# RSS Feeds
	add_theme_support( 'automatic-feed-links' );

	# Custom Theme Color
	function sxss_droid_custom_theme_color( )
	{
		global $sxss_m_option;

		echo '
			<style>

			#header {
			    background: rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 1) !important;
			}

			.post,
			#wrap .page,
			#respond,
			.comment-body {
			    # border-top: 0.3em solid rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 0.5) !important;
			}

			.post-content a,
			.commentlist p a {
			    border-bottom: 0.1em dotted rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 0.8);
			}

			.sticky {
				border-color: rgba(' . $sxss_m_option["sxss_m_theme_color"] . ', 0.5) !important
			}

			</style>
		';
	}

	add_action('wp_head', 'sxss_droid_custom_theme_color');

	# Admin Notice
	if( false == $sxss_m_activated )
	{
		function sxss_droid_activate_plugin_notice()
		{
		    echo '<div class="updated"><p>Hey! Plase install and / or activate the <a target="_blank" title="sxss Mobile" href="http://wordpress.org/plugins/sxss-mobile/">sxss Mobile</a> plugin to enable all features.</p></div>';
		}

		add_action('admin_notices', 'sxss_droid_activate_plugin_notice');
	}
?>