<?php
/**
 * Handles the Coming Soon State of WooCommerce Products Frontend Side.
 *
 * @category class
 * @package  ComingSoon
 */

namespace GPLSCorePro\GPLS_PLUGIN_WCSAMM;

use DateTime;
use GPLSCorePro\GPLS_PLUGIN_WCSAMM\Settings;
use GPLSCorePro\GPLS_PLUGIN_WCSAMM\ComingSoon;

/**
 * Coming Soon Class
 */
class ComingSoonFrontend extends ComingSoon {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks Function.
	 *
	 * @return void
	 */
	public function hooks() {
		// Assets.
		add_action( 'wp_enqueue_scripts', array( get_called_class(), 'front_assets' ) );

		// Show "Coming Soon" title.
		add_action( 'woocommerce_single_product_summary', array( $this, 'get_coming_soon_section' ), 29 );

		// Disable Add to cart.
		add_filter( 'woocommerce_is_purchasable', array( $this, 'make_coming_soon_product_unpurchasable' ), PHP_INT_MAX, 2 );
		// Disable Add to cart for coming soon external products.
		add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'redirect_coming_soon_external_to_product_page' ), 1000, 2 );
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'change_coming_soon_external_cart_btn_text' ), 1000, 2 );

		// Remove Add to cart buttons.
		add_action( 'woocommerce_before_single_product', array( $this, 'handle_add_to_cart_button' ), 1 );

		// 5) Coming Soon Badge.
		// Soon Badge in Single page.
		add_action( 'woocommerce_gallery_image_html_attachment_image_params', array( $this, 'single_coming_soon_badge_trigger' ), 1000, 4 );
		add_action( 'wp_get_attachment_image', array( $this, 'single_and_loop_add_coming_soon_badge' ), PHP_INT_MAX, 5 );

		// Soon Badge in Loop page.
		add_filter( 'single_product_archive_thumbnail_size', array( $this, 'loop_coming_soon_badge_trigger' ), 100, 1 );

		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'loop_coming_soon_text' ), 8 );
		add_action( 'woocommerce_after_shop_loop_item', array( get_called_class(), 'loop_countdown_section' ), 9 );

		// Availability Text.
		add_filter( 'woocommerce_get_availability', array( $this, 'is_hide_availability_text' ), PHP_INT_MAX, 2 );

		// Filter Price.
		add_filter( 'woocommerce_get_price_html', array( $this, 'filter_product_price' ), PHP_INT_MAX, 2 );
	}

	/**
	 * Trigger the Loop Thubmanil Function.
	 *
	 * @param string $size
	 * @return string
	 */
	public function loop_coming_soon_badge_trigger( $size ) {
		$GLOBALS[ self::$plugin_info['name'] . '-inside-loop-product-thubmnail' ] = true;
		return $size;
	}

	/**
	 * Trigger if show coming soon badge for single Product main image.
	 *
	 * @param array $params
	 * @param int $attachment_id
	 * @param array|string $image_size
	 * @param boolean $main_image
	 * @return array
	 */
	public function single_coming_soon_badge_trigger( $params, $attachment_id, $image_size, $main_image ) {
		if ( ! $main_image ) {
			return $params;
		}
		global $product;

		if ( ! $product || is_null( $product ) || is_wp_error( $product ) ) {
			return $params;
		}

		if ( ! self::is_product_coming_soon( $product->get_id() ) ) {
			return $params;
		}

		$badge_details = Settings::get_settings( 'badge' );
		if ( 'on' !== $badge_details['badge_status'] ) {
			return $params;
		}

		$params[ self::$plugin_info['name'] . '-single-product-coming-soon-badge-trigger' ] = true;

		return $params;
	}

	/**
	 * Single - Loop Product Coming Soon Badge hooking to Main Product Image.
	 *
	 * @param string $img_html
	 * @param int $attachment_id
	 * @param array|string $size
	 * @param string $icon
	 * @param array $attr
	 * @return string
	 */
	public function single_and_loop_add_coming_soon_badge( $img_html, $attachment_id, $size, $icon, $attr ) {
		global $product;

		if ( ! $product || is_wp_error( $product ) ) {
			return $img_html;
		}

		// Single Product Image.
		if ( ! empty( $attr[ self::$plugin_info['name'] . '-single-product-coming-soon-badge-trigger' ] ) ) {

			$badge_details = Settings::get_settings( 'badge' );
			$badge_url     = Settings::get_badge_url( $badge_details['badge_icon'] );

			if ( 'on' !== $badge_details['badge_status'] ) {
				return $img_html;
			}

			if ( self::product_has_coming_soon_badge( $product->get_id() ) ) {
				return $img_html;
			}

			$coming_soon_badge = self::coming_soon_badge( $badge_url, $badge_details );
			$img_html          = str_replace( '<img', self::coming_soon_badge_wrapper_start() . $coming_soon_badge . '<img', $img_html );
			$img_html         .= self::coming_soon_badge_wrapper_end();

			self::mark_product_coming_soon_badge( $product->get_id() );
		// Loop Product Thumbnail.
		} elseif ( ! empty( $GLOBALS[ self::$plugin_info['name'] . '-inside-loop-product-thubmnail' ] ) ) {

			if ( ! self::product_has_coming_soon_badge( $product->get_id() ) ) {
				$coming_soon_badge = self::add_coming_soon_badge( '', 0 );
				$img_html          = str_replace( '<img', self::coming_soon_badge_wrapper_start() . $coming_soon_badge . '<img', $img_html );
				$img_html         .= self::coming_soon_badge_wrapper_end();

				self::mark_product_coming_soon_badge( $product->get_id() );
			}

			unset( $GLOBALS[ self::$plugin_info['name'] . '-inside-loop-product-thubmnail' ] );
		}

		return $img_html;
	}

	/**
	 * Coming Soon Badge Wrapper Start.
	 *
	 * @return string
	 */
	public static function coming_soon_badge_wrapper_start() {
		global $product;
		if ( ! $product || is_wp_error( $product ) ) {
			return;
		}
		if ( ! self::is_product_coming_soon( $product->get_id() ) ) {
			return;
		}
		ob_start();
		?>
		<span class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-coming-soon-badge-img-wrapper' ); ?>" style="display:inline-block;position:relative;width:auto;height:auto;" class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-gif-block-loader' ); ?>">
		<?php
		return ob_get_clean();
	}

	/**
	 * Coming Soon Badge Wrapper Start.
	 *
	 * @return string
	 */
	public static function coming_soon_badge_wrapper_end() {
		global $product;
		if ( ! $product || is_wp_error( $product ) ) {
			return;
		}
		if ( ! self::is_product_coming_soon( $product->get_id() ) ) {
			return;
		}
		ob_start();
		?></span><?php
		return ob_get_clean();
	}

	/**
	 * Mark The Product already has coming soon badge.
	 *
	 * @param int $product_id
	 * @return void
	 */
	private static function mark_product_coming_soon_badge( $product_id ) {
		if ( ! isset( $GLOBALS[ self::$plugin_info['name'] . '-product-has-coming-soon-badge' ] ) ) {
			$GLOBALS[ self::$plugin_info['name'] . '-product-has-coming-soon-badge' ] = array();
		}
		$GLOBALS[ self::$plugin_info['name'] . '-product-has-coming-soon-badge' ][] = $product_id;
	}

	/**
	 * Check if the product already has coming soon badge.
	 *
	 * @param int $product_id
	 * @return boolean
	 */
	private static function product_has_coming_soon_badge( $product_id ) {
		if ( ! isset( $GLOBALS[ self::$plugin_info['name'] . '-product-has-coming-soon-badge' ] ) ) {
			return false;
		}

		if ( ! in_array( $product_id, $GLOBALS[ self::$plugin_info['name'] . '-product-has-coming-soon-badge' ] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Frontend Assets.
	 *
	 * @return void
	 */
	public static function front_assets() {
		wp_enqueue_style( self::$plugin_info['name'] . '-flipdown-responsive-style', self::$plugin_info['url'] . 'assets/dist/css/flipdown.min.css', array(), self::$plugin_info['version'], 'all' );
		wp_enqueue_script( self::$plugin_info['name'] . '-flipdown-responsive', self::$plugin_info['url'] . 'core/assets/libs/flipdown.min.js', array( 'jquery' ), self::$plugin_info['version'], true );

		$badge_details       = Settings::get_settings( 'badge' );
		$front_badge_details = array(
			'status' => $badge_details['badge_status'],
			'angle'  => $badge_details['badge_angle'],
			'left'   => $badge_details['badge_left'],
			'top'    => $badge_details['badge_top'],
		);
		wp_enqueue_script( self::$plugin_info['name'] . '-dist-single-product-actions', self::$plugin_info['url'] . 'assets/dist/js/front-single-product-actions.min.js', array( 'jquery', self::$plugin_info['name'] . '-flipdown-responsive' ), self::$plugin_info['version'], true );
		wp_localize_script(
			self::$plugin_info['name'] . '-dist-single-product-actions',
			self::$plugin_info['localize_var'],
			array(
				'badge'                     => $front_badge_details,
				'ajaxUrl'                   => admin_url( 'admin-ajax.php' ),
				'nonce'                     => wp_create_nonce( self::$plugin_info['name'] . '-nonce' ),
				'prefix'                    => self::$plugin_info['name'],
				'subSubmitAction'           => self::$plugin_info['name'] . '-subscription-submit-action',
				'classes_prefix'            => self::$plugin_info['classes_prefix'],
				'related_plugins'           => self::$plugin_info['related_plugins'],
				'labels'                    => array(
					'flipDownHeading' => array(
						'days'    => esc_html__( 'Days', 'gpls-wcsamm-coming-soon-for-woocommerce' ),
						'hours'   => esc_html__( 'Hours', 'gpls-wcsamm-coming-soon-for-woocommerce' ),
						'minutes' => esc_html__( 'Minutes', 'gpls-wcsamm-coming-soon-for-woocommerce' ),
						'seconds' => esc_html__( 'Seconds', 'gpls-wcsamm-coming-soon-for-woocommerce' ),
					),
				),
			)
		);

		wp_enqueue_style( self::$plugin_info['name'] . '-frontend-styles', self::$plugin_info['url'] . 'assets/dist/css/frontend-styles.min.css', array(), self::$plugin_info['version'], 'all' );

		ob_start();
		?>
		.<?php echo esc_attr( self::$plugin_info['classes_prefix'] ); ?>-coming-soon-badge {
			display: block;
			margin-left: unset;
			margin-right: unset;
			margin-bottom: unset;
			width: <?php echo esc_attr( ! empty( $badge_details['badge_width'] ) ? $badge_details['badge_width'] . 'px' : 50 . 'px' ); ?>;
			height: <?php echo esc_attr( ! empty( $badge_details['badge_height'] ) ? $badge_details['badge_height'] . 'px' : 50 . 'px' ); ?>;
			left: <?php echo esc_attr( $badge_details['badge_left'] . 'px' ); ?>;
			top: <?php echo esc_attr( $badge_details['badge_top'] . 'px' ); ?>;
			transform: rotate(<?php echo absint( esc_attr( $badge_details['badge_angle'] ) ); ?>deg) !important;
			position: relative;
			z-index: 1000;
		}
		.wc-block-grid__products .wc-block-grid__product .<?php echo esc_attr( self::$plugin_info['classes_prefix'] ); ?>-coming-soon-badge {
			width: <?php echo esc_attr( $badge_details['badge_width'] . 'px' ); ?>;
			height: <?php echo esc_attr( $badge_details['badge_height'] . 'px' ); ?>;
		}
		<?php
		Settings::get_countdown_styles( true );
		$badge_img_styles = ob_get_clean();
		wp_add_inline_style(
			self::$plugin_info['name'] . '-frontend-styles',
			$badge_img_styles
		);
	}

	/**
	 * Get Coming-Soon Section of a product.
	 *
	 * @return void
	 */
	public function get_coming_soon_section() {
		global $product;
		if ( ! $product ) {
			return;
		}
		$product_id = $product->get_id();
		$settings   = self::get_settings( $product_id );
		if ( ! self::is_product_coming_soon( $product_id ) ) {
			return;
		}
		// 1) Coming Soon Text.
		self::coming_soon_text( $product_id, $settings );

		// 2) Arrival Time Countdown.
		self::countdown_section( $product_id, $settings );
	}

	/**
	 * Coming Soon Text.
	 *
	 * @param int   $product_id
	 * @param array $settings
	 * @return void
	 */
	public static function coming_soon_text( $product_id, $settings = array() ) {
		if ( empty( $settings ) ) {
			$coming_soon_text = self::get_settings( $product_id, 'coming_soon_text' );
		} else {
			$coming_soon_text = $settings['coming_soon_text'];
		}
		$content = apply_filters( 'the_content', $coming_soon_text );
		$content = str_replace( ']]>', ']]&gt;', $content );
		// phpcs:ignore WordPress.Security.EscapeOutput
		?>
		<div class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-coming-soon-text' ); ?>" >
		<?php
		echo wp_kses_post( $content );
		?>
		</div>
		<?php
	}

	/**
	 * CountDown Section.
	 *
	 * @param int $product_id Product ID.
	 *
	 * @return void
	 */
	public static function countdown_section( $product_id, $settings = array() ) {
		if ( empty( $settings ) ) {
			$settings = self::get_settings( $product_id );
		}
		if ( self::is_show_arrival_time_countdown( $product_id ) && ! self::is_product_arrival_time_passed( $product_id ) ) :
			$current_time = ( current_datetime()->getTimestamp() );
			$arrival_time = DateTime::createFromFormat( 'Y-m-d\TH:i', $settings['arrival_time'], wp_timezone() );
			if ( empty( $arrival_time ) ) {
				return;
			}
			?>
			<div id="flipdown"
				class="flipdown flipper flipper-dark <?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-flipper' . ' ' . self::$plugin_info['classes_prefix'] . '-flipper' . '-' . $product_id ); ?>"
				data-datetime="<?php echo esc_attr( $arrival_time->getTimestamp() ); ?>"
				data-template="ddd|HH|ii|ss"
				data-labels="Days|Hours|Minutes|Seconds"
				data-reverse="true"
				data-auto_enable="false"
				data-now="<?php echo esc_attr( $current_time ); ?>"
			>
			</div>
			<?php
		endif;
	}

	/**
	 * Coming Soon Text in Loop
	 *
	 * @return void
	 */
	public function loop_coming_soon_text() {
		global $product;
		if ( ! $product ) {
			return;
		}
		$loop_settings = Settings::get_settings( 'countdown', 'loop' );
		if ( ! $loop_settings['text_status'] || ( 'off' === $loop_settings['text_status'] ) ) {
			return;
		}
		if ( ! self::is_product_coming_soon( $product->get_id() ) ) {
			return;
		}
		self::coming_soon_text( $product->get_id() );
	}

	/**
	 * Countdown Section in loop.
	 *
	 * @return void
	 */
	public static function loop_countdown_section( $_product = null ) {
		if ( empty( $_product ) || is_null( $_product ) ) {
			global $product;
		} else {
			$product = $_product;
		}
		if ( ! $product ) {
			return;
		}
		$loop_settings = Settings::get_settings( 'countdown', 'loop' );
		if ( ! $loop_settings['status'] || ( 'off' === $loop_settings['status'] ) ) {
			return;
		}
		if ( ! self::is_product_coming_soon( $product->get_id() ) ) {
			return;
		}
		self::countdown_section( $product->get_id() );
	}

	/**
	 * Filter Product Price.
	 *
	 * @param string $price_html
	 * @param \WC_Product $_product
	 * @return string
	 */
	public function filter_product_price( $price_html, $_product ) {
		$settings   = self::get_settings( $_product->get_id() );
		if ( self::is_product_coming_soon( $_product->get_id() ) && ( 'yes' === $settings['hide_price'] ) ) {
			return '';
		}
		return $price_html;
	}

	/**
	 * Direct External Product Link to Product single page.
	 *
	 * @param string $product_url
	 * @param object $product_obj
	 * @return string
	 */
	public function redirect_coming_soon_external_to_product_page( $product_url, $_product ) {
		if ( is_a( $_product, \WC_Product_External::class ) && self::is_product_coming_soon( $_product->get_id() ) ) {
			return $_product->get_permalink();
		}
		return $product_url;
	}

	/**
	 * Change Coming Soon External Add To Cart Button Text.
	 *
	 * @param string $add_to_cart_text
	 * @param object $_product
	 * @return string
	 */
	public function change_coming_soon_external_cart_btn_text( $add_to_cart_text, $_product ) {
		if ( is_a( $_product, \WC_Product_External::class ) && self::is_product_coming_soon( $_product->get_id() ) ) {
			return esc_html__( 'Read more', 'woocommerce' );
		}
		return $add_to_cart_text;
	}


	/**
	 * Hide - Show Availability Text.
	 *
	 * @param array  $avialability_data Availability Text and Class Array.
	 * @param object $product_obj The product Object.
	 *
	 * @return array
	 */
	public function is_hide_availability_text( $avialability_data, $product_obj ) {
		if ( ! $product_obj ) {
			return $avialability_data;
		}
		if ( self::is_product_coming_soon( $product_obj->get_id() ) && ! $product_obj->backorders_allowed() ) {
			return array();
		}
		return $avialability_data;
	}


	/**
	 * Add Coming Soon Badge to products.
	 *
	 * @param string $image_thumbnail_html
	 * @param int    $thumbnail_id
	 * @return string
	 */
	public static function add_coming_soon_badge( $image_thumbnail_html, $thumbnail_id, $product = null ) {
		if ( is_null( $product ) ) {
			global $product;
		}
		if ( ! $product || is_null( $product ) || is_wp_error( $product ) ) {
			return $image_thumbnail_html;
		}

		if ( ! self::is_product_coming_soon( $product->get_id() ) ) {
			return $image_thumbnail_html;
		}

		// Exclude widgets, not suitable for badges.
		$widget_start = did_action( 'woocommerce_widget_product_item_start' );
		$widget_end   = did_action( 'woocommerce_widget_product_item_end' );
		if ( $widget_start > $widget_end ) {
			return $image_thumbnail_html;
		}

		$badge_details = Settings::get_settings( 'badge' );
		$badge_url     = Settings::get_badge_url( $badge_details['badge_icon'] );

		if ( 'on' !== $badge_details['badge_status'] ) {
			return $image_thumbnail_html;
		}

		$image_thumbnail_html .= self::coming_soon_badge( $badge_url, $badge_details );

		self::mark_product_coming_soon_badge( $product->get_id() );

		return $image_thumbnail_html;
	}

	/**
	 * Coming Soon Badge HTML.
	 *
	 * @param string $badge_url
	 * @param array $badge_details
	 * @return string
	 */
	protected static function coming_soon_badge( $badge_url, $badge_details ) {
		$badge_url = empty( $badge_url ) ? self::$plugin_info['url'] . 'assets/images/coming-soon-icon-9.png' : $badge_url;
		ob_start();
		?><img style="position:absolute;left:<?php echo esc_attr( isset( $badge_details['badge_left'] ) ? $badge_details['badge_left'] : 0 ); ?>px;top:<?php echo esc_attr( isset( $badge_details['badge_top'] ) ? $badge_details['badge_top'] : 0 ); ?>px;<?php echo esc_attr( ! empty( $badge_details['badge_width'] ) ? 'width:' . $badge_details['badge_width'] . 'px;' : '' ); ?><?php echo esc_attr( ! empty( $badge_details['badge_height'] ) ? 'height:' . $badge_details['badge_height'] . 'px;' : '' ); ?>" src="<?php echo esc_url_raw( $badge_url ); ?>" class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] ); ?>-coming-soon-badge" alt="coming-soon-badge"><?php
		return ob_get_clean();
	}

	/**
	 * Add Coming Soon Badge in loop pages.
	 *
	 * @param string $size
	 * @return string
	 */
	public function add_coming_soon_badge_loop() {
		// Woo Default loop thumbnail function is removed, fallback to regular hook.
		global $product;
		if ( ! $product || is_wp_error( $product ) ) {
			return;
		}

		if ( ! self::product_has_coming_soon_badge( $product->get_id() ) ) {
			echo wp_kses_post( self::add_coming_soon_badge( '', 0 ) );
		}
	}

	/**
	 * Disable add to cart function of coming soon product by making it unpurchasable.
	 *
	 * @param boolean $is_purchasable
	 * @param object  $product_obj
	 * @return boolean
	 */
	public function make_coming_soon_product_unpurchasable( $is_purchasable, $product_obj ) {
		if ( is_null( $product_obj ) || empty( $product_obj ) || is_wp_error( $product_obj ) ) {
			return $is_purchasable;
		}
		if ( self::is_product_unpurchasable( $product_obj->get_id() ) ) {
			return false;
		}
		return $is_purchasable;
	}

	/**
	 * Handle the Add to cart button for coming soon products.
	 *
	 * @return void
	 */
	public function handle_add_to_cart_button() {
		$products_types = wc_get_product_types();
		foreach ( $products_types as $type_name => $type_label ) {
			add_action( 'woocommerce_' . $type_name . '_add_to_cart', array( $this, 'remove_add_to_cart_button_for_coming_soon' ), 1 );
		}
	}

	/**
	 * Remove Add to cart hook for coming soon products.
	 *
	 * @return void
	 */
	public function remove_add_to_cart_button_for_coming_soon() {
		global $product;
		if ( ! $product || is_wp_error( $product ) ) {
			return;
		}
		$product_id = $product->get_id();
		if ( self::is_product_unpurchasable( $product_id ) ) {
			remove_action( 'woocommerce_' . $product->get_type() . '_add_to_cart', 'woocommerce_' . $product->get_type() . '_add_to_cart', 30 );
		}
	}


	/**
	 * Filter Whether Let the Add to cart Link Button or not.
	 *
	 * @param string $link_html
	 * @param object $_product_obj
	 * @param array  $args
	 * @return string
	 */
	public function filter_loop_add_to_cart_button( $link_html, $_product_obj, $args ) {
		$product_id = $_product_obj->get_id();
		if ( self::is_product_coming_soon( $product_id ) ) {
			return '';
		}
		return $link_html;
	}

	/**
	 * Filter Single Product Page Add to cart button.
	 *
	 * @return void
	 */
	public function filter_single_product_add_to_cart_button() {
		global $product;
		if ( ! self::is_product_coming_soon( $product->get_id() ) ) {
			return;
		}
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}

}
