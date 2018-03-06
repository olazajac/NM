<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */
 add_action( 'get_header', 'remove_storefront_sidebar' );
 function remove_storefront_sidebar() {
 	if ( is_woocommerce() ) {
 		remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
 	}
 }
 add_action( 'init', 'jk_remove_storefront_header_search' );

 function jk_remove_storefront_header_search() {
 remove_action( 'storefront_header', 'storefront_product_search', 40 );
 }

 add_action( 'init', 'removefromfooter' );

 function removefromfooter() {
remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
remove_action( 'storefront_footer', 'storefront_credit',         20 );
 }

 // Change number of columns per row
add_filter('loop_shop_columns', 'change_loop_columns', 999);
add_filter('storefront_loop_columns', 'change_loop_columns', 999);
function change_loop_columns() {
    return 4;
}

 // remove default sorting dropdown in StoreFront Theme

add_action('init','delay_remove');

function delay_remove() {
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
}



// remove FROM SINGLE PRODUCT

// add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {


    // unset( $tabs['reviews'] ); 			// Remove the reviews tab
    // unset( $tabs['additional_information'] );  	// Remove the additional information tab

    // return $tabs;

}

/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
					* @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */

			 /**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */


// add font
add_action('wp_enqueue_scripts', function () {
   wp_enqueue_style( 'raleway', 'https://fonts.googleapis.com/css?family=Raleway:300,400,700');
    // wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', [], false, 'all');
    wp_enqueue_style( 'pawel-style', get_stylesheet_directory_uri() . '/pablo-style.css', [], false, 'all');
    wp_enqueue_style( 'font', get_stylesheet_directory_uri() . '/fontstyle.css', [], false, 'all');
    wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/slick.css');
	  wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/slick-theme.css');
    wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/slick.min.js');
    wp_enqueue_style( 'lightbox', get_stylesheet_directory_uri() . '/lightbox.css');
    wp_enqueue_script( 'lightbox', get_stylesheet_directory_uri() . '/lightbox.js');
});



function sv_remove_product_page_skus( $enabled ) {
    if ( ! is_admin() && is_product() ) {
        return false;
    }

    return $enabled;
}
add_filter( 'wc_product_sku_enabled', 'sv_remove_product_page_skus' );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
	    $image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<img src="' . $image . '" alt="' . $cat->name . '" />';
		}
	}
}



add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 90;
  return $cols;
}
