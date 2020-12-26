//Ajax
jQuery(document).ready(
    function () {

        jQuery(document).on(
            'keyup', '.woocommerce-cart-form__contents .input-text ', function () {    
                var cart_item_key = jQuery(this).closest('tr').attr('data-cartitemkey');
                var gift_message = jQuery(this).val();
        
                jQuery.ajax(
                    {
                        url : frontend_ajax_object.ajaxurl,
                        type : 'post',
                        data : {
                            action : 'save_gift_message_in_cart',
                            cart_item_key: cart_item_key,
                            gift_message: gift_message,
                        },
                        success : function ( data ) {
                            // data contains HTML inputs to display
                        }
                    }
                );
            }
        );
    
    }
)