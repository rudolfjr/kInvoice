<?php
class Shortcodes
{

    /**
     * Start up
     */
    public function __construct()
    {

    }
    /**
    * Get the products */
    public function wpki_get_products(){

        global $post;
        
        $args = array(
            'post_type'        => 'kinvoice-product',
            'post_status'      => 'publish'
        );
        $posts_array = get_posts( $args );

        require_once WPKI_PLUGIN_DIR . '/pages/products.php';
    }


    /**
    * Get the products */
    public function wpki_get_cart(){

        global $wpdb;

        $table_cart = $wpdb->prefix . 'wpki_invoice';
        $table_itens = $wpdb->prefix . 'wpki_itens';
        $table_products = $wpdb->prefix . 'posts';

        $current_invoice = $wpdb->get_results( "SELECT * FROM $table_cart WHERE status <> 'enviado' ORDER BY ID DESC LIMIT 1" );

        $products = $wpdb->get_results( "SELECT $table_itens.qtd, $table_itens.cart, $table_itens.product, $table_itens.ID as ID_ITEM, $table_products.* FROM $table_itens INNER JOIN $table_products ON $table_products.ID = $table_itens.product WHERE $table_itens.cart = {$current_invoice[0]->ID} ORDER BY $table_products.post_title ASC" );

        require_once WPKI_PLUGIN_DIR . '/pages/cart.php';
      
    }

    /**
    * Success msg */
    public function wpki_success(){
        return __( 'Your invoice was sent, thank you.', 'kinvoice' );
    }

}