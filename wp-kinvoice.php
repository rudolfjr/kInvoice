<?php
/*
Plugin Name: kInvoice
Plugin URI: http://rudolfjr.com/
Description: Simple way to recive invoices in you email, more stuff will come with updates :)
Author: Rudolf Kroker Junior
Author URI: http://rudolfjr.com/
Text Domain: kinvoice
Domain Path: /languages/
Version: 0.1a
*/

/*  Copyright 2007-2015 Rudolf Kroker Junior (email: oi at rudolfjr.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
defined( 'ABSPATH' ) or die ( 'Not allowed!' ); 

define( 'WPKI_VERSION', '1.0' );
define( 'WPKI_REQUIRED_WP_VERSION', '4.0' );
define( 'WPKI_PLUGIN', __FILE__ );
define( 'WPKI_PLUGIN_BASENAME', plugin_basename( WPKI_PLUGIN ) );
define( 'WPKI_PLUGIN_NAME', trim( dirname( WPKI_PLUGIN_BASENAME ), '/' ) );
define( 'WPKI_PLUGIN_DIR', untrailingslashit( dirname( WPKI_PLUGIN ) ) );

require_once WPKI_PLUGIN_DIR . '/settings/Instalacao.php';
require_once WPKI_PLUGIN_DIR . '/settings/Shortcodes.php';
require_once WPKI_PLUGIN_DIR . '/settings/Database.php';

if( is_admin() ){

    /** Create necessery pages */
    register_activation_hook( __FILE__, array('Instalacao', 'wpki_create_pages') );
    /** Delete generated pages */
    register_deactivation_hook(__FILE__, array('Instalacao', 'wpki_delete_pages') );

}

/**
* create custom post and custom taxonomy */
add_action( 'init', array('Instalacao', 'kinvoice_products'), 0 );
add_action( 'init', array('Instalacao', 'kinvoice_categories'), 0 );

/**
* when active plug-in or change theme reset the rewrite rules
*/

function rewrite_flush() {
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'rewrite_flush' );
add_action( 'after_switch_theme', 'rewrite_flush' );


/** load stuff in pages with shortcodes */
add_shortcode( 'ki_products', array('Shortcodes', 'wpki_get_products') );
add_shortcode( 'ki_cart', array('Shortcodes', 'wpki_get_cart') );
add_shortcode( 'ki_success', array('Shortcodes', 'wpki_success') );

/**
* show product invoice single.php
* TODO - Make class for that
*/

add_filter('template_include', 'wpki_single');

function wpki_single( $template ){

    /* load the page if dont find */
    if(is_singular('kinvoice-product') && 'single-kinvoice-product.php' != $template ){
        $template = WPKI_PLUGIN_DIR . '/pages/single-kinvoice-product.php';
    }

    return $template;
}


/**
* TODO - Make this better, and show in WP-ADMIN 
*/

$database = new Database;

register_activation_hook( __FILE__, array($database, 'wpki_create_tables') );
register_deactivation_hook(__FILE__, array($database, 'wpki_destroy_tables') );


/**
* check the POST to do one of the fuctions above
*/

if($_POST && isset($_POST['type'])){
   switch ($_POST['type']) {
        case 'save':
            $database->save();
            break;
        case 'edit':
            $database->edit();
            break;
        case 'delete':
            $database->delete();
            break;
        case 'ok':
            $database->send();
            break;
    }

}


/**
* add css and js
*/
/**
 * Proper way to enqueue scripts and styles
 */
function kinvoice_scripts() {
    wp_enqueue_style( 'kinvoice-style', plugins_url() . '/kinvoice/pages/assets/css/style.css' );
    #wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'kinvoice_scripts' );