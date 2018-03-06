<?php //Template Name: Press ?>



<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

get_header(); ?>



	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post();

				do_action( 'storefront_page_before' );

				get_template_part( 'content', 'page' );

				/**
				 * Functions hooked in to storefront_page_after action
				 *
				 * @hooked storefront_display_comments - 10
				 */
				do_action( 'storefront_page_after' );

			endwhile; // End of the loop. ?>




<style media="screen">
/* gallery */

.galleries {
	display:flex;
	flex-wrap:wrap;
}

/* .galleries img {
	height: auto;
		max-width: 84%;
		display: block;
} */

.galleryitem{
	width:30%;
	margin:5px;
}

</style>












	            <div class="galleries">
					<?php $images = get_field('gallery'); ?>
					<?php foreach ($images as $image):?>
					<div class="galleryitem">

	                    <a href="<?php echo $image['url']; ?>" data-lightbox="gallery" data-title="<?php echo $image['title']; ?>">
	                        <div class="galleryitemimg">
	                            <div class="galleryimgbox">
	                                <img src="<?php echo $image['url']; ?>"/>
	                                <div class="galleryoverflow">
	                                </div>
	                                 <div class="galleryoverflowtext">
	                                    <h3><?php echo $image['title']; ?></h3>

	                                    <p>
	                                        <?php echo $image['description']; ?>
	                                    </p>
	                                </div>
	                            </div>
	                        </div>
	                    </a>
	                </div>

					<?php endforeach;?>


	            </div>

	        </div>

	    </div>




















		</main><!-- #main -->
	</div><!-- #primary -->















<?php

get_footer();

?>


<?php
