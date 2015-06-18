<?php
class Database
{


    public function __construct()
    {

    }    

    
    /** create necessery tables */
    public function wpki_create_tables () {

        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'wpki_invoice';

        $sql = "CREATE TABLE $table_name (

            ID int NOT NULL AUTO_INCREMENT,
            id_user int(10) NOT NULL,
            ip_user varchar(500) NOT NULL,
            status varchar(500) NOT NULL,
            UNIQUE KEY ID (id)

        ) $charset_collate;";

        dbDelta( $sql );



        $table_name = $wpdb->prefix . 'wpki_itens';

        $sql = "CREATE TABLE $table_name (

            ID int NOT NULL AUTO_INCREMENT,
            cart int(10) NOT NULL,
            product int(10) NOT NULL,
            qtd int(10) NOT NULL,
            UNIQUE KEY ID (id)

        ) $charset_collate;";

        dbDelta( $sql );


    }

    /* delete created tables on activation plug-in*/
    public function wpki_destroy_tables () {

        global $wpdb;
        $table = $wpdb->prefix."wpki_invoice";
        $wpdb->query("DROP TABLE IF EXISTS $table");

        $table = $wpdb->prefix."wpki_itens";
        $wpdb->query("DROP TABLE IF EXISTS $table");

    }

    /* create invoice */
    public function save(){

        
        $ip = $this->userIp();

        $exists_cart = $this->currentCart();

        global $wpdb;

        $table_invoice = $wpdb->prefix."wpki_invoice";
        $table_itens = $wpdb->prefix."wpki_itens";

        /* if exists save product */
        if($exists_cart){

            $cart = $this->currentProduct($_POST['id'], $exists_cart);

            if($cart->ID){

                $qtd = $cart->qtd + $_POST['qtd'];

                $wpdb->update( 
                    $table_itens, 
                    array( 
                        'qtd' => $qtd,  // integer
                    ), 
                    array( 'ID' => $cart->ID ), 
                    array( 
                        '%d'    // value
                    ), 
                    array( '%d' ) 
                );

            }else{
                $wpdb->insert( 
                    $table_itens, 
                    array( 
                        'cart' => $exists_cart, 
                        'product' => $_POST['id'], 
                        'qtd' => $_POST['qtd']
                    ), 
                    array( 
                        '%d', 
                        '%d', 
                        '%d' 
                    ) 
                );
            }
            

        /* if cart invoice dont exists, create new one */
        }else{

            $wpdb->insert( 
                $table_invoice, 
                array( 
                    'id_user' => get_current_user_id(), 
                    'ip_user' => $ip, 
                    'status' => 'aberto'
                ), 
                array( 
                    '%d', 
                    '%s', 
                    '%s' 
                ) 
            );


            $wpdb->insert( 
                $table_itens, 
                array( 
                    'cart' => $wpdb->insert_id, 
                    'product' => $_POST['id'], 
                    'qtd' => $_POST['qtd']
                ), 
                array( 
                    '%d', 
                    '%d', 
                    '%d'
                ) 
            );
        }

        
        add_filter('the_content', array($this, 'wpki_add_product') );

    }

   

    /* editar cart invoice */
    public function edit(){

        /*TODO - make this work next plug-in update*/
    }

    /* delete product in invoice */
    public function delete(){

        global $wpdb;

        $table_name = $wpdb->prefix . 'wpki_itens';

        $wpdb->delete( $table_name, array( 'ID' => $_POST['product'], 'cart' => $_POST['cart'] ), array( '%d','%d' ) );

        add_filter('the_content', array($this, 'wpki_delete_product') );
    }

    /* send invoice to email */
    public function send(){

        global $wpdb;

        $table_name = $wpdb->prefix . 'wpki_invoice';

        $wpdb->update( 
                    $table_name, 
                    array( 
                        'status' => 'enviado',  // string
                    ), 
                    array( 'ID' => $_POST['invoice'] ), 
                    array( 
                        '%s'    // value
                    ), 
                    array( '%d' ) 
                );

        add_action('plugins_loaded', array($this, 'wpki_send_email'));

        add_action('plugins_loaded', array($this, 'wpki_redirect_success'));
        

    }

    /* check if exists a open invoice */
    private function currentCart(){

        global $wpdb;

        $table_name = $wpdb->prefix . 'wpki_invoice';

        $check = $wpdb->get_var( "SELECT ID FROM $table_name WHERE id_user = ".get_current_user_id()." AND status <> 'enviado' OR ip_user = '".$this->userIp()."' AND status <> 'enviado' ORDER BY ID DESC LIMIT 1" );

        return $check;
    }

    /* check if already exists the product */
    private function currentProduct($produto, $cart){

        global $wpdb;

        $table_name = $wpdb->prefix . 'wpki_itens';

        $check = $wpdb->get_results( "SELECT * FROM $table_name WHERE product = $produto AND cart = $cart LIMIT 1" );

        return $check[0];

    }


    /* return the ip user */
    private function userIp(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }



    /* success product add */
    public function wpki_add_product($content){
        return $content . '<br/>' . _e( 'You add the product with success', 'kinvoice' );
    }

    /* success product delete */
    public function wpki_delete_product($content){
        return $content . '<br/>' . _e( 'You delete the product with success', 'kinvoice' );
    }

    /* send email */
    public function wpki_send_email(){

        add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );

        $msg = '<h1>' . __( 'You recive a new Invoice from your website.', 'kinvoice' ) . '</h1><hr/>';
        $msg .= __( 'Your name', 'kinvoice' ) . ': ' . $_POST['name'] . '<br/>';
        $msg .= __( 'Your telephone', 'kinvoice' ) . ': ' . $_POST['phone'] . '<br/>';
        $msg .= __( 'Your e-mail', 'kinvoice' ). ': ' . $_POST['email'] . '<br/>';
        $msg .= __( 'Observation', 'kinvoice' ) . ': ' . $_POST['obs'] . '<br/>';
        $msg .= '<h1>' . __( 'Invoice selected products', 'kinvoice' ) . '</h1><hr/>';
        $msg .= '<table>';


        $msg .= '<tr>';
        $msg .= '<td><strong>'. __( 'Product Name', 'kinvoice' ).'</strong></td>';
        $msg .= '<td><strong>'. __( 'Quantity', 'kinvoice' ).'</strong></td>';
        $msg .= '<td><strong>'. __( 'Product Link', 'kinvoice' ).'</strong></td>';
        $msg .= '</tr>';

        global $wpdb;

        $table_cart = $wpdb->prefix . 'wpki_invoice';
        $table_itens = $wpdb->prefix . 'wpki_itens';
        $table_products = $wpdb->prefix . 'posts';

        $current_invoice = $wpdb->get_results( "SELECT * FROM $table_cart WHERE status = 'enviado' ORDER BY ID DESC LIMIT 1" );

        $products = $wpdb->get_results( "SELECT $table_itens.qtd, $table_products.post_title, $table_products.post_name FROM $table_itens INNER JOIN $table_products ON $table_products.ID = $table_itens.product WHERE $table_itens.cart = {$current_invoice[0]->ID} ORDER BY $table_products.post_title ASC" );


        foreach ($products as $p) {
            $msg .= '<tr>';
                $msg .= '<td>' . $p->post_title . '</td>';
                $msg .= '<td>' . $p->qtd . '</td>';
                $msg .= '<td><a target="_blank" href="'.site_url('kinvoice-product/'.$p->post_name).'"> ' . __( 'See Online Product', 'kinvoice' ) . '</a> </td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';




        $email = get_bloginfo('admin_email');
        
        wp_mail(
            $email,
            __( 'You recive a new Invoice from your website.', 'kinvoice' ),
            $msg
        );

        remove_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
    }

    /* email in html format */
    public function set_html_content_type() {
        return 'text/html';
    }


    /* redirect to success page when sent the invoice */
    public function wpki_redirect_success(){
        wp_redirect( site_url('invoice-success') );
        exit;
    }

}
