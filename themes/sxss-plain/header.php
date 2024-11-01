<html>

<head>
    
    <title>sxss Plain</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    
    <link href='http://fonts.googleapis.com/css?family=Alef' rel='stylesheet' type='text/css'>
    
    <link href='style.css' rel='stylesheet' type='text/css'>
    
    <?php wp_head(); ?>
    
</head>

<body <?php body_class(); ?>>
    
    <div id="top">

    	<?php $sxss_m_activated = function_exists('sxss_mobile_plugin'); ?>
    
        <?php if( true == $sxss_m_activated ) { ?>

        	<img class="settings-icon sxss-sidebar" src="https://cdn2.iconfinder.com/data/icons/flat-ui-icons-24-px/24/menu-24-512.png">
        
        <?php } ?>

        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
    
    </div>