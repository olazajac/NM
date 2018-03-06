<?php
/*
Plugin Name: Storefront Wishlist
Description: Allows users to add products to wish list
Version: 1.0.1
Plugin URI: https://pootlepress.com/freemius-testimonials
Author: Pootlepress
Author URI: https://pootlepress.com/
Domain: sfwl
@developer shramee.srivastav@gmail.com
*/

class Storefront_Wishlist {

	/** @var Storefront_Wishlist Instance */
	private static $_instance;
	protected $version;
	protected $app_url;
	protected $shop_icon_pos;
	protected $product_icon_pos;

	/**
	 * Storefront_Wishlist constructor.
	 */
	public function __construct() {

		$this->version = '1.0.1';

		add_action( 'init', array( $this, 'init' ) );
	}
//	protected $active;

	/**
	 * Get instance
	 * @return Storefront_Wishlist Instance
	 */
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initiates the plugin
	 * @action init
	 */
	public function init() {

		if ( class_exists( 'Storefront_Pro' ) ) {
			add_action( 'wp', array( $this, 'wp' ) ); // Init from settings

			add_filter( 'storefront_pro_fields', array( $this, 'fields' ) ); // Add settings fields

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) ); //Enqueue styles n scripts

			add_action( 'wp_footer', array( $this, 'footer' ) ); // Adds app iframe and toggle button
		}
	}

	/**
	 * Initiates plugin based on settings
	 * @action wp
	 */
	public function wp() {

		$this->app_url = 'https://links-list.firebaseapp.com/';

		$this->product_icon_pos = $this->get( 'sfwl-product-pos' ); // @TODO implement these
		$this->shop_icon_pos    = $this->get( 'sfwl-shop-pos' ); // @TODO implement these

		if ( defined( 'SFWL_DEBUG' ) && SFWL_DEBUG ) {
			$this->app_url = 'http://localhost:4200/';
		}

		$this->app_url .=
			'?site=' . $_SERVER['HTTP_HOST'] .
			'&hdbg=' . urlencode( $this->get( 'header-bg-color', '#fff' ) ) .
			'&hdtxt=' . urlencode( $this->get( 'pri-nav-text-color', '#333' ) );

		if ( is_product() ) {
			switch( $this->product_icon_pos ) {
				case 'left-img':
					add_action( 'woocommerce_product_thumbnails', array( $this, 'echo_add2wishlist' ), 99 );
					break;
				case 'fr-cart':
					add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'echo_add2wishlist' ) );
					break;
				default:
					add_filter( 'the_title', array( $this, 'title_add2wishlist' ) );
			}
		}

		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'a2c_add2wishlist' ) );
	}

	/**
	 * Get setting value for storefront pro settings
	 *
	 * @param string $id Setting id
	 * @param mixed|null $default
	 *
	 * @return mixed|null
	 */
	public function get( $id, $default = null ) {
		return get_sfp_mod( $id, $default );
	}

	/**
	 * Enqueues styles and scripts
	 * Localizes script
	 * @uses Storefront_Wishlist::get_styles()
	 */
	public function enqueue() {
		wp_enqueue_style( 'sfwl-front-css', plugin_dir_url( __FILE__ ) . '/assets/front.css', '', $this->version );

		wp_add_inline_style( 'sfwl-front-css', $this->get_styles() );

		wp_enqueue_script( 'sfwl-front-js', plugin_dir_url( __FILE__ ) . '/assets/front.js', [ 'jquery' ], $this->version );

		wp_localize_script( 'sfwl-front-js', 'sfwlData', [
			'host' => $_SERVER['HTTP_HOST'],
		] );
	}

	/**
	 * Generates styles from settings
	 * @return string CSS
	 */
	private function get_styles() {

		$css = 'a#sfwl-app-toggle{';
		$css .= 'color:' . $this->get( 'sfwl-app-icon-clr' ) . ';';
		$css .= 'background:' . $this->get( 'sfwl-app-icon-fill' ) . ';';
		$css .= 'font-size:' . $this->get( 'sfwl-app-icon-size' ) . 'px;';

		switch ( $this->get( 'sfwl-app-icon-pos' ) ) {
			case 'right':
				$css .= 'margin: auto 25px 25px auto;';
				break;
			case 'hide':
				$css .= 'display:none;';
				break;
			default:
		}

		$css .= '}';

		$css .= '.single-product .sfwl-a2w{';
		$css .= 'color:' . $this->get( 'sfwl-product-clr' ) . ';';
		$css .= 'font-size:' . $this->get( 'sfwl-product-size' ) . 'px;';
		$css .= '}';

		$css .= '.products .product .sfwl-a2w{';
		$css .= 'color:' . $this->get( 'sfwl-shop-clr' ) . ';';
		$css .= 'font-size:' . $this->get( 'sfwl-shop-size' ) . 'px;';
		if ( $this->shop_icon_pos == 'left-img' ) {
			$css .= 'position:absolute;top:16px;left:16px;';
		} else if ( $this->shop_icon_pos == 'right-img' ) {
			$css .= 'position:absolute;top:16px;right:53px;';
		}
		$css .= '}';

		return $css;
	}

	/**
	 * Changes excerpt length
	 * @return int Words in Excert
	 */
	public function excerpt_length() {
		return 25;
	}

	/**
	 * Returns add to wishlist button markup
	 * @return string HTML
	 */
	public function a2w_btn() {
		/** @var WC_Product $product */
		global $product;
		$price = strip_tags( wc_price( wc_get_price_to_display( $product ) ) . $product->get_price_suffix() );

		add_filter( 'excerpt_length', array( $this, 'excerpt_length', ), 999 );
		$desc = strip_tags( get_the_excerpt() );
		remove_filter( 'excerpt_length', array( $this, 'excerpt_length', ), 999 );

		$link = get_the_permalink();
		$product_attrs = [
			'id'      => "$_SERVER[HTTP_HOST]-" . get_the_ID(),
			'link'    => $link,
			'title'   => get_the_title(),
			'img'     => get_the_post_thumbnail_url( get_the_ID(), 'shop_catalog' ),
			'meta'    => [
				'Price' => $price,
			],
			'actions' => [
				'Add to cart' => add_query_arg( 'add-to-cart', get_the_ID(), $link ),
			],
			'desc'    => $desc,
		];

		ob_start();
		?>
		<a href='javascript:void(0)' onclick='sfwl.a2w( <?php echo json_encode( $product_attrs ); ?>, this )'
			 class="sfwl-a2w">
			<i class="fa fa-heart-o"></i> <span class="screen-reader-text">Add to wishlist</span>
		</a>

		<?php
		return ob_get_clean();

	}

	/**
	 * Outputs add to wishlist button
	 * @action woocommerce_product_thumbnails
	 */
	public function echo_add2wishlist() {
		echo $this->a2w_btn();
	}

	/**
	 * Adds add to wishlist icon near add to cart button
	 * @filter woocommerce_loop_add_to_cart_link
	 * @param string $html
	 * @return string
	 */
	public function a2c_add2wishlist( $html ) {
		if ( ! $this->shop_icon_pos ) {
			return '<style>.sfwl-a2w .fa{float: right}</style>' . $this->a2w_btn() . $html;
		}

		return $html . $this->a2w_btn();
	}

	/**
	 * Adds add to wishlist icon near title
	 * @filter the_title
	 * @param string $title
	 * @return string
	 */
	public function title_add2wishlist( $title ) {

		// Proceed only if doing single product summary
		if ( ! doing_action( 'woocommerce_single_product_summary' ) ) return $title;

		// Execute hook only once
		remove_filter( 'the_title', array( $this, 'title_add2wishlist' ) );

		// Append by default (empty value)
		if ( ! $this->product_icon_pos ) return $title . $this->a2w_btn();

		// Prepend if value is set
		return $this->a2w_btn() . $title;
	}

	/**
	 * Adds dialogs for pootle could
	 * @action pootlepb_le_dialogs
	 */
	public function footer() {
		?>
		<div id="wishlist" style="display: none;">
			<i class="fa fa-close"></i>
			<div id="sfwl-app-wrap">
				<iframe src="<?php echo $this->app_url ?>" frameborder="0" id="sfwl-app"></iframe>
			</div>
		</div>

		<a href="#wishlist" id="sfwl-app-toggle">
			<img id="sfwl-app-tgl-img">
			<i class="fa fa-heart"></i>
		</a>
		<?php
	}

	/** Initiate hooks */
	function fields( $fields ) {
		return array_merge( $fields, array(

			array(
				'id'       => 'sfwl-app-icon-pos',
				'label'    => 'App icon position',
				'section'  => 'Wishlist',
				'priority' => 25,
				'type'     => 'select',
				'default'  => '',
				'choices'  => array(
					''      => "Bottom Left",
					'right' => 'Bottom Right',
					'hide'  => "Don't show",
				),
			),
			array(
				'id'       => 'sfwl-app-icon-clr',
				'label'    => 'App icon color',
				'section'  => 'Wishlist',
				'priority' => 30,
				'type'     => 'color',
				'default'  => '#d34',
			),
			array(
				'id'       => 'sfwl-app-icon-fill',
				'label'    => 'App icon fill color',
				'section'  => 'Wishlist',
				'priority' => 35,
				'type'     => 'color',
				'default'  => '#fff',
			),
			array(
				'id'       => 'sfwl-app-icon-size',
				'label'    => 'App icon size',
				'section'  => 'Wishlist',
				'priority' => 40,
				'type'     => 'range',
				'default'  => 20,
			),

			// Shop settings

			array(
				'id'       => 'sfwl-shop-settings-b4',
				'section'  => 'Wishlist',
				'priority' => 160,
				'type'     => 'sf-divider',
			),
			array(
				'id'       => 'sfwl-shop-settings',
				'label'    => 'Shop settings',
				'section'  => 'Wishlist',
				'priority' => 160,
				'type'     => 'sf-heading',
			),
			array(
				'id'       => 'sfwl-shop-settings-fr',
				'section'  => 'Wishlist',
				'priority' => 170,
				'type'     => 'sf-divider',
			),
			array(
				'id'       => 'sfwl-shop-pos',
				'label'    => 'Add to wishlist heart position',
				'section'  => 'Wishlist',
				'priority' => 175,
				'type'     => 'select',
				'default'  => '',
				'choices'  => array(
					''          => "Left of Add to cart",
					'right-a2c' => 'Right of Add to cart',
					'left-img'  => 'Over product image Top left',
					'right-img' => 'Over product image Top right',
				),
			),
			array(
				'id'       => 'sfwl-shop-clr',
				'label'    => 'Add to wishlist heart color',
				'section'  => 'Wishlist',
				'priority' => 180,
				'type'     => 'color',
				'default'  => '#d34',
			),
			array(
				'id'       => 'sfwl-shop-size',
				'label'    => 'Add to wishlist heart size',
				'section'  => 'Wishlist',
				'priority' => 185,
				'type'     => 'range',
				'default'  => 20,
			),

			// Single product settings

			array(
				'id'       => 'sfwl-product-settings-b4',
				'section'  => 'Wishlist',
				'priority' => 250,
				'type'     => 'sf-divider',
			),
			array(
				'id'       => 'sfwl-product-settings',
				'label'    => 'Single product settings',
				'section'  => 'Wishlist',
				'priority' => 250,
				'type'     => 'sf-heading',
			),
			array(
				'id'       => 'sfwl-product-settings-fr',
				'section'  => 'Wishlist',
				'priority' => 250,
				'type'     => 'sf-divider',
			),
			array(
				'id'       => 'sfwl-product-pos',
				'label'    => 'Add to wishlist heart position',
				'section'  => 'Wishlist',
				'priority' => 255,
				'type'     => 'select',
				'default'  => '',
				'choices'  => array(
					''         => "Append to title",
					'b4-title' => 'Prepend to title',
					'left-img' => 'Over product image',
					'fr-cart'  => 'After cart button',
				),
			),
			array(
				'id'       => 'sfwl-product-clr',
				'label'    => 'Add to wishlist heart color',
				'section'  => 'Wishlist',
				'priority' => 260,
				'type'     => 'color',
				'default'  => '#d34',
			),
			array(
				'id'       => 'sfwl-product-size',
				'label'    => 'Add to wishlist heart size',
				'section'  => 'Wishlist',
				'priority' => 255,
				'type'     => 'range',
				'default'  => 20,
			),
		) );
	}
}

Storefront_Wishlist::instance();