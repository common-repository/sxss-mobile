<?php
/*
Plugin Name: sxss Mobile
Plugin URI: http://sxss.nw.am
Description: Enable mobile themes for mobile devices
Author: sxss
Version: 0.3 Beta
*/

// word on todo list
// 3.4 	wp_is_mobile


// I18n
load_plugin_textdomain('sxss_m', false, basename( dirname( __FILE__ ) ) . '/languages' );

define( 'SXSS_M_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'SXSS_M_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// to check for in the theme
function sxss_mobile_plugin() {}

// selected theme
function sxss_m_theme() 
{
	return sxss_m_get_settings("sxss_m_theme");
}

// change theme
function sxss_m_change_theme()
{
	// only frontend
	if ( false == is_admin() )
	{
		// sxss-mobile sxss-mobile
 		add_filter('template', 'sxss_m_theme');
 		add_filter('stylesheet', 'sxss_m_theme');
	}
}

function sxss_m_options() {
	
	$options[] = "sxss_m_activated";
	$options[] = "sxss_m_menu_header";
	$options[] = "sxss_m_menu_sidebar";
	$options[] = "sxss_m_theme";
	$options[] = "sxss_m_theme_color";
	#$options[] = "sxss_m_include_cpt";

	return $options;
}

function sxss_m_themes()
{
	$themes["sxss-droid"] = "Droid";
	$themes["sxss-plain"] = "Plain";

	return $themes;
}

function sxss_m_themes_available()
{
	# List of sxss mobile themes
	$themes = sxss_m_themes();

	# List of installed themes
	foreach( wp_get_themes() as $key => $array )
	{
		$installed_themes[] = $key;
	}

	# List of sxss themes which are installed
	foreach( $themes as $slug => $name )
	{
		if( true == in_array( $slug, $installed_themes ) ) $return[$slug] = $themes[$slug];
	}

	return $return;
}



function sxss_m_get_theme_version( $slug )
{
	// get theme informations
	$theme = wp_get_theme( $slug );

	// if theme does exist return version number
	if ( $theme->exists() )
	{
		return $theme->get( 'Version' );
	}
	else
	{
		return false;
	}
}

function sxss_m_is_theme_installed( $slug )
{
	if( false == sxss_m_get_theme_version( $slug ) ) return false;
	else return true;
}

function sxss_m_theme_update_available( $slug )
{
	// check for version number of the installed theme
	$installed_version = sxss_m_get_theme_version( $slug );

	// if theme is installed
	if( false != $installed_version )
	{
		// get newest version number
		$file = SXSS_M_PLUGIN_PATH . 'themes/' . $slug . '/version.txt';

		// check if theme version file exist
		if( true == file_exists( $file ) )
		{
			// get newest theme version available
			$version_available = file_get_contents( $file );

			// compare theme numbers
			if( $installed_version != $version_available )
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	return false;
}

function sxss_m_save_settings()
{
	$options = sxss_m_options();

	foreach( $options as $option )
	{
		$save[$option] = $_POST[$option];
	}

	# Convert Hex to RGB
	$save["sxss_m_theme_color"] = sxss_m_hex2rgb( $save["sxss_m_theme_color"] );

	$save = serialize( $save );

	update_option( 'sxss_mobile', $save );
}

function sxss_m_get_settings( $option = false )
{
	$settings = unserialize( get_option('sxss_mobile') );

	if( false == $option ) return $settings;
	else return $settings[$option];
}

function sxss_m_get_menus( $option = false, $selected = false )
{
	# Get Wordpress Menus
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

	# Message if no menus were found
	if( 0 == sizeof($menus) ) return '<a href="' . admin_url( 'nav-menus.php' ) . '">' . __('Create a menu first.', 'sxss_m') . '</a>';

	# Return array if $option isn't set
	if( false == $option ) return $menus;

	# Return select box with name and id
	$return = '<select id="' . $option . '" name="' . $option . '">';

	# First option - notice
	$return .= '<option value="">' . __('Select a menu...', 'sxss_m') . '</option>';

	# List of menus
	foreach( $menus as $menu )
	{
		if( $menu->slug == $selected ) $mark = "selected";
		else $mark = "";

		$return .= '<option value="' . $menu->slug . '" ' . $mark . '>' . $menu->name . '</value>';
	}

	$return .= "</select>";

	return $return;
}

function sxss_m_menu_names_array()
{
	$menus = sxss_m_get_menus();

	foreach( $menus as $menu )
	{
		$return[$menu->slug] = $menu->name;
	}

	return $return;
}

function sxss_m_get_themes( $selected = "sxss-droid" )
{
	$themes = sxss_m_themes_available();

	if( 0 == sizeof( $themes ) ) return __('Please install one of our mobile themes.', 'sxss_m');

	$return = '<select name="sxss_m_theme">';

	foreach( $themes as $key => $value )
	{
		if( $selected == $key ) $mark = "selected";
		else $mark = "";

		$return .= '<option value="' . $key . '" ' . $mark . '>' . $value . '</option>';
	}

	$return .= "</select>";

	return $return;
}

function sxss_m_hex2rgb( $hex )
{
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) 
   {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } 
   else 
   {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }

   # $rgb = array($r, $g, $b);

   return "$r, $g, $b";
}

function sxss_m_rgb2hex($rgb)
{
	$rgb = explode( ',', $rgb );
	$hex = "#";
	$hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

	return $hex; // returns the hex value including the number sign (#)
}

function sxss_m_nice_themename( $name )
{
	$name = "sxss-" . strtolower( $name );

	return $name;
}

/* Theme Copy Function */
function sxss_m_theme_copy( $name )
{
	// todo: check for sxss_m_themes()

	// if your hoster disabled copy()
	// give install instructions on the settings pge
	if( false == function_exists('copy') )
	{
		// return statement
		$return["success"] = false;
		$return["error"] = "nocopy";
	}

	// source directory
	$source = SXSS_M_PLUGIN_PATH . '/themes/' . $name . '/';

	// check or source existance
	if( true == file_exists( $source ) )
	{
		// Destination folder
		$destination = get_theme_root() . '/' . $name . '/';

		// Default true
		$destination_exists = true;

		// Check if destination folder exists
		if( false == file_exists( $destination ) )
		{
			// if not, create it
			$destination_exists = mkdir( $destination, 0777 );
		}

		// if destination folder exists
		if( true == $destination_exists )
		{
			// get array of files in the source directory
			$files = scandir( $source );

			// copy each file
			foreach( $files as $file )
			{
				// dont copy directorys
				if( "." != $file{0})
				{
					// copy file from source to destination
					$copy = copy( $source . $file, $destination . $file);

					// if file cound't be copied
					if( false == $copy ) 
					{	
						$copyerror[] = $file;
					}
				}
			}
		}
	}

	if( true == isset( $copyerror ) )
	{
		// list the files
		$copyerror = implode(', ', $copyerror);

		// return statement
		$return["success"] = false;
		$return["error"] = __("These theme files couldn't be copied: ") . $copyerror;
	}
	else
	{
		return true;
	}
}


// settingspage
function sxss_m_settings() 
{
	// save settings
	if ( true == isset( $_POST['action'] ) && $_POST['action'] == 'update')
	{
		# Chck User Input
		if( 0 == sizeof( sxss_m_themes_available() ) ) $error[] = __('Please select a valid theme','sxss_m');
		elseif( false == array_key_exists( $_POST["sxss_m_theme"], sxss_m_themes_available() ) ) $error[] = __('Please select a valid theme','sxss_m');
		if( false == preg_match('/^#[a-f0-9]{6}$/i', $_POST["sxss_m_theme_color"]) ) $error[] = __('Please use valid HEX color','sxss_m');
		if( false == array_key_exists( $_POST["sxss_m_menu_header"], sxss_m_menu_names_array() ) ) $error[] = __('Please select a valid header menu','sxss_m');
		if( false == array_key_exists( $_POST["sxss_m_menu_sidebar"], sxss_m_menu_names_array() ) ) $error[] = __('Please select a valid sidebar menu','sxss_m');
		if( $_POST["sxss_m_activated"] != "true" && $_POST["sxss_m_activated"] != "false" ) $error[] = __('You can only activate or deactivate the plugin.','sxss_m');
		
		# No Errors
		if( false == isset($error) )
		{
			sxss_m_save_settings();

			$message = '<div id="message" class="updated fade"><p><strong>' . __('Settings updated', 'sxss_m') . '</strong></p></div>'; 
		}

		# If there are errors
		else
		{
			# start error message
			$message = '<div class="error"><p>';

			# display all error messages
			foreach( $error as $errmsg )
			{
				$message .= '<p>' . $errmsg . '</p>';
			}

			$message .= '</p></div>';
		}
	} 

	if( true == isset( $_POST['action'] ) && $_POST['action'] == "themecopy" )
	{
		sxss_m_theme_copy( $_POST["sxss-m-theme"] );
	}

	if( false == isset($message) ) $message = "";

	$settings = sxss_m_get_settings();

	# Include Custom Post Types
	#
	# if( "yes" == $settings["sxss_m_include_cpt"] ) $cb_include_cpt = "checked";
	# else $cb_include_cpt = "";
	#	<tr>
	#
	#		<td><input disabled class="sxss-checkbox" type="checkbox" name="sxss_m_include_cpt" value="yes" ' . $cb_include_cpt . '></td>
	#		<td>' . __('Include Custom Post Types', 'sxss_m') . '</td>
	#
	#	</tr>

	# For developement reasons
	# pre($settings);
	
	?>

	<style>

	table.sxss-m td {
		padding: 10px;
		border-bottom: 1px dotted #C0C0C0;
	}

	table.sxss-m input,
	table.sxss-m select,
	table.sxss-m textarea {
		padding: 5px;m
	}

	#sxss-mobile-themes img {
		display: none;
		border: 1px solid rgba( 0, 0, 0, 0.3 );
		width: 200px;
		height: 150px;
		margin: 0 10px 10px 0;
	}

	.install-theme {
		text-align: center;
		display: inline-block;
		margin: 0 30px 0 0;
	}

	.sxss-install-button {
		position: relative;
		left: -5px;
		top: 10px;
	}

	.sxss-mobile-priview {
		text-align: left;
		width: 133px;
		height: 250px;
		background: url("<?php echo SXSS_M_PLUGIN_URL; ?>/resources/nexus.png");
		overflow: hidden;
	}

	.sxss-mobile-priview img {
		width: 112px;
		height: 187px;
		position: relative;
		left: 8px;
		top: 30px;
	}

	.sxss-m-container {
		float: left;
		margin: 0 50px 30px 0;
	}

	.sxss-m-container h2 {
		margin-bottom: 20px;
	}

	</style>

	<script type="text/javascript">
		
		// farbtastic
		jQuery(document).ready(function() {
    
			jQuery("#sxss_m_theme_color_cp").hide();
    
			jQuery("#sxss_m_theme_color_cp").farbtastic("#sxss_m_theme_color");
    
			jQuery("#sxss_m_theme_color").click(function(){jQuery("#sxss_m_theme_color_cp").slideToggle()});
		});
	
	</script>

	<div class="wrap">

		<?php echo $message; ?>

		<div id="icon-options-general" class="icon32"><br /></div>

		<h2>sxss Mobile - Make your site mobile ready</h2>

		<div style="display: none;" id="message" class="updated"><p><strong>We have submitted our first theme to the Wordpress repository, but we have to wait for its approval.</strong><br />In the meantime, <a href="http://sxss.dreamhosters.com/sxss-droid.zip">download our theme here</a> and <a href="<?php echo admin_url('theme-install.php?tab=upload'); ?>">upload the zip file here</a> (don't activate it).</p></div>

		<div class="sxss-m-container">

			<h3><?php echo __('Mobile Settings', 'sxss_m'); ?></h3>

			<form method="post" action="">

				<table class="sxss-m">

					<tr>

						<td width="200">

							<select name="sxss_m_activated">

								<option value="true" selected><?php echo __('ON','sxss_m'); ?></option>
								<option value="false"><?php echo __('OFF','sxss_m'); ?></option>

							</select>

						</td>
						<td>

							<?php echo __('ON / OFF switch','sxss_m'); ?>

						</td>

					<tr>

						<td><?php echo sxss_m_get_themes( $settings['sxss_m_theme'] ); ?></td>
						<td><?php echo __('Select the theme', 'sxss_m');?></td>

					</tr>

					<tr>

						<td><input type="text" id="sxss_m_theme_color" name="sxss_m_theme_color" value="<?php echo sxss_m_rgb2hex( $settings["sxss_m_theme_color"] ); ?>" style="background: <?php echo sxss_m_rgb2hex( $settings["sxss_m_theme_color"] ); ?>;"><div id="sxss_m_theme_color_cp"></div></td>
						<td><?php echo __('Theme color', 'sxss_m'); ?></td>

					</tr>

					<tr>

						<td><?php echo sxss_m_get_menus( 'sxss_m_menu_header', $settings['sxss_m_menu_header'] ); ?></td>
						<td><?php echo __('Header menu', 'sxss_m');?></td>

					</tr>

					<tr>

						<td><?php echo sxss_m_get_menus( 'sxss_m_menu_sidebar', $settings['sxss_m_menu_sidebar'] ); ?></td>
						<td><?php echo __('Sidebar menu', 'sxss_m'); ?></td>

					</tr>

				</table>

				<input type="hidden" name="action" value="update" />

				<p><input type="submit" class="button-primary" value="<?php echo __('Save settings', 'sxss_m'); ?>" /></p>

			</form>

		</div>

		<?php #if( sizeof( sxss_m_themes_available() ) != sizeof( sxss_m_themes() ) ) { ?>

		<div class="sxss-m-container">

			<h3><?php echo __('sxss Mobile Themes', 'sxss_m'); ?></h3>

			<?php foreach( sxss_m_themes() as $slug => $name ) { ?>

				<?php 

					// If theme is not installed, show install button
					if( false == sxss_m_is_theme_installed( $slug ) ) $sxss_m_actionterm[$slug] = "Install";

					// if theme is installed, but outdated, show update button
					elseif( true == sxss_m_theme_update_available( $slug ) ) $sxss_m_actionterm[$slug] = "Update";

				?>

				<div id="sxss-m-theme-<?php echo $slug; ?>" class="install-theme">

					<div class="sxss-mobile-priview">

						<img src="<?php echo SXSS_M_PLUGIN_URL; ?>/themes/<?php echo $slug; ?>_screenshot.jpg">

					</div>

					<?php if( true == isset( $sxss_m_actionterm[$slug] ) ) { ?>

					<form method="post" action="">

						<input type="hidden" name="action" value="themecopy">

						<input type="hidden" name="sxss-m-theme" value="<?php echo $slug; ?>">

						<input type="submit" class="button-primary sxss-install-button" name="sxss_m_copy_theme_submit" value="<?php echo $sxss_m_actionterm[$slug]; ?> <?php echo $name; ?>">

					</form>

					<?php } ?>

				</div>

			<?php } ?>


		</div>

		<br style="clear: both;" />

		<p align="right"><a target="_blank" title="sxss Plugins on wordpress.org" href="https://profiles.wordpress.org/sxss/"><img src="<?php echo plugins_url( 'resources/sxss-plugins.png' , __FILE__ ); ?>"></a></p>

	</div>


	<?php

	function sxss_m_farbtastic()
	{
		wp_enqueue_style( 'farbtastic' );

		wp_enqueue_script( 'farbtastic' );
	}

	add_action('init', 'sxss_m_farbtastic');

}

// register settings page
function sxss_m_admin_menu()
{  
	add_theme_page(__('sxss Mobile', 'sxss_m'), __('sxss Mobile', 'sxss_m'), 9, 'sxss_m', 'sxss_m_settings');  
}  

add_action("admin_menu", "sxss_m_admin_menu"); 


##############################
# Theme: Sidebar             #
##############################

function sxss_m_enable_sidebar()
{
	function sxss_m_include_sidebar() {

		$js = SXSS_M_PLUGIN_URL. "/resources/sidebar.js";
		$css = SXSS_M_PLUGIN_URL. "/resources/sidebar.css";

		wp_enqueue_style( 'sxss-sidebar-css', $css );

		wp_enqueue_script('jquery');
		wp_enqueue_script( 'sxss-sidebar-js', $js, array(), '1.0.0', true );
	}

	add_action( 'wp_enqueue_scripts', 'sxss_m_include_sidebar' );

	function sxss_m_sidebar_html()
	{
		global $sxss_m_option;
		?>

		<div id="mobilemenu">
        
        	<form id="menusearch">
        
            	<input style="background: rgba( 0, 0, 0, 0.2 ) url('<?php echo SXSS_M_PLUGIN_URL; ?>/resources/search.png') no-repeat 0.3em 0.3em;" type="text" name="s" placeholder="Suche">
            
        	</form>
        
        	<?php wp_nav_menu( array( 'menu' => $sxss_m_option["sxss_m_menu_sidebar"] ) ); ?>
    
    	</div>

    	<?php
	}

	add_action( 'wp_footer' , 'sxss_m_sidebar_html' );
}

function sxss_m_register_sidebar_menu() 
{
	register_nav_menus(
		array(
	    	'sidebar-menu' => __( 'Sidebar Menu' )
	    )
	);
}

add_action( 'init', 'sxss_m_register_sidebar_menu' );

// this is where the magic happens
if( true == wp_is_mobile() || isset( $_GET["mobile"]) )
{
	$sxss_mobile = sxss_m_get_settings();

	if( "true" == $sxss_mobile["sxss_m_activated"] ) 
	{
		add_action('plugins_loaded','sxss_m_change_theme');
	}
}

?>