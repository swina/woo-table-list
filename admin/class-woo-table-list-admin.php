<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://antonionardone.com
 * @since      1.0.0
 *
 * @package    Woo_Table_List
 * @subpackage Woo_Table_List/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Table_List
 * @subpackage Woo_Table_List/admin
 * @author     Antonio Nardone <swina.allen@gmail.com>
 */
class Woo_Table_List_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-table-list-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script( 'jquery-ui-draggable' ,false, array('jquery'));
		wp_enqueue_script( 'jquery-ui-sortable' ,false, array('jquery'));
		wp_enqueue_script( 'jquery-ui-droppable' ,false, array('jquery'));
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-table-list-admin.js', array( 'jquery' ), $this->version, false );
		$options = get_option ( $this->plugin_name );
		wp_localize_script( $this->plugin_name , $this->plugin_name, $options );
	}

	public function woo_table_list_menu(){
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page(  $this->plugin_name, 'Woo Table List', 'administrator', $this->plugin_name, array( $this, 'displayPluginAdminDashboard' ), 'dashicons-list-view'  );
		
	}

	public function displayPluginAdminDashboard() {
		include_once(dirname ( __FILE__ ) . '/partials/woo-table-list-admin-display.php');
  	}

	public function wtl_options_ajax(){
		$fields = array (
			'enable'      => 'checked,Enable Woo Table List,Enable Woo Table List as default view,none',
			'default'     => 'checked,Woo Table List default view,Enable Woo Table List as default view,none',
			'template'    => 'checked,Enable switch view,Enable to switch from Woo Table List to the template view.<br/>When checked user can switch view from Woo Table List to the default template view. <br/>An icon will be added to switch between views.,none',
			'force_variable' => 'checked,Enable Variations Selection, Enable to open a select variations modal directly form the table list view.',
			'image'       => 'checked,Thumbnail,View/Hide product image in the table view,draggable',
			'title'       => 'checked,Name,View/Hide product name,draggable',
			'categories'  => 'checked,Categories,View/Hide product categories list,draggable',
			'rating'      => 'checked,Rating,View/Hide product rating,draggable',
			'price'       => 'checked,Price,View/Hide price from the list,draggable',
			'sale'        => 'checked,Sale tag,View/Hide sale tag',
			'qty'         => 'checked,Quantity,Enable quantities input by the user (available only for simple products),draggable',
			'add_to_cart' => 'checked,Add To Cart,Enable add to cart button,draggable',
			'excerpt'     => 'checked,Show excerpt,View/Hide product excerpt in the add to cart modal,none',
			'image_zoom'  => 'checked,Thumbnail Zoom,Enable thumbnail mouseover to show a larger preview of the product image'
		);
		$settings = array(); 
		foreach ( $fields as $key=>$field ){
			if ( isset($_POST['woo_table_list_' .$key]) ){
				$settings[$key] = prefix_sanitize_checkbox($_POST['woo_table_list_'.$key]);
			} else { 
				$settings[$key] = '0';
			}
		}
		$settings['meta_fields']    = sanitize_text_field($_POST['woo_table_list_meta'] );
		$settings['cart_text']      = sanitize_text_field($_POST['woo_table_list_cart_text'] );
		$settings['cart_goto']      = sanitize_text_field($_POST['woo_table_list_cart_goto'] );
		$settings['cart_added']     = sanitize_text_field($_POST['woo_table_list_cart_added'] );
		$settings['options_text']   = sanitize_text_field($_POST['woo_table_list_options_text'] );
		$settings['sale_text']      = sanitize_text_field($_POST['woo_table_list_sale_text'] );
		$settings['variable_text']  = sanitize_text_field($_POST['woo_table_list_variable_text'] );
		$settings['fields_order']   = sanitize_text_field($_POST['woo_table_list_fields_order'] );
		$settings['table_layout']   = sanitize_text_field($_POST['woo_table_list_layout'] );
		update_option ( 'woo_table_list' , $settings );
	}

	

}
function prefix_sanitize_checkbox( $input, $expected_value='on' ) {
	if ( $input === 'on' ) {
		return '1';
	} else {
		return '0';
	}
}