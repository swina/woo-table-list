<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://antonionardone.com
 * @since      1.0.0
 *
 * @package    Woo_Table_List
 * @subpackage Woo_Table_List/admin/partials
 */

$order_fields = array ( 'image' , 'title' , 'categories' , 'rating' , 'price' , 'qty' , 'add_to_cart' );
$table_layout = 'image;title;categories;rating;price;qty;add_toCart';
$options = get_option ( 'woo_table_list' );

if ( !$options ){
  $options = array (
  'enable'        => '0',
  'default'       => '0',
  'template'      => '0',
  'image'         => '1',
  'title'         => '1',
  'categories'    => '0',
  'rating'        => '0',
  'price'         => '1',
  'sale'          => '1',
  'qty'           => '1',
  'add_to_cart'   => '1',
  'excerpt'       => '0',
  'force_variable'=> '1',
  'image_zoom'    => '0',
  'cart_text'     => 'Add to cart',
  'cart_goto'     => 'My Cart',
  'cart_added'    => 'added to cart',
  'options_text'  => 'Select options',
  'variable_text' => 'Add variations',
  'sale_text'     => 'Sale!',
  'meta_fields'   => '',
  'fields_order'  => implode(',',$order_fields),
  'table_layout'  => $table_layout
  );
  update_option ( 'woo_table_list' , $options );
  //$options = get_option ( 'woo_table_list' );
  //$order_fields = array ( $options['fields_order'] );
}
 $fields = array (
  'enable'      => 'checked,Enable Woo Table List,Enable Woo Table List for Woocommerce,none',
  'default'     => 'checked,Woo Table List default view,Enable Woo Table List as default view,none',
  'template'    => 'checked,Enable switch view,Enable to switch from Woo Table List to the template view.<br/>When checked user can switch view from Woo Table List to the default template view. <br/>An icon will be added to switch between views.,none',
  'force_variable' => 'checked,Enable Variations Selection, Enable to open a select variations modal directly form the table list view.,none',
  'image'       => 'checked,Thumbnail,View/Hide product image in the table view,draggable',
  'title'       => 'checked,Name,View/Hide product name,draggable',
  'categories'  => 'checked,Categories,View/Hide product categories list,draggable',
  'rating'      => 'checked,Rating,View/Hide product rating,draggable',
  'price'       => 'checked,Price,View/Hide price from the list,draggable',
  'sale'        => 'checked,Sale tag,View/Hide sale tag,none',
  'qty'         => 'checked,Quantity,Enable quantities input by the user (available only for simple products),draggable',
  'add_to_cart' => 'checked,Add To Cart,Enable add to cart button,draggable',
  'excerpt'     => 'checked,Show excerpt,View/Hide product excerpt in the add to cart modal,none',
  'image_zoom'  => 'checked,Thumbnail Zoom,Enable thumbnail mouseover to show a larger preview of the product image,none'
 );

/*
if ( isset($_POST['save_options']) ){
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
  $options = get_option ( 'woo_table_list' );
}
  /*
  function prefix_sanitize_checkbox( $input, $expected_value='on' ) {
    if ( $input === 'on' ) {
        return '1';
    } else {
        return '0';
    }
  }
  /*
?>
*/
?>
<h1 class="wtl_header" style="">
<img src="<?php echo plugin_dir_url(__DIR__).'/img/woo-table-list.png';?>" width="80" style="margin-right:1rem;"/>
<div>Woo Table List </div>
<span class="wtl_table_list_tabs wtl_table_list_tabs_active" data-tab="1">Settings</span> 
<span class="wtl_table_list_tabs" data-tab="2">Read Me</span> 
<input type="hidden" class="admin_url" value="<?php echo admin_url( 'admin-ajax.php' );?>"/>
</h1>
<div class="notice is-dismissible notice-info wtl_notice"><p>Saved succesfully</p></div>
<div class="notice is-dismissible notice-error wtl_notice"><p>Settings not saved. Some error occured. Please contact administrator.</p></div>
<form method="POST" id="frmWooTableList" style="flex-direction:row;" class="wtl_table_list_tab tab_1">
  
  <div class="wtl_options_tabs">
 
    <div class="menu">
      <span class="wtl_options_btn wtl_option_active" data-tab="1">Main</span>
      <span class="wtl_options_btn" data-tab="2">Layout</span>
      <span class="wtl_options_btn" data-tab="3">Customize</span>
      <input type="hidden"  name="save_options" value="true"/>
      <input type="hidden"  name="action" value="wtl_options_ajax"/>
      <button class="button button-primary wtl_save_options">Save</button>
      <!--<input type="submit" class="button button-primary" value="Save"/>-->
    </div>
    <div class="wtl_option_tab wtl_option_tab_1">
      <h3>Enable/Disable options</h3>
      <div>
      <?php 
        foreach ( $fields as $key=>$field ){
          if ( explode(',',$field)[3] != 'draggable' ){
            $ischecked = '';
            if ( $options[$key] === '1' ){
              $ischecked = 'checked';
            }
            echo '<div class="woo_field">';
            echo '<input type="checkbox" name="woo_table_list_' .$key. '" '.$ischecked.'/>';
            echo '<label>'.explode(',',$field)[1].'</label><div><small>'.explode(',',$field)[2].'</small></div>';
            echo '</div>';
          }
        }
      ?>
      
      </div>
    </div>
    <div class="wtl_option_tab wtl_option_tab_2">
      <div  style="display:flex;flex-direction:row;width:100%">
        <!--<div style="width:20%">
          <h3 class="wtl_fields_drawer" data-width="5%">Fields</h3>
          <div class="woo_options">
            <?php 
              $n = 0;
              $current_order = explode ( ',' , $options['fields_order'] );
              foreach ( $current_order as $field ){
                $ischecked = '';
                if ( $options[$field] === '1' ){
                  $ischecked = 'checked';
                }
                $disabled = '';
                if ( $field === 'title' ){
                  $disabled = 'disabled';
                }
                echo '<div class="woo_option_draggable" data-field="'.$field.'" data-label="'.explode(',',$fields[$field])[1].'">';
                echo '<input type="checkbox" name="woo_table_list_' .$field. '" '.$ischecked.'/>';
                echo '<label class="wtl_field_droppable">'.explode(',',$fields[$field])[1].'</label>';
                echo '</div>';
                $n++;
              }
            ?>
            
          </div>
          <div><i>Drag field to the desidered column</i></div>
        </div>
            -->
        <div style="width:100%">
          <h3>Fields</h3>
          <div style="padding:1rem;border:1px solid #ddd;background:#f1f1f1;display:flex;flex-direction:row;flex-wrap:wrap;">
          <?php 
              $n = 0;
              $current_order = explode ( ',' , $options['fields_order'] );
              foreach ( $current_order as $field ){
                $ischecked = '';
                if ( $options[$field] === '1' ){
                  $ischecked = 'checked';
                }
                $disabled = '';
                if ( $field === 'title' ){
                  $disabled = 'disabled';
                }
                echo '<span class="woo_option_draggable" style="" data-field="'.$field.'" data-label="'.explode(',',$fields[$field])[1].'" title="Drag to desired column">';
                echo '<input type="checkbox" style="display:none" name="woo_table_list_' .$field. '" '.$ischecked.'/>';
                echo '<label class="wtl_field_droppable" style="width:100;display:inline-block;text-align:center;">'.explode(',',$fields[$field])[1].'</label>';
                echo '</span>';
                $n++;
              }
              echo '<span class="woo_option_draggable" data-field="meta_field" data-label="Custom Field" title="Drag to desired column">';
              echo '<label class="wtl_field_droppable">Custom Field</label>';
              echo '</span>';
            ?>
          </div>  
          <div><i>Drag fields to the desidered column</i></div>
          <h3>Columns Layout <button class="button wtl_column_add">Add column</button></h3>
          <div class="wtl_option_columns"></div>
          <div class="wtl_col_remove">Drop here to remove a column/field</div>
          <input type="hidden" name="woo_table_list_fields_order" class="woo_table_list_fields_order" value="<?php echo $options['fields_order'];?>">
          <input type="hidden" name="woo_table_list_layout" class="wtl_table_layout" value="<?php echo $options['table_layout'];?>">
        </div>
      </div>
    </div>
    
    <div class="wtl_option_tab wtl_option_tab_3">
      <h3>Custom labels and text</h3>
      <div class="woo_option_text_field">
        <label>Add to cart button text</label>
        <input type="text" name="woo_table_list_cart_text" placeholder="Add to cart text" value="<?php echo $options['cart_text'];?>"/>
        <div><small>Change Add to cart button text</small></div>
      </div>
      <div class="woo_option_text_field">
        <label>Go to Cart text</label>
        <input type="text" name="woo_table_list_cart_goto" placeholder="Add to cart text" value="<?php echo $options['cart_goto'];?>"/>
        <div><small>Go to Cart button text (used for variable products, after product has been added to cart)</small></div>
      </div>
      <div class="woo_option_text_field">
        <label>Added to cart message</label>
        <input type="text" name="woo_table_list_cart_added" placeholder="added to cart" value="<?php echo $options['cart_added'];?>"/>
        <div><small>Message displayed when a product has been added to cart. Your setting:<br/><i>_product_name_ <?php echo $options['cart_added'];?></i></small></div>
      </div>
      <div class="woo_option_text_field">
        <label>Select options text</label>
        <input type="text" name="woo_table_list_options_text" placeholder="Select options text" value="<?php echo $options['options_text'];?>"/>
        <div><small>Change Add to cart button text when a product is variable</small></div>
      </div>

      <div class="woo_option_text_field" style="display:none;">
        <label>Add Variations Link</label>
        <input type="text" name="woo_table_list_variable_text" placeholder="Variation options text" value="<?php echo $options['variable_text'];?>"/>
        <div><small>Add a variation link. Add to cart button will open variable product page.<br/>If you leave blank Add to cart button will open the variations selection.</small></div>
      </div>

      <div class="woo_option_text_field">
        <label>Sale tag text</label>
        <input type="text" name="woo_table_list_sale_text" placeholder="Sale tag text"  value="<?php echo $options['sale_text'];?>"/>
        <div><small>Change Sale tag text. Set to <strong>%</strong> to show percentage discount.</small></div>
      </div>

      <div class="woo_option_text_field">
        <label>Add Meta Field(s) Name (separate with comma)</label>
        <input type="text" name="woo_table_list_meta" placeholder="meta keys separated by comma" value="<?php echo $options['meta_fields'];?>"/>
        <div><small>You can add custom meta fields to show below the product name</small></div>
      </div>
    </div>
  </div>
</form>
<div class="wtl_table_list_tab tab_2">
  <?php
    include_once ( 'woo-table-list-readme.php' );
  ?>
</div>
<div><small><strong>Woo Table List</strong> by A. Nardone</small></div>