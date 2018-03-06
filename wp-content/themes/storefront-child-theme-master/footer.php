<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>


	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

		<ul class="footer-menu">
			<li> <a href="<?php echo get_site_url(); ?>/contact/"> KONTAKT</li></a>
			<a href="<?php echo get_site_url(); ?>/regulamin/">  <li>REGULAMIN</li>  </a>
			<a href="<?php echo get_site_url(); ?>/dostawa-i-platnosc/">  <li>DOSTAWA I PŁATNOŚC</li>  </a>
			<a href="<?php echo get_site_url(); ?>/zwroty/">  <li>ZWROTY</li>  </a>
			<a href="https://www.facebook.com/N.MILKA.jewellery/">  <li><img src="http://zaionc.vot.pl/WP/wp-content/uploads/2018/01/iconfb.jpg" alt=""></li> </a>
			<a href="https://www.instagram.com/nmilkajewellery/">  <li><img src="http://zaionc.vot.pl/WP/wp-content/uploads/2018/01/iconista.jpg" alt=""></li></a>





		</ul>

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			do_action( 'storefront_footer' ); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
