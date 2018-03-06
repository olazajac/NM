<?php //Template Name: Front ?>


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

			<?php echo do_shortcode("[smartslider3 slider=2]"); ?>
			<?php echo do_shortcode("[wpcs id=119]"); ?>










		</main><!-- #main -->








	</div><!-- #primary -->


</div><!-- .col-full -->




<div class="aaa" style="background-color:#f1f1f1; display:flex; padding-top: 40px;">
	<div class="lightgrey" style="display:inline-block; max-width: 66.4989378333em;
    margin-left: auto;
    margin-right: auto;">
			  <div class="semi-main">
			  	<div class="semi semi-image">
			  		<img src="http://zaionc.vot.pl/WP/wp-content/uploads/2018/01/lewe.jpg" alt="">
			  	</div>
					<div class="semi semi-content">
						<h2><?php echo get_post_field('naglowek_lewy', 124); ?></h2>
						<p><?php echo get_post_field('tekst_lewy', 124); ?></p>

					</div>
			  </div>
				<div class="semi-main">
			  	<div class="semi semi-image">
					<img class="bead-1" src="http://zaionc.vot.pl/WP/wp-content/uploads/2018/01/bead-1.png" alt="">
			  		<img src="http://zaionc.vot.pl/WP/wp-content/uploads/2018/01/prawe.jpg" alt="">
					<img class="bead-2" src="http://zaionc.vot.pl/WP/wp-content/uploads/2018/01/bead-2.png" alt="">
					<img class="bead-3" src="http://zaionc.vot.pl/WP/wp-content/uploads/2018/01/bead-3.png" alt="">
			  	</div>
					<div class="semi semi-content">
						<h2><?php echo get_post_field('naglowek_prawy', 124); ?></h2>
						<p><?php echo get_post_field('tekst_prawy', 124); ?></p>

					</div>
			  </div>

			</div>

</div>
























<div class="mban">
	<div class="col-full" style="display:flex;margin:25px auto;">

<style media="screen">
	.banh{
		font-family: raleway;
text-transform: uppercase;
font-size: 20px;
text-align: center;
background-color: white;
z-index: 9;
display: inline-block;
padding: 0px 10px;
	}
	.liner{
z-index: -1;
display: relative;
background-color: black;
height: 1px;
position: relative;
top: -29px;
	}
	.banhead{
		display:flex;
		justify-content: center;
	}
</style>
<div class="mban ban1" style="margin-right:20px;">
<div class="banhead">
<h3 class="banh">ZOBACZ KATALOG</h1>

</div>
<div class="liner">

</div>

		<img src="http://localhost/SITES/wooo/wp-content/uploads/2018/01/tcol.jpg" alt="">
</div>

<div class="mban ban1" style="margin-right:20px;">
<div class="banhead">
<h3 class="banh">PRESS & MEDIA</h1>

</div>
<div class="liner">

</div>
<a href="<?php echo get_site_url(); ?>/press-and-media/"> <img src="http://localhost/SITES/wooo/wp-content/uploads/2018/01/med.jpg" alt=""> </a>

</div>



</div></div>















<?php
do_action( 'storefront_sidebar' );


get_footer();

?>


<?php
