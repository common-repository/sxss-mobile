<?php get_header(); ?>

	<?php if ( have_posts() ) : ?>

		<?php $i = 0; ?>

	    <?php while ( have_posts() ) : the_post(); ?>

	    	<?php 

	    		if( $i % 2 == 0 ) $sxss_even = "even";

	    		else $sxss_even = "odd";

	    	?>

	    	<?php if( true == has_post_thumbnail() ) { ?>

	    	<a href="<?php the_permalink(); ?>?mobile" title="<?php the_time('j. F Y'); ?>">

		 		<div class="homethumb home<?php echo $sxss_even; ?>" style="background-image: url('<?php echo sxss_plain_get_featured_image(); ?>');">

		 			<div class="overlay">

		            	<h2><?php the_title(); ?></h2>

		            </div>
  
		        </div>

		    </a>

	        <?php } else { ?>

	    	<a href="<?php the_permalink(); ?>?mobile" title="<?php the_time('j. F Y'); ?>">

		 		<div class="homethumb homewithout home<?php echo $sxss_even; ?>">

		            	<h2><?php the_title(); ?></h2>

		        </div>

		    </a>

	        <?php } ?>

	        <?php $i++; ?>

	    <?php endwhile; ?>

	<?php else : ?>

	    <h1 class="nothingfound"><?php echo __('Noting found.', 'sxss_droid'); ?></h1>

	<?php endif; ?>

	<br style="clear: both;" />

<?php get_footer(); ?>