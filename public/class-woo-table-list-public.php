<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://antonionardone.com
 * @since      1.0.0
 *
 * @package    Woo_Table_List
 * @subpackage Woo_Table_List/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Table_List
 * @subpackage Woo_Table_List/public
 * @author     Antonio Nardone <swina.allen@gmail.com>
 */
class Woo_Table_List_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Table_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Table_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-table-list.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Table_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Table_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( is_shop() || is_product_category() || is_product_tag() || is_product() ){
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-table-list.min.js', array( 'jquery' ), $this->version, false );
			$options = get_option ( $this->plugin_name );
			$options['admin_url'] = admin_url( 'admin-ajax.php' );
			$options['cart_url'] = wc_get_cart_url();
			$options['woo_product_page'] = is_product() ? true : false;
			$options['currency'] = get_woocommerce_currency_symbol();
			$options['action']	= 'woo_table_list_add_cart_single_ajax';
			wp_localize_script( $this->plugin_name , $this->plugin_name, $options );
		}
	}

	public function woo_table_list_add_list_button(){
		if ( !is_product() )
		echo '<div class="products-table-list-btn" data-trigger="0" title="List View"><span class="wtl-table-list-icon dashicons dashicons-list-view"></span></div>';
	}

	public function woo_table_list_variable_product_price( $price, $product ) {

		$target_product_types = array(
			'variable'
		);
		

		if ( in_array ( $product->get_type(), $target_product_types ) ) {
			// if variable product return and empty string
			
			return str_replace('From:','',$price);
		}
		
		// return normal price
		return $price;
	}

	public function woo_table_list_product_data(){
		global $product;
		$options = get_option ( $this->plugin_name );
		$meta = '';
		$id = $product->get_id();
		if ( $options['meta_fields'] ){
			$keys = explode(',', $options['meta_fields']);
			foreach ( $keys as $key ){
				$meta .= get_post_meta( $id , $key, true );
			}
		}
		echo '<span class="products-table-row-meta-data" data-meta="'.$meta.'"></span>';
		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		
		$cat_ids = $product->get_category_ids();
		$cats = '';
		foreach((get_the_terms( $product->get_id(), 'product_cat' )) as $category) {
			$cats .= str_replace("'" , "|" , $category->name ) . ',';
		}
		$image =  wp_get_attachment_url( $product->get_image_id() );
		if ( !$image ){
			$image = wc_placeholder_img_src();
		}
		$variations = '';
		if ($product->get_type() === 'variable' ){
			$variations = $product->get_available_variations();
			$sale       =  0;
			$price   	=  $product->get_variation_regular_price( 'min', true );
		} else {
			$sale = $product->get_sale_price();
			$price = $product->get_regular_price();//str_replace('From:','',$product->get_price_html());
		}

		$data = array (
			'id'		=> $id,
			'type'		=> $product->get_type(),
			'title'		=> $product->get_title(),
			'excerpt'	=> get_the_excerpt($product->get_id()),
			'regular'	=> $price,
			'sale_price'=> $sale,
			'price' 	=> wc_price($price),
			'sale'		=> wc_price($sale),
			'rating'	=> wc_get_rating_html($product->get_average_rating(),$product->get_rating_count()),
			'slug'		=> $product->get_permalink(),
			'api'		=> $product->get_slug(),
			'variations'=> json_encode($variations),
			'categories'=> $cats,
			'image'		=> $image,
			'meta'		=> $meta
		);
		echo "<span class='products-data' id='product-". $product->get_id()."' data-json='".json_encode($data)."'></span>";

	}

	public function wtl_change_loop_add_to_cart(){
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		add_action( 'woocommerce_after_shop_loop_item', 'wtl_template_loop_add_to_cart', 10 );
	}

	public function wtl_product_variations_ajax(){
		$id = $_GET['id'];
		$product = new WC_Product_Variable( $_GET['product_id'] );
		$variations = $product->get_available_variations();
		print_r( $variations );
	}

	/**
	 * Use single add to cart button for variable products.
	 */
	

	public function woo_table_list_add_cart_single_ajax() {
		ob_start();
		$product_id = $_POST['product_id'];
		$variation_id = $_POST['variation_id'];
		$quantity = $_POST['quantity'];
		$variations = ! empty ( $_POST['variations'] ) ? $_POST['variations'] : '';
	
		if ($variation_id) {
			if ( WC()->cart->add_to_cart( $product_id, $quantity, $variation_id , $variations ) ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				//wc_add_to_cart_message( $product_id );
			} else {
				woo_table_list_add_cart_single_ajax_error($product_id);
			}
		} else {
			if ( WC()->cart->add_to_cart( $product_id, $quantity) ){
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				//wc_add_to_cart_message( $product_id );
			} else {
				woo_table_list_add_cart_single_ajax_error($product_id);
			}
		}
		die();
	}
	

}


/**
 * Replace add to cart button in the loop.
 */

/*
function wtl_change_loop_add_to_cart() {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	add_action( 'woocommerce_after_shop_loop_item', 'wtl_template_loop_add_to_cart', 10 );
}
add_action( 'init', 'wtl_change_loop_add_to_cart', 10 );
*/

/**
 * Use single add to cart button for variable products.
 */

function wtl_template_loop_add_to_cart() {
	global $product;

	if ( ! $product->is_type( 'variable' ) ) {
		//woocommerce_template_loop_add_to_cart();
		return;
	}
	remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
	add_action( 'woocommerce_single_variation', 'wtl_loop_variation_add_to_cart_button', 20 );

	woocommerce_template_single_add_to_cart();
}
/**
 * Customise variable add to cart button for loop.
 *
 * Remove qty selector and simplify.
 */
function wtl_loop_variation_add_to_cart_button() {
	global $product;

	?>
	<div class="woocommerce-variation-add-to-cart variations_button">
		<?php _e('Q.ty','woo_table_list');?> <input type="number" min="1" max="100" class="wtl-variations-qty"  name="quantity" value="1"/>
		<button type="submit" class="single_add_to_cart_button button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
		<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
		<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
		<input type="hidden" name="variation_id" class="variation_id" value="0" />
	</div>
	<?php
}

/*
add_action( 'wp_ajax_woo_table_list_add_cart_single_ajax', 'woo_table_list_add_cart_single_ajax' );

function woo_table_list_add_cart_single_ajax() {
	ob_start();
	$product_id = $_POST['product_id'];
	$variation_id = $_POST['variation_id'];
	$quantity = $_POST['quantity'];
	$variations = ! empty ( $_POST['variations'] ) ? $_POST['variations'] : '';
	
	if ($variation_id) {
		if ( WC()->cart->add_to_cart( $product_id, $quantity, $variation_id , $variations ) ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );
			//wc_add_to_cart_message( $product_id );
		} else {
			woo_table_list_add_cart_single_ajax_error($product_id);
		}
	} else {
		if ( WC()->cart->add_to_cart( $product_id, $quantity) ){
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );
			//wc_add_to_cart_message( $product_id );
		} else {
			woo_table_list_add_cart_single_ajax_error($product_id);
		}
	}
	die();
}
*/

function woo_table_list_add_cart_single_ajax_error($product_id){
	$data = array(
		'error'       => true,
		'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
	);
	wp_send_json( $data );
}