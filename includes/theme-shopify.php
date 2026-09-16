<?php 

$orders_per_page = 50;
$offset = 0;

//add_action('admin_menu', 'register_my_custom_submenu_page', 300);

function register_my_custom_submenu_page() {
    add_submenu_page( 'woocommerce', 'Shopify Orders', 'Shopify Orders', 'manage_options', 'woo-shopify-page', 'shopify_orders_page_callback' ); 
}

function shopify_orders_page_callback() {
    global $wpdb;
    global $orders_per_page;
    global $offset;

    $count = $wpdb->get_var(  "SELECT COUNT(*) FROM " . $wpdb->prefix . "platform_orders" );
    $page = isset($_GET['_page']) ? $_GET['_page'] : 0;
    $total_pages = ceil($count/$orders_per_page);
    $offset = ($page * $orders_per_page);


    $orders = getShopifyOrders();
    
    echo "PAGE = " . $page;
    echo "Total = " . $total_pages;
    echo "Offset = " . $offset;

    echo '<h3>Shopify Orders</h3>';
    echo "<table style='width:100%'>";
    ?>
        <tr>
            <th>Order Number</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Email</th>
            <th>Total</th>
        </tr>
    <?php 
    foreach( $orders as $order ) {

        $data = json_decode($order->order_json);

        ?>
        <tr>
            <td>
                #<?php echo $order->platform_order_id; ?>
            </td>
            <td><?php echo $order->order_date; ?></td>
            <td><?php echo $order->order_status; ?></td>
            <td><?php echo $order->email; ?> <a target="_blank" href="<?php echo site_url(); ?>/wp-admin/user-edit.php?user_id=<?php echo $order->wp_user_id; ?>">View User</a></td>
            <td><?php echo number_format($data->total, 2); ?></td>
        </tr>
        <?php 
    }
    echo "</table>";
    ?>

    <div class="pagination">
        <?php for( $i=0; $i<$total_pages; $i++ ) : ?>
            <a <?php if( $i == $_GET['_page'] ) : ?> class="active" <?php endif; ?> href="<?php echo site_url(); ?>/wp-admin/admin.php?page=woo-shopify-page&_page=<?php echo $i; ?>"><?php echo ($i+1); ?></a>
        <?php endfor; ?>
    </div>

    <style>
        th {
            text-align: left;
        }
        .pagination {
            margin-top: 1em;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }
        .pagination a {
            margin: 0 5px;
        }
        .pagination a.active {
            font-weight: bold;
        }
    </style>
    <?php 
}

function getShopifyOrders() {

    global $wpdb;
    global $orders_per_page;
    global $offset;




    return $wpdb->get_results(  "SELECT * FROM " . $wpdb->prefix . "platform_orders ORDER BY platform_order_id DESC LIMIT " . $offset . ", " . $orders_per_page );

}
