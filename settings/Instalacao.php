<?php
class Instalacao
{

    /**
     * Start up
     */
    public function __construct()
    {
        /** TODO 
        * Add menu and other stuff
        * add_action('admin_menu', array( $this, 'kinvoice_menu' ) );
        */
    }

    /**
    * TODO
    * Options, save budgets and much more.
    * future configurations */
    public function kinvoice_menu()
    {
        /*
        add_menu_page( 'kInvoice', 'kInvoice', 'manage_options', 'kinvoice/products.php', '', 'dashicons-images-alt' );
        
        add_submenu_page(
                  'kinvoice/products.php',
                  'Products', 
                  'Products', 
                  'manage_options', 
                  'kinvoice/products.php'
            );
        */
        
    }

    // Register Custom Post Type
    public function kinvoice_products() {

      $labels = array(
        'name'                => _x( 'Invoice Products', 'Post Type General Name', 'kinvoice' ),
        'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'kinvoice' ),
        'menu_name'           => __( 'Invoice Products', 'kinvoice' ),
        'name_admin_bar'      => __( 'Invoice Products', 'kinvoice' ),
        'parent_item_colon'   => __( 'Parent Item:', 'kinvoice' ),
        'all_items'           => __( 'All Items', 'kinvoice' ),
        'add_new_item'        => __( 'Add New Item', 'kinvoice' ),
        'add_new'             => __( 'Add New', 'kinvoice' ),
        'new_item'            => __( 'New Item', 'kinvoice' ),
        'edit_item'           => __( 'Edit Item', 'kinvoice' ),
        'update_item'         => __( 'Update Item', 'kinvoice' ),
        'view_item'           => __( 'View Item', 'kinvoice' ),
        'search_items'        => __( 'Search Item', 'kinvoice' ),
        'not_found'           => __( 'Not found', 'kinvoice' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'kinvoice' ),
      );
      $args = array(
        'label'               => __( 'kinvoice-product', 'kinvoice' ),
        'description'         => __( 'Invoice Products', 'kinvoice' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'post-formats', ),
        'taxonomies'          => array( 'kinvoice-categorie' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'http://i.imgur.com/kEW3eEc.png',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
      );
      register_post_type( 'kinvoice-product', $args );

    }


    // Register Custom Taxonomy
    public function kinvoice_categories() {

      $labels = array(
        'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'kinvoice' ),
        'singular_name'              => _x( 'Product Categorie', 'Taxonomy Singular Name', 'kinvoice' ),
        'menu_name'                  => __( 'Product Category', 'kinvoice' ),
        'all_items'                  => __( 'All Items', 'kinvoice' ),
        'parent_item'                => __( 'Parent Item', 'kinvoice' ),
        'parent_item_colon'          => __( 'Parent Item:', 'kinvoice' ),
        'new_item_name'              => __( 'New Item Name', 'kinvoice' ),
        'add_new_item'               => __( 'Add New Item', 'kinvoice' ),
        'edit_item'                  => __( 'Edit Item', 'kinvoice' ),
        'update_item'                => __( 'Update Item', 'kinvoice' ),
        'view_item'                  => __( 'View Item', 'kinvoice' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'kinvoice' ),
        'add_or_remove_items'        => __( 'Add or remove items', 'kinvoice' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'kinvoice' ),
        'popular_items'              => __( 'Popular Items', 'kinvoice' ),
        'search_items'               => __( 'Search Items', 'kinvoice' ),
        'not_found'                  => __( 'Not Found', 'kinvoice' ),
      );
      $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
      );
      register_taxonomy( 'kinvoice-categorie', array( 'kinvoice-product' ), $args );

    }

    /**
    * Create pages do kInvoice*/
    public function wpki_create_pages(){

      /* Page with products */
      $product_page = array(
        'post_title'    => 'Invoice Products',
        'post_content'  => '[ki_products]',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => get_current_user_id(),
        'page_template' => 'Invoice Products'
      );

      wp_insert_post( $product_page );


      /* Invoice Page With Selected products */
      $cart_page = array(
        'post_title'    => 'Invoice Cart',
        'post_content'  => '[ki_cart]',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => get_current_user_id()
      );

      wp_insert_post( $cart_page );


      /* Invoice Success page */
      $cart_page = array(
        'post_title'    => 'Invoice Success',
        'post_content'  => '[ki_success]',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => get_current_user_id()
      );

      wp_insert_post( $cart_page );
      

    }





    /**
    * Delete pages  kInvoice*/
    public function wpki_delete_pages(){
      /* TODO */
    }


}
