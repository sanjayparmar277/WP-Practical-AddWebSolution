<?php
/**
 * Plugin Name: WP Add Web Solution
 * Plugin URI: #
 * Description: Wordpress Practical Plugin for add column in order table etc..
 * Author: Sanjay Parmar
 * version: 1.0.0
 * Author URI: #
 *
 * PHP version 7.3.21
 *
 * @category Plugin
 * @package  WP_AddWeb_Solution_Plugin
 * @author   Sanjay Parmar <sanjayparmar277@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://addwebsolution.com/
 */

define('WP_ADDWEBSOLUTION_VERSION', 1.0);
define('WP_ADDWEBSOLUTION_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_ADDWEBSOLUTION_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_ADDWEBSOLUTION_PLUGIN_BASENAME', plugin_basename(__FILE__));

register_activation_hook(__FILE__, 'pluginActivation');
register_activation_hook(__FILE__, 'pluginDeactivation');

add_action( 
    'wp_enqueue_scripts', 
    'loadAssets' 
);

add_filter(
    'manage_edit-product_columns', 
    'adminProductsVisibilityColumn', 
    9999
);
add_action(
    'manage_product_posts_custom_column', 
    'adminProductsOrdersColumnContent', 
    10, 2
);
add_filter(
    'manage_edit-product_sortable_columns', 
    'adminProductsOrdersColumnSortable'
);
add_filter(
    'manage_edit-product_columns', 
    'changeColumnOrder'
);
add_action(
    'woocommerce_email_order_meta', 
    'sendDiscountCode', 
    10, 3
);
add_filter(
    'woocommerce_locate_template', 
    'overrideWCTemplates', 
    1, 3
);
add_action(
    'wp_ajax_nopriv_save_gift_message_in_cart', 
    'saveGiftMessageCartItem'
);
add_action(
    'wp_ajax_save_gift_message_in_cart',  
    'saveGiftMessageCartItem'
);
add_filter(
    'woocommerce_get_cart_item_from_session', 
    'extractFromSessionStoreInCart', 
    1, 3
);
add_action(
    'woocommerce_before_cart_item_quantity_zero', 
    'removeGiftMessageFromCart', 1, 1
);
add_filter(
    'woocommerce_checkout_cart_item_quantity', 
    'displayGiftMessagetoCartCheckout', 
    1, 3
);
add_action(
    'woocommerce_add_order_item_meta', 
    'addGiftMessagetoOrderItem', 
    1, 2
);

if (!function_exists('pluginActivation')) {
    /**
     * Activation Hook defined
     *
     * @return void
     **/
    function pluginActivation()
    {
        // Plugin Activation logic
        if (!class_exists('WooCommerce') ) {
            deactivate_plugins(WP_ADDWEBSOLUTION_PLUGIN_BASENAME);
            wp_die(__('Please install and Activate WooCommerce.', 'WP_AddWeb_Solution_Plugin'), 'Plugin dependency check', array( 'back_link' => true ));
        }
    }
}

if (!function_exists('pluginDeactivation')) {
    /**
     * Deactivation Hook defined
     *
     * @return void
     **/
    function pluginDeactivation()
    {
        // Plugin Deactivation logic here
    }
}

if (!function_exists('loadAssets')) {
    /**
     * Load Assests from here
     *
     * @return void
     **/
    function loadAssets()
    {
        wp_enqueue_script('frontend-ajax', WP_ADDWEBSOLUTION_PLUGIN_URL . 'assets/js/custom.js', array('jquery'), null, true);
        wp_localize_script(
            'frontend-ajax', 'frontend_ajax_object',
            array( 
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );
    }
}

if (!function_exists('sendDiscountCode')) {
    /**
     * Display discount code on the Email..
     * PHP Custom Feild Value
     *
     * @param $order_obj     Order Object
     * @param $sent_to_admin If this email is for administrator or for a customer
     * @param $plain_text    HTML or Plain text
     *
     * @return void
     **/
    function sendDiscountCode( $order_obj, $sent_to_admin, $plain_text )
    {

        if ($sent_to_admin != 1) {
        
            $seed = str_split(
                'abcdefghijklmnopqrstuvwxyz'
                     .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                .'0123456789'
            );
            shuffle($seed);
            $discountCode = '';
            foreach (array_rand($seed, 5) as $k) {
                $discountCode .= $seed[$k];
            }
            
            $couponCode = strtoupper($discountCode);
            $amount = '10';
            $discount_type = 'fixed_cart';

            $coupon = array(
            'post_title' => $couponCode,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon');

            $new_coupon_id = wp_insert_post($coupon);

            update_post_meta($new_coupon_id, 'discount_type', $discount_type);
            update_post_meta($new_coupon_id, 'coupon_amount', $amount);
            update_post_meta($new_coupon_id, 'individual_use', 'no');
            update_post_meta($new_coupon_id, 'product_ids', '');
            update_post_meta($new_coupon_id, 'exclude_product_ids', '');
            update_post_meta($new_coupon_id, 'usage_limit', 1);
            update_post_meta($new_coupon_id, 'expiry_date', '');
            update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
            update_post_meta($new_coupon_id, 'free_shipping', 'no');

            if ($plain_text === false ) {
         
                echo '<h2>Discount Code for Next Purchase</h2>
				<p>
					<b>Note:</b> This Discount code you can use 
					for next purchage but only onc time usable.<br/>
					<strong>Discount Code:</strong> ' . $couponCode.'
				</p>';
         
            } else {
                echo "Discount Code for Next Purchase\n
	            <b>Note:</b> This Discount code you can use for next 
	            purchage but only onc time usable.\n
				Discount Code: $discountCode";    
         
            }
        }
    }
}

if (!function_exists('adminProductsVisibilityColumn')) {
    /**
     * Total orders Column visibility
     *
     * @param $columns Table column
     *
     * @return void
     **/
    function adminProductsVisibilityColumn( $columns )
    {
        $columns['total_orders'] = 'Total Orders';
        return $columns;
    }
}
 
if (!function_exists('adminProductsOrdersColumnContent')) {
    /**
     * Total orders Column Content
     *
     * @param $column     table column
     * @param $product_id product id return
     *
     * @return void
     **/
    function adminProductsOrdersColumnContent( $column, $product_id )
    {
        if ($column == 'total_orders' ) {
            echo getOrdersFromProductIds($product_id);        
        }
    }
}

if (!function_exists('getOrdersFromProductIds')) {
    /**
     * Get total orders by product id
     *
     * @param $product_id product id return
     *
     * @return void
     **/
    function getOrdersFromProductIds( $product_id )
    {
        global $wpdb;
        
        // Define HERE the orders status to include in
        $orders_statuses = "'wc-completed', 'wc-processing', 'wc-on-hold'";

        // Get All defined statuses Orders IDs for a defined product ID
        return $wpdb->get_var(
            "SELECT COUNT(*)
	        FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim, 
	             {$wpdb->prefix}woocommerce_order_items as woi, 
	             {$wpdb->prefix}posts as p
	        WHERE  woi.order_item_id = woim.order_item_id
	        AND woi.order_id = p.ID
	        AND p.post_status IN ( $orders_statuses )
	        AND woim.meta_key IN ( '_product_id', '_variation_id' )
	        AND woim.meta_value LIKE '$product_id'
	        ORDER BY woi.order_item_id DESC"
        );
    }
}

if (!function_exists('adminProductsOrdersColumnSortable')) {
    /**
     * Total orders Column Sortable
     *
     * @param $columns Table column 
     *
     * @return void
     **/
    function adminProductsOrdersColumnSortable( $columns )
    {
        $columns['total_orders'] = 'total_orders';
        return $columns;
    }
}

if (!function_exists('changeColumnOrder')) {
    /**
     * All column order change in listing
     *
     * @param $product_columns Product Table column 
     *
     * @return void
     **/
    function changeColumnOrder( $product_columns )
    {
        return array(
        'cb' => '<input type="checkbox" />',
        'thumb' => '<span class="wc-image tips" data-tip="Image">Image</span>',
        'name' => 'Name',
        'date' => 'Date',
        'sku' => 'SKU',
        'is_in_stock' => 'Stock',
        'price' => 'Price',
        'total_orders' => 'Total Orders',
        'product_cat' => 'Categories',
        'product_tag' => 'Tags',
        ); 
    }
}

if (!function_exists('overrideWCTemplates')) {
    /**
     * Override Woocommerce templates file from our plugin
     *
     * @param $template      result of the wp core function locate_template
     * @param $template_name that is only the filename
     * @param $template_path that is the woocommerce path for templates
     *
     * @return void
     **/
    function overrideWCTemplates( $template, $template_name, $template_path )
    {
        global $woocommerce;
        $_template = $template;
        if (! $template_path ) { 
            $template_path = $woocommerce->template_url;
        }
     
        $plugin_path  = untrailingslashit(WP_ADDWEBSOLUTION_PLUGIN_DIR)  . '/woocommerce/template/';
     
        // Look within passed path within the theme - this is priority
        $template = locate_template(
            array(
            $template_path . $template_name,
            $template_name
            )
        );
     
        if (! $template && file_exists($plugin_path . $template_name) ) {
            $template = $plugin_path . $template_name;
        }
     
        if (! $template ) {
            $template = $_template;
        }

        return $template;
    }
}

/*function ajaxCallAfterUpdateCart()
{
    ?>
<script type="text/javascript">
    jQuery(document).on('keyup', '.woocommerce-cart-form__contents .input-text ', function() {    
        var cart_item_key = jQuery(this).closest('tr').attr('data-cartitemkey');
        var gift_message = jQuery(this).val();
        console.log(cart_item_key);
        console.log(gift_message);

        jQuery.ajax({
            url : '<?php echo admin_url('admin-ajax.php'); ?>',
            type : 'post',
            data : {
                action : 'save_gift_message_in_cart',
                cart_item_key: cart_item_key,
                gift_message: gift_message,
            },
            success : function( data ) {
                // data contains HTML inputs to display
            }
        });
    });
</script>
    <?php
}*/

if (!function_exists('saveGiftMessageCartItem')) {
    /**
     * Store gift message in cart session
     *
     * @return void
     **/
    function saveGiftMessageCartItem()
    {
        global $woocommerce;

        //echo "<pre>"; print_r($_POST);
        $woocommerce->cart->cart_contents[$_POST['cart_item_key']]['gift_message'] = $_POST['gift_message'];
        $woocommerce->cart->set_session();
        die;
    }
}

if (!function_exists('extractFromSessionStoreInCart')) {
    /**
     * Extract Gift Message from WooCommerce Session and Insert it into Cart Object
     *
     * @param $item   cart item data
     * @param $values cart custom value
     * @param $key    cart key
     *
     * @return void
     **/
    function extractFromSessionStoreInCart($item, $values, $key)
    {
        if (array_key_exists('gift_message', $values) ) {
            $item['gift_message'] = $values['gift_message'];
        }       
        return $item;
    }
}

if (!function_exists('displayGiftMessagetoCartCheckout')) {
    /**
     * Display Gift Message on Cart and Checkout page
     *
     * @param $product_name  get product name
     * @param $values        values of cart item
     * @param $cart_item_key cart item key
     *
     * @return void
     **/
    function displayGiftMessagetoCartCheckout($product_name, $values, $cart_item_key )
    {
        if ($values['gift_message']) {
            $return_string = $product_name . "</a><dl class='variation'>";
            $return_string .= "<table class='wdm_options_table' id='" . $values['product_id'] . "'>";
            $return_string .= "<tr><td style='padding: 0'>Message: " . $values['gift_message'] . "</td></tr>";
            $return_string .= "</table></dl>"; 
            return $return_string;
        } else {
            return $product_name;
        }
    }
}

if (!function_exists('addGiftMessagetoOrderItem')) {
    /**
     * Add Gift Message as Metadata to the Order Items
     *
     * @param $item_id cart item id
     * @param $values  values of cart item     
     *
     * @return void
     **/
    function addGiftMessagetoOrderItem($item_id, $values)
    {
        global $woocommerce,$wpdb;
        $user_custom_values = $values['gift_message'];
        if (!empty($user_custom_values)) {
            wc_add_order_item_meta($item_id, 'gift_message', $user_custom_values);  
        }
    }
}

if (!function_exists('removeGiftMessageFromCart')) {
    /**
     * Remove Gift Message, if Product is Removed from Cart
     *
     * @param $cart_item_key cart item key
     *
     * @return void
     **/
    function removeGiftMessageFromCart($cart_item_key)
    {
        global $woocommerce;
        $cart = $woocommerce->cart->get_cart();
        foreach ( $cart as $key => $values) {
            if ($values['gift_message'] == $cart_item_key ) {
                unset($woocommerce->cart->cart_contents[ $key ]);
            }
        }
    }
}

