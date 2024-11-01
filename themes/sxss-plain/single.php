<?php get_header(); ?>

<?php if ( have_posts() ) : ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <div id="header">
            
            <div class="overlay">
        
                <div class="headercontent">
                    
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    
                    <span class="meta date">

                        <a href="<?php the_permalink(); ?>"><?php the_time('j. F Y'); ?></a>

                    </span>
                    
                    <span class="meta comments">

                        <?php comments_popup_link( __( 'Leave a comment', 'sxss_plain' ), __( '1 Comment', 'sxss_plain' ), __( '% Comments', 'sxss_plain' ) ); ?>

                    </span>
                
                </div>

            </div>
            
        </div>
        
        <div id="contentwrap">
        
            <div id="postID" class="post">
                
                <?php the_content(); ?>
                
                <p class="tags">
                    
                    <?php the_tags('', '', ''); ?>
                
                </p>
                
            </div>
        
        </div>

        <?php comments_template(); ?>

        <?php comment_form(); ?>

    <?php endwhile; ?>

<?php else : ?>

    <h1 class="nothingfound"><?php echo __('Noting found.', 'sxss_droid'); ?></h1>

<?php endif; ?>

<?php get_footer(); ?>