<h2>Previous Purchases</h2>

<?php 

$purchases = SCO_Helpers::getUserPreviousPurchases( get_current_user_id() ); 

?>

<?php if( $purchases ) : ?>
    <?php foreach( $purchases as $purchase ) : ?>
        <?php $order_details = json_decode( $purchase->order_json ); ?>
        <h3>Order Number #<?php echo $purchase->platform_order_id; ?> - <?php echo date('d F Y', strtotime($order_details->ordered_at)); ?> </h3>
       
        <?php if( $order_details ) : ?>
            <?php if( $order_details->line_items ) : ?>
                <table class="table-history">
                    <tr>
                        <th>Product</th>
                        <th style="width:130px">Price</th>
                        <th style="width:130px">Qty</th>
                    </tr>
                <?php foreach( $order_details->line_items as $item ) : ?>
                    <tr>
                        <td><?php echo $item->product; ?></td>
                        <td style="width:130px"><?php echo $item->qty; ?></td>
                        <td style="width:130px"><?php echo $item->price; ?></td>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td></td>
                        <th class="total-col" >Total</th>
                        <td><?php echo $order_details->total; ?></td>
                        
                    </tr>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    
    <?php endforeach; ?>
<?php else : ?>
    <p>We have no historical purchase history on Shopify for you.</p>
<?php endif; ?>
