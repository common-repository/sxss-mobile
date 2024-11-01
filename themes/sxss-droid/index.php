<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"?>
<html <?php language_attributes(); ?>>

<head>
    
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>> 
    
    <div id="header">
    
        <?php if( true == $sxss_m_activated ) { ?>

            <div id="menu">
            
                <img class="icon menu-trigger" id="search" src="<?php echo SXSS_M_PLUGIN_URL; ?>/resources/search.png">
                
                <img class="icon menu-trigger" id="settings" src="<?php echo SXSS_M_PLUGIN_URL; ?>/resources/settings.png">
            
            </div>

        <?php } ?>
        
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
    
    </div>
    
    <div id="categories">
    
        <?php echo sxss_droid_head_nav( $sxss_m_option["sxss_m_menu_header"] ); ?>
        
    </div>
    
    <div id="wrap">

        <?php if ( have_posts() ) : ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <div id="contentbox post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
                        <div class="post-header">
                            
                            <div class="date"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_date('d M. y'); ?></a></div>

                            <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                        
                        </div>
                    
                        <div class="post-content">
                            
                            <?php 

                                if ( has_post_thumbnail() )
                                {
                                    the_post_thumbnail('thumbnail');
                                } 
                                if ( true == has_excerpt() ) 
                                { 
                                    the_excerpt();
                                } 
                                else 
                                {
                                    the_content();
                                }
                            ?>

                            <?php wp_link_pages( ); ?>

                            <?php if( false == is_singular() ) { ?>

                                <p class="meta">

                                    <?php comments_popup_link( __( 'Leave a comment', 'sxss_droid' ), __( '1 Comment', 'sxss_droid' ), __( '% Comments', 'sxss_droid' ) ); ?>

                                </p>

                            <?php } ?>

                            <?php if( true == is_singular() ) { ?>

                                <p class="meta"><?php the_tags(__('Tags: ', 'sxss_droid'), ', ', ''); ?></p>
                            
                            <?php } ?>      

                        </div>
                        
                    </div>

                    <?php if( true == is_singular() ) comments_template(); ?>

                    <?php if( true == is_singular() ) comment_form(); ?>

                <?php endwhile; ?>

                
                <div id="pagelinks">

                    <?php posts_nav_link(); ?>

                </div>


        <?php else : ?>

            <h1 class="nothingfound"><?php echo __('Noting found.', 'sxss_droid'); ?></h1>

        <?php endif; ?>
    
    </div>
    
    <div id="footer">
        
            <a target="_blank" title="Wordpress.org" href="http://wordpress.org">Powered by: WordPress</a>
            <a target="_blank" title="sxss Mobile Theme" href="https://profiles.wordpress.org/sxss/">Theme: sxss Mobile</a>
        
    </div>
    
    <?php wp_footer(); ?>

</body>

</html>