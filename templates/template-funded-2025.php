<?php
/**
 * Template Name: Get Funded 2026
 *
 * @package WordPress
 * @subpackage Sam & Co
 */


$indexes = get_field('key_labels');



?>
<?php get_header(); ?>


<?php woocommerce_output_all_notices(); ?>

<?php $_content = get_field('header'); ?>
<section class="masthead masthead--hero  section section--dark">
        <div class="container">
            <article>
                <h1><?php echo $_content['header_title']; ?></h1>
                <?php echo wpautop( $_content['header_content'] ); ?>
                <div class="button-group">
                <?php if( $_content['header_button_url'] ) : ?>
                    <a href="<?php echo $_content['header_button_url']; ?>" class="button"><?php echo $_content['header_button_label']; ?></a>
                <?php endif; ?>
                <?php if( $_content['header_button_url_2'] ) : ?>
                    <a href="<?php echo $_content['header_button_url_2']; ?>" class="button button--white"><?php echo $_content['header_button_label_2']; ?></a>
                <?php endif; ?>
                </div>
            </article>
            <figure>
                <img src="<?php echo $_content['header_image']; ?>" loading="lazy" alt="<?php echo $_content['header_title']; ?>" />
            </figure>
        </div>

        <div class="gfx gfx--grid"></div>
        <div class="gfx gfx--grid gfx--grid-alt"></div>
</section>


<?php $matix = get_field('pricing_matrix_options'); ?>
<section class="section section--gfx section--matrixv2">
    <div class="container container--s">
        <header class="section__title">
            <h2><?php echo $matix['title']; ?></h2>
            <?php echo wpautop( $matix['content'] ); ?>
        </header>
    </div>
    <div class="container">
        

        <div class="matrix-v2">

            <header class="matrix-v2__header">
                <div class="matrix-v2__headerItem">
                    <h3>I would like to:</h3>
                    <ul>
                        <li><button class="button  button--small " data-target="getQualfied">Get Qualified</button></li>
                        <li><button class="button  button--small active" data-target="getFunded" >Get Funded</button></li>
                    </ul>
                </div>

                <div class="matrix-v2__headerItem" >
                    <h3> Account Balance:</h3>
                    <ul class="getQualified-options" hidden>
                        <?php while( have_rows('get_qualified_services') ) : the_row(); 
                            $service_amount = get_sub_field('starting_balance'); ?>
                            <li><button class="button  button--small" data-amount="<?php echo $service_amount; ?>"><?php echo '$' . number_format($service_amount); ?></button></li>
                        <?php endwhile; ?>
                    </ul>
                    <ul class="getFunded-options " >
                        <?php while( have_rows('get_funded_services') ) : the_row(); 
                            $service_amount = get_sub_field('starting_balance'); ?>
                            <li><button class="button  button--small" data-amount="<?php echo $service_amount; ?>"><?php echo '$' . number_format($service_amount); ?></button></li>
                        <?php endwhile; ?>
                    </ul>

                </div>
            </header>
                
            <div class="matrix-v2__frame">

               

                <div class="matrix-v2__nav matrix-v2__nav--right" aria-hidden="true">
                    <button type="button" class="matrix-v2__arrow" data-direction="right">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="matrix-v2__grid" id="getQualfied-matrix">

                    <div class="matrix-v2__keys">
                        <?php if( $indexes ) : ?>
                            <?php foreach( $indexes as $index ) : ?>
                                <div class="matrix-v2__item">
                                    <header>
                                        <?php if( $index['icon'] ) : ?>
                                         <img src="<?php echo $index['icon']['url']; ?>" alt="<?php echo $index['label']; ?>" />
                                        <?php endif; ?>
                                        <h4><?php echo $index['label']; ?>
                                            <?php if( $index['description'] ) : ?>
                                                <span class="tooltip" data-tooltip="<?php echo esc_attr( $index['description'] ); ?>">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            <?php endif; ?>
                                        </h4>
                                    </header>
                                   
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="matrix-v2__column" id="getQualfied-data" hidden>
                        <?php while( have_rows('get_qualified_services') ) : the_row(); ?>
                            <div class="matrix-service" data-amount="<?php echo get_sub_field('starting_balance'); ?>">
                                <div class="matrix-service__wrapper">
                                    <div class="matrix-service__title">
                                        <div>
                                            <h3>Account Balance</h3>
                                            <p>$<?php echo number_format(get_sub_field('starting_balance')); ?></p>
                                        </div>

                                            <div class="matrix-service__titleCTA">
                                                <p><strong>Setup Cost</strong></p>
                                                <p class="matrix-service__cost">£<?php echo number_format(get_sub_field('setup_fee')); ?></p>

                                                <p><strong>Plus 11 monthly payments of</strong></p>
                                                <p class="matrix-service__cost">£<?php echo number_format(get_sub_field('cost'),2); ?>
                                                </p>
                                                <small>All prices exclude VAT</small>
                                                
                                                <a class="button" href="?add-to-cart=<?php echo get_sub_field('woocommerce_product'); ?>&quantity=1&buy_now=1"
                                                    <?php if( get_sub_field('requires_pop_up_notcie') ) : ?> data-requiresPopUp="true" <?php endif; ?>
                                                >Buy Now</a>
                                            </div>

                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[0]['label']; ?></span>
                                        <p><?php the_sub_field('funding_type'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[1]['label']; ?></span>
                                        <p>$<?php echo number_format(get_sub_field('maximum_daily_loss')); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[2]['label']; ?></span>
                                        <p>$<?php echo number_format(get_sub_field('max_loss')); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[3]['label']; ?></span>
                                        <p><?php the_sub_field('profit_share'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[4]['label']; ?></span>
                                        <p><?php the_sub_field('profit_builder'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[5]['label']; ?></span>
                                        <p><?php the_sub_field('consistency_rule'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[6]['label']; ?></span>
                                        <p><?php the_sub_field('leverage_up_to'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[7]['label']; ?></span>
                                        <p><?php the_sub_field('payout_frequency'); ?></p>
                                    </div>
                                </div>

                                
                                <div class="matrix-service__footer matrix-service__footer--alt">
                                    <p><strong>Setup Cost</strong></p>
                                    <p class="matrix-service__cost">£<?php echo number_format(get_sub_field('setup_fee')); ?></p>

                                    <p><strong>Plus 11 monthly payments of</strong></p>
                                    <p class="matrix-service__cost">£<?php echo number_format(get_sub_field('cost'),2); ?>
                                    </p>
                                    <small>All prices exclude VAT</small>
                                    
                                    <a class="button" href="?add-to-cart=<?php echo get_sub_field('woocommerce_product'); ?>&quantity=1&buy_now=1"
                                        <?php if( get_sub_field('requires_pop_up_notcie') ) : ?> data-requiresPopUp="true" <?php endif; ?>
                                    >Buy Now</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                     <div class="matrix-v2__column" id="getFunded-data" >
                        <?php while( have_rows('get_funded_services') ) : the_row(); ?>
                            <div class="matrix-service"  data-amount="<?php echo get_sub_field('starting_balance'); ?>">
                                <div class="matrix-service__wrapper">
                                    <div class="matrix-service__title">
                                        <div>
                                        <h3>Account Balance</h3>
                                        <p>$<?php echo number_format(get_sub_field('starting_balance')); ?></p>
                                        </div>

                                            <div class="matrix-service__titleCTA">
                                                  <p><strong>Total Setup Cost</strong>
                                                    <p class="matrix-service__cost">£<?php echo number_format(get_sub_field('setup_fee'), 2); ?>
                                                    
                                                    </p>
                                                    <small>Setup fee of £<?php echo number_format(get_sub_field('setup_fee')); ?> plus Monthly cost of £<?php echo number_format(get_sub_field('cost'),2); ?>.
                                                        All prices exclude VAT</small>
                                                </p>

                                                
                                                <a class="button" href="?add-to-cart=<?php echo get_sub_field('woocommerce_product'); ?>&quantity=1&buy_now=1"
                                                    <?php if( get_sub_field('requires_pop_up_notcie') ) : ?> data-requiresPopUp="true" <?php endif; ?>
                                                >Buy Now</a>
                                            </div>

                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[0]['label']; ?></span>
                                        <p><?php the_sub_field('funding_type'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[1]['label']; ?></span>
                                        <p>$<?php echo number_format(get_sub_field('maximum_daily_loss')); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[2]['label']; ?></span>
                                        <p>$<?php echo number_format(get_sub_field('max_loss')); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[3]['label']; ?></span>
                                        <p><?php the_sub_field('profit_share'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[4]['label']; ?></span>
                                        <p><?php the_sub_field('profit_builder'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[5]['label']; ?></span>
                                        <p><?php the_sub_field('consistency_rule'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[6]['label']; ?></span>
                                        <p><?php the_sub_field('leverage_up_to'); ?></p>
                                    </div>
                                    <div class="matrix-service__item">
                                        <span><?php echo $indexes[7]['label']; ?></span>
                                        <p><?php the_sub_field('payout_frequency'); ?></p>
                                    </div>
                                </div>

                                <div class="matrix-service__footer matrix-service__footer--alt">
                                    <p><strong>Total Setup Cost</strong>
                                        <p class="matrix-service__cost">£<?php echo number_format(get_sub_field('setup_fee'), 2); ?>
                                           
                                        </p>
                                         <small>Setup fee of £<?php echo number_format(get_sub_field('setup_fee')); ?> plus Monthly cost of £<?php echo number_format(get_sub_field('cost'),2); ?>.
                                            All prices exclude VAT</small>
                                    </p>

                           
                                    
                                    <a class="button" href="?add-to-cart=<?php echo get_sub_field('woocommerce_product'); ?>&quantity=1&buy_now=1"
                                        <?php if( get_sub_field('requires_pop_up_notcie') ) : ?> data-requiresPopUp="true" <?php endif; ?>
                                    >Buy Now</a>
                                </div>

                            </div>
                        <?php endwhile; ?>
                    </div>


                </div>  



            

        </div>
    

    </div>
 </section>



<?php /*
<?php $matix = get_field('pricing_matrix_options'); ?>
<section class="section section--gfx section--matrix">
    <div class="container container--s">
        <header class="section__title">
        <h2><?php echo $matix['title']; ?></h2>
                <?php echo wpautop( $matix['content'] ); ?>
        </header> 

        
        <div class="trade-matrix">

            <div class="table--headers">

                <div class="table-filter">
                    <header>
                        <h3>Course Format:</h3>
                    </header>
                    <div class="table-filter__opts">
                        <a href="#" class="button button--person">In-Person</a>
                        <a href="#" class="button active button--online">Online</a>
                    </div>
                </div>

                <div class="table-filter table-filter--amount">
                    <header>
                        <h3>Starting Balance:</h3>
                    </header>
                    <div class="table-filter__opts">
                        <a href="#" class="button button-tenk">$10,000</a>
                        <a href="#" class="button button-twentyk active">$25,000</a>
                        <a href="#" class="button button-fiftyk">$50,000</a>
                    </div>
                </div>

                <div class="table-filter">
                    <dl class="opt-online-10 hidden opt-filter">
                        <dt>Set-up Fee</dt>
                        <dd>£199 (+VAT)</dd>
                        <dt>Starting Monthly Fee</dt>
                        <dd>£99 (+VAT)</dd>
                    </dl>
                    <dl class="opt-online-25 opt-filter">
                        <dt>Set-up Fee</dt>
                        <dd>£249 (+VAT)</dd>
                        <dt>Starting Monthly Fee</dt>
                        <dd>£149 (+VAT)</dd>
                    </dl>
                    <dl class="opt-online-50 hidden opt-filter">
                        <dt>Set-up Fee</dt>
                        <dd>£249 (+VAT)</dd>
                        <dt>Starting Monthly Fee</dt>
                        <dd>£249 (+VAT)</dd>
                    </dl>
                    <dl class="opt-person-25 hidden opt-filter">
                        <dt>Set-up Fee</dt>
                        <dd>£249 (+VAT)</dd>
                        <dt>Starting Monthly Fee</dt>
                        <dd>£149 (+VAT)</dd>
                    </dl>
                </div>

                <div class="filter-actions">
                    <div class="opt-online-25 opt-action">
                        <p><strong>Get Funded for</strong> $25,000</p>
                        <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=2222&quantity=1&buy_now=1">Buy Now</a>
                    </div>
                    <div class="opt-online-10 hidden opt-action">
                        <p><strong>Get Funded for</strong> $10,000</p>
                        <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=838&quantity=1&buy_now=1">Buy Now</a>
                    </div>
                    <div class="opt-online-50 hidden opt-action">
                        <p><strong>Get Funded for</strong> $50,000</p>
                        <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=2219&quantity=1&buy_now=1">Buy Now</a>
                    </div>
                    <div class="opt-person-25 hidden opt-action">
                        <p><strong>Get Funded for</strong> $25,000</p>
                        <a class="button button-modal" data-jtp="inperson"   href="#">Apply Now</a>
                    </div>
                </div>

            </div>
            <div class="matrix-wrap">       
            <div class="table--data">
                <span class="m-control"><i class="fas fa-chevron-right"></i></span>
                <table class="opt-online-10 hidden">
                    <tr>
                        <th></th>
                        <th>Step 1</th>
                        <th>Step 2</th>
                        <th>Step 3</th>
                        <th>Step 4</th>
                        <th>Step 5</th>
                        <th>Step 6</th>
                        <th>Step 7</th>
                    </tr>
                    <tr>
                        <th scope="row">Balance</th>
                        <td>$10,000</td>
                        <td>$25,000</td>
                        <td>$35,000</td>
                        <td>$50,000</td>
                        <td>$60,000</td>
                        <td>$75,000</td>
                        <td>$100,000</td>
                    </tr>
                    <tr>
                        <th scope="row">Profit Share</th>
                        <td>50:50</td>
                        <td>50:50</td>
                        <td>50:50</td>
                        <td>50:50</td>
                        <td>70:30</td>
                        <td>70:30</td>
                        <td>70:30</td>
                    </tr>
                    <tr>
                        <th scope="row">Profit Target</th>
                        <td>4% ($400)</td>
                        <td>4% ($1,000)</td>
                        <td>4% ($1,400)</td>
                        <td>4% ($2,000)</td>
                        <td>4% ($2,400)</td>
                        <td>4% ($3,000)</td>
                        <td>4% ($4,000)</td>
                    </tr>
                    <tr>
                        <th scope="row">Buying Power</th>
                        <td>$30,000 </td>
                        <td>$75,000</td>
                        <td>$105,000</td>
                        <td>$150,000</td>
                        <td>$180,000</td>
                        <td>$225,000</td>
                        <td>$300,000</td>
                    </tr>
                    <tr>
                        <th scope="row">Next Step Target</th>
                        <td>3&nbsp;Payouts</td>
                        <td>1&nbsp;Payout</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                    </tr>
                    <tr>
                        <th scope="row">Account Fee</th>
                        <td>£99 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£199 only applicable if target not met</td>
                        <td>£249 only applicable if target not met</td>
                    </tr>
                </table>


                <table class="opt-online-25">
                    <tr>
                        <th></th>
                        <th>Step 1</th>
                        <th>Step 2</th>
                        <th>Step 3</th>
                        <th>Step 4</th>
                        <th>Step 5</th>
                        <th>Step 6</th>
                    </tr>
                    <tr>
                        <th scope="row">Balance</th>     
                        <td>$25,000</td>
                        <td>$35,000</td>
                        <td>$50,000</td>
                        <td>$60,000</td>
                        <td>$75,000</td>
                        <td>$100,000</td>
                    </tr>
                    <tr>
                        <th scope="row">Profit Share</th>       
                        <td>50:50</td>
                        <td>50:50</td>
                        <td>50:50</td>
                        <td>70:30</td>
                        <td>70:30</td>
                        <td>70:30</td>
                    </tr>
                    <tr>
                        <th scope="row">Profit Target</th>        
                        <td>4% ($1,000)</td>
                        <td>4% ($1,400)</td>
                        <td>4% ($2,000)</td>
                        <td>4% ($2,400)</td>
                        <td>4% ($3,000)</td>
                        <td>4% ($4,000)</td>
                    </tr>
                    <tr>
                        <th scope="row">Buying Power</th> 
                        <td>$75,000</td>
                        <td>$105,000</td>
                        <td>$150,000</td>
                        <td>$180,000</td>
                        <td>$225,000</td>
                        <td>$300,000</td>
                    </tr>
                    <tr>
                        <th scope="row">Next Step Target</th>
                        <td>1&nbsp;Payout</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                    </tr>
                    <tr>
                        <th scope="row">Account Fee</th>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£199 only applicable if target not met</td>
                        <td>£249 only applicable if target not met</td>
                    </tr>
                </table>


                <table class="opt-online-50 hidden">
                    <tr>
                        <th></th>
                        <th>Step 1</th>
                        <th>Step 2</th>
                        <th>Step 3</th>
                        <th>Step 4</th>
                    </tr>
                    <tr>
                        <th scope="row">Balance</th>        
                        <td>$50,000</td>
                        <td>$60,000</td>
                        <td>$75,000</td>
                        <td>$100,000</td>
                    </tr>
                    <tr>
                        <th scope="row">Profit Share</th>       
                        <td>50:50</td>
                        <td>70:30</td>
                        <td>70:30</td>
                        <td>70:30</td>
                    </tr>
                    <tr>
                        <th scope="row">Profit Target</th>        

                        <td>4% ($2,000)</td>
                        <td>4% ($2,400)</td>
                        <td>4% ($3,000)</td>
                        <td>4% ($4,000)</td>
                    </tr>
                    <tr>
                        <th scope="row">Buying Power</th> 
                        <td>$150,000</td>
                        <td>$180,000</td>
                        <td>$225,000</td>
                        <td>$300,000</td>
                    </tr>
                    <tr>
                        <th scope="row">Next Step Target</th>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                    </tr>
                    <tr>
                        <th scope="row">Account Fee</th>
                        <td>£249 only applicable if target not met</td>
                        <td>£249 only applicable if target not met</td>
                        <td>£249 only applicable if target not met</td>
                        <td>£249 only applicable if target not met</td>
                    </tr>
                </table>


                <table class="opt-person-25 hidden">
                    <tr>
                        <th></th>
                        <th>Junior Trading Programme</th>
                        <th>Step 1</th>
                        <th>Step 2</th>
                        <th>Step 3</th>
                        <th>Step 4</th>
                        <th>Step 5</th>
                        <th>Step 6</th>
                    </tr>
                    <tr>
                        <th scope="row">Balance</th>
                        <td>$25,000 <span>(1 month demo period)</span></td>
                        <td>$25,000</td>
                        <td>$35,000</td>
                        <td>$50,000</td>
                        <td>$60,000</td>
                        <td>$75,000</td>
                        <td>$100,000</td>
                    </tr>
                    <tr>
                        <th scope="row">Profit Share</th>
                        <td>N/A</td>
                        <td>50:50</td>
                        <td>50:50</td>
                        <td>50:50</td>
                        <td>70:30</td>
                        <td>70:30</td>
                        <td>70:30</td>
                    </tr>
                    <tr>
                        <th scope="row">Profit Target</th>
                        <td>N/A</td>
                        <td>4% ($1,000)</td>
                        <td>4% ($1,400)</td>
                        <td>4% ($2,000)</td>
                        <td>4% ($2,400)</td>
                        <td>4% ($3,000)</td>
                        <td>4% ($4,000)</td>
                    </tr>
                    <tr>
                        <th scope="row">Buying Power</th>
                        <td>$75,000</td>
                        <td>$75,000</td>
                        <td>$105,000</td>
                        <td>$150,000</td>
                        <td>$180,000</td>
                        <td>$225,000</td>
                        <td>$300,000</td>
                    </tr>
                    <tr>
                        <th scope="row">Next Step Target</th>
                        <td>N/A</td>
                        <td>1&nbsp;Payout</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                        <td>2&nbsp;Payouts</td>
                    </tr>
                    <tr>
                        <th scope="row">Account Fee</th>
                        <td>N/A</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£149 only applicable if target not met</td>
                        <td>£199 only applicable if target not met</td>
                        <td>£249 only applicable if target not met</td>
                    </tr>
                </table>

            </div>
            </div>
        </div>
       

        <div class="buttons buttons--matrix">
            <a class="button button-hover--alt" href="#jtp">Find out More About our In-Person Programme</a>
            <a class="button button--tertiary button-hover--alt2" href="#jtp-online">Find out More About our Online Programme</a>
        </div>

    </div>

    <div class="gfx gfx--grid gfx--grid-d" style="position:absolute;top: 80px; right: -20px;"></div>
</section>
*/ ?>

<?php $_content = get_field('content_strip');  ?>
<section class="section section--gfx section--strip section--strip-reverse" id="jtp">
    <div class="container  container--s">
        <figure>
            <img src="<?php echo $_content['section_image']; ?>" loading="lazy" alt="<?php echo $_content['main_title']; ?>" />
        </figure>
        <article>
            <header class="strip__header">
                <h2><?php echo $_content['sub_title']; ?> <span><?php echo $_content['main_title']; ?></h2>
            </header>
            <div class="strip__content">
                 <?php echo wpautop( $_content['content'] ); ?>
            </div>
            
             <?php if( $_content['information_pdf'] ) : ?>
                <a style="margin-bottom:2em" href="<?php echo $_content['information_pdf']; ?>" target="_blank" class="button button--alt"><i class="fas fa-file-pdf"></i> Download PDF</a>
             <?php endif; ?>

            <h3 class="light">Select Your  Account Balance</h3>
            <div class="button-group">
                <?php while( have_rows('get_funded_services') ) : the_row(); ?>
                <a class="button button--small" href="?add-to-cart=<?php echo get_sub_field('woocommerce_product'); ?>&quantity=1&buy_now=1"><?php echo '$' . number_format( get_sub_field('starting_balance') ); ?></a>
                <?php endwhile; ?>
            </div>

        </article>
    </div>

    <div class="gfx gfx--grid gfx--grid-d" style="position:absolute;top: -25px; left: 35px;"></div>
</section>


<?php $grid = get_field('content_grid'); ?>
<section class="section section--gfx">
        <div class="container  container--s">

            <header class="section__title">
                <h2>Come and Join Us in Our Inspiring Office Space </h2>
            </header>

            <div class="block-grid block--grid-alt">
                
                <div class="block-grid__item">
                    <figure>
                        <img src="<?php echo $grid['column_1']['column_image']; ?>" loading="lazy" alt="" />
                    </figure>
                    <div class="block-grid__column">
                        <div class="block-grid__card mini-card">
                            <header>
                                <h3><?php echo $grid['column_1']['box_1_title']; ?></h3>
                                <img src="<?php echo $grid['column_1']['box_1_icon']; ?>" loading="lazy" alt="" />
                            </header>
                            <?php echo wpautop( $grid['column_1']['box_1_content'] ); ?>
                        </div>
                        <div class="block-grid__card mini-card">
                            <header>
                                <h3><?php echo $grid['column_1']['box_2_title']; ?></h3>
                                <img src="<?php echo $grid['column_1']['box_2_icon']; ?>" loading="lazy" alt="" />
                            </header>
                            <?php echo wpautop( $grid['column_1']['box_2_content'] ); ?>
                        </div>
                    </div>
                </div>

                <div class="block-grid__item">
                    <figure>
                        <img src="<?php echo $grid['column_2']['column_image']; ?>" loading="lazy" alt="" />
                    </figure>
                    <div class="block-grid__column">
                        <div class="block-grid__card mini-card">
                            <header>
                                <h3><?php echo $grid['column_2']['box_1_title']; ?></h3>
                                <img src="<?php echo $grid['column_2']['box_1_icon']; ?>" loading="lazy" alt="" />
                            </header>
                            <?php echo wpautop( $grid['column_2']['box_1_content'] ); ?>
                        </div>
                        <div class="block-grid__card mini-card">
                            <header>
                                <h3><?php echo $grid['column_2']['box_2_title']; ?></h3>
                                <img src="<?php echo $grid['column_2']['box_2_icon']; ?>" loading="lazy" alt="" />
                            </header>
                            <?php echo wpautop( $grid['column_2']['box_2_content'] ); ?>
                        </div>
                    </div>
                </div>

                <!-- 
                <div class="block-grid__item">
                    <figure>
                        <img src="<?php echo $grid['column_3']['column_image']; ?>" loading="lazy" alt="" />
                    </figure>
                    <div class="block-grid__card mini-card">
                        <header>
                            <h3><?php echo $grid['column_3']['box_1_title']; ?></h3>
                            <img src="<?php echo $grid['column_3']['box_1_icon']; ?>" loading="lazy" alt="" />
                        </header>
                        <?php echo wpautop( $grid['column_3']['box_1_content'] ); ?>
                    </div>
                    <div class="block-grid__card mini-card">
                        <header>
                            <h3><?php echo $grid['column_3']['box_2_title']; ?></h3>
                            <img src="<?php echo $grid['column_3']['box_2_icon']; ?>" loading="lazy" alt="" />
                        </header>
                        <?php echo wpautop( $grid['column_3']['box_2_content'] ); ?>
                    </div>
                </div>
                -->


            </div>

        </div>

        <div class="gfx gfx--grid gfx--grid-d" style="position:absolute;top: 80px; right: -20px;"></div>
        <div class="gfx gfx--grid gfx--grid-d" style="position:absolute;bottom: 40px; left: -20px;"></div>
    </section>


<?php $_content = get_field('content_strip_2'); ?>
<section class="section section--strip" id="jtp-online">
    <div class="container  container--s">
        <figure>
            <img src="<?php echo $_content['section_image']; ?>" loading="lazy" alt="<?php echo $_content['main_title']; ?>" />
        </figure>
        <article>
            <header class="strip__header">
                <h2><?php echo $_content['sub_title']; ?> <span><?php echo $_content['main_title']; ?></h2>
            </header>
            <div class="strip__content">
                 <?php echo wpautop( $_content['content'] ); ?>
            </div>
            <?php /* if( $_content['button_url'] ) : ?>
                    <a href="<?php echo $_content['button_url']; ?>"  class="button"><?php echo $_content['button_label']; ?></a>
                <?php endif; */ ?>

                <?php if( $_content['information_pdf'] ) : ?>
                        <a style="margin-bottom:2em" href="<?php echo $_content['information_pdf']; ?>" target="_blank" class="button button--alt"><i class="fas fa-file-pdf"></i> Download PDF</a>
                    <?php endif; ?>

                <h3 class="light">Select Your  Account Balance</h3>
                <div class="button-group">
                     <?php while( have_rows('get_qualified_services') ) : the_row(); ?>
                        <a class="button button--small" href="?add-to-cart=<?php echo get_sub_field('woocommerce_product'); ?>&quantity=1&buy_now=1"><?php echo '$' . number_format( get_sub_field('starting_balance') ); ?></a>
                     <?php endwhile; ?>
                    
                </div>
        </article>
    </div>
</section>

<?php $_content = get_field('content_strip_3'); ?>
<section class="section section--gfx">
        <div class="container  container--s container--layout ">
        <figure>
            <img src="<?php echo $_content['section_image']; ?>" alt="<?php echo $_content['main_title']; ?>" />
        </figure>
            <article class="content content--branded">
                <h2><?php echo $_content['main_title']; ?></h2>
                <?php echo wpautop( $_content['content'] ); ?>
                <?php if( $_content['button_url'] ) : ?>
                    <a href="<?php echo $_content['button_url']; ?>" class="button button-hover--alt"><?php echo $_content['button_label']; ?></a>
                <?php endif; ?>
            </article>
        </div>

        <div class="gfx gfx--grid gfx--grid-d" style="position:absolute;right: 20px; bottom: -30px;"></div>
    </section>

    <?php $stories = $args = array(
        'post_type' => 'success_story',
        'posts_per_page' => -1,

        ); 
    $stories = new WP_Query($args);
    


    ?>


    <section class="section section--dark section--stories-alt">
        <div class="container container--xs">

            <header>
                <h2 class="stories--header">Success Stories</h2>
            </header>

           
            <div class="stories-slider stories-slider--alt">
            <?php foreach( $stories->posts as $story ) :
                 if( get_post_meta($story->ID,'payout_earnt', true) ) : ?>

            <div class="stories__slide stories__slide--count">
                <div>
                    <header>
                        <h3><?php echo $story->post_title; ?></h3>
                    </header>
                    <p><?php echo get_field('course_taken', $story->ID); ?>
                    <?php if( get_field('countryorigin_of_user', $story->ID)) : ?>
                       | <strong><?php echo get_field('countryorigin_of_user', $story->ID); ?></strong>
                    <?php endif; ?>
                    </p>
                <?php echo wpautop( $story->post_content ); ?>
                </div>
                <aside>
                        <div class="story__value">
                            <strong><span>Payout</span>
                            $<?php echo number_format(get_field('payout_earnt', $story->ID)); ?></strong>
                            <?php if( get_field('percentage', $story->ID) ) : ?>
                            <span class="story__percentage"><?php echo get_field('percentage', $story->ID) ; ?>%</span>
                            <?php endif; ?>
                        </div>
                        
                    </aside>
            </div>

            <?php endif; endforeach; ?>

               
            </div>

        </div>
    </section>

    <?php $faqs = get_field('faqs'); ?>
    <section class="section">
        <div class="container  container--s ">
            <header class="section__title">
                <h2><?php echo $faqs['section_title']; ?></h2>
                <?php echo wpautop( $faqs['section_sub_title'] ); ?>
            </header>

            <div class="faqs">

                <?php if( $faqs['faqs'] ) : ?>
                    <?php foreach( $faqs['faqs'] as $faq ) : ?>
                        <details class="faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <summary class="faq__question" >
                                <strong  itemprop="name"><?php echo $faq['question']; ?></strong>
                                <i class="fas fa-chevron-down"></i>
                            </summary>
                            <div class="fa__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                <div itemprop="text"><?php echo wpautop($faq['answer']); ?></div>
                            </div>
                        </details>
                    <?php endforeach; ?>
                <?php endif; ?>               
                
            </div>
        </div>
    </section>


    <div class="modal">
        <div class="modal__box">
            <div class="modal__content">
                <header>
                    <h2>Apply Now <span id="apply_title"></span></h2><i class="fas fa-times"></i>
                </header>
                <?php echo do_shortcode( '[contact-form-7 id="491" title="JTP Form"]' ); ?>
            </div>
        </div>
    </div>


    <div class="disclaimer-modal" id="disclaimer-modal" style="display:none;">
        <div class="disclaimer-modal__content">
            <span class="disclaimer-modal__close" id="disclaimer-modal-close">&times;</span>
            <h2><?php echo get_field('pop_up_title'); ?></h2>
            <div class="disclaimer-modal__body">
                <?php echo wpautop( get_field('pop_up_content') ); ?>
            </div>
            <a class="button" href="" id="disclaimer-accept">I Accept</a>
            <a class="button button--alt" id="disclaimer-decline" href="#">Cancel</a>
        </div>
    </div>

    <script>
         const BREAKPOINT = 820;
        (() => {
   

            const modeButtons = document.querySelectorAll('[data-target]');
            const balanceButtons = document.querySelectorAll('[data-amount]');

            const qualifiedMatrix = document.getElementById('getQualfied-data');
            const fundedMatrix = document.getElementById('getFunded-data');

            const qualifiedOptions = document.querySelector('.getQualified-options');
            const fundedOptions = document.querySelector('.getFunded-options');

           let activeMatrix = !qualifiedMatrix.hidden ? qualifiedMatrix : fundedMatrix;

            // --- Mode toggle (Get Qualified / Get Funded)
            modeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    modeButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    console.log(btn.classList);

                    const isQualified = btn.dataset.target === 'getQualfied';

                    qualifiedMatrix.hidden = !isQualified;
                    fundedMatrix.hidden = isQualified;

                    qualifiedOptions.hidden = !isQualified;
                    fundedOptions.hidden = isQualified;

                    activeMatrix = isQualified ? qualifiedMatrix : fundedMatrix;

                    // Reset balance buttons when mode changes
                    document.querySelectorAll('[data-amount]').forEach(b => b.classList.remove('active'));

                    // activate first balance button in the active options
                    const firstBalanceBtn = (isQualified ? qualifiedOptions : fundedOptions).querySelector('[data-amount]');
                    firstBalanceBtn.classList.add('active');
                });
            });

            // --- Balance selection
            balanceButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const amount = btn.dataset.amount;

                    // button active state
                    btn.closest('ul')
                        .querySelectorAll('[data-amount]')
                        .forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    applyBalanceSelection(amount);
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                // Ensure activeMatrix is correct (Get Funded is visible)
                activeMatrix = !qualifiedMatrix.hidden ? qualifiedMatrix : fundedMatrix;

                // Select the 3rd balance button in the visible options (0-based index)
                const fundedButtons = fundedOptions.querySelectorAll('[data-amount]');
                const thirdButton = fundedButtons[4]; // $100,000

                if (thirdButton) {
                    thirdButton.click();
                }
            });

            // -- Arrow scroll control
            const arrow = document.querySelector('.matrix-v2__arrow');

            if (arrow) {
                console.log( 'Arrow found' );
                arrow.addEventListener('click', () => {

                    console.log( 'Arrow clicked' );

                    if (!activeMatrix) return;

                    const maxScrollLeft =
                        activeMatrix.scrollWidth - activeMatrix.clientWidth;

                    const atEnd =
                        Math.ceil(activeMatrix.scrollLeft) >= maxScrollLeft - 2;

                    if (atEnd) {
                        // Scroll back to start
                        activeMatrix.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        // Scroll right
                        activeMatrix.scrollBy({
                            left: activeMatrix.clientWidth * 0.8,
                            behavior: 'smooth'
                        });
                    }
                });
            }

           function updateArrowVisibility() {
                if (!arrow || !activeMatrix) return;

                const hasOverflow =
                    activeMatrix.scrollWidth > activeMatrix.clientWidth + 5;

                arrow.parentElement.style.display = hasOverflow ? 'block' : 'none';
            }

            // Initial check
            updateArrowVisibility();

            // Re-check on resize
            window.addEventListener('resize', updateArrowVisibility);

            // Re-check when mode changes
            modeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    setTimeout(updateArrowVisibility, 50);
                });
            });



        function applyBalanceSelection(amount, animate = true) {
            const cards = activeMatrix.querySelectorAll('.matrix-service');

            cards.forEach(card => {
                const match = card.dataset.amount === amount;

                if (window.innerWidth < BREAKPOINT) {
                    card.hidden = !match;
                } else {
                    card.hidden = false;
                    card.classList.toggle('active', match);

                    if (match && animate) {
                        const container = activeMatrix;
                        const scrollPosition =
                            card.offsetLeft -
                            (container.offsetWidth / 2) +
                            (card.offsetWidth / 2);

                        container.scrollTo({
                            left: scrollPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        }

        

        // --- Reset on resize
        window.addEventListener('resize', () => {
                if (window.innerWidth >= BREAKPOINT) {
                    document
                        .querySelectorAll('.matrix-service')
                        .forEach(card => (card.hidden = false));
                }
            });
        })();


        document.addEventListener('DOMContentLoaded', () => {
            if (window.innerWidth >= BREAKPOINT) return;

            const visibleMatrix = document.querySelector(
                '#getQualfied-data:not([hidden]), #getFunded-data:not([hidden])'
            );

            if (!visibleMatrix) return;

            const cards = visibleMatrix.querySelectorAll('.matrix-service');

            cards.forEach((card, index) => {
                card.hidden = index !== 0;
            });
        });

        // -- ToolTip 
        /*
        <span class="tooltip" data-tooltip="<?php echo esc_attr( $index['description'] ); ?>">
                                                        <i class="fas fa-info-circle"></i>
                                                    </span>
        */

        document.querySelectorAll('.tooltip').forEach(tooltip => {
            const text = tooltip.dataset.tooltip;
            if (!text) return;

            const tooltipBox = document.createElement('div');
            tooltipBox.className = 'tooltip-box';
            tooltipBox.textContent = text;
            tooltip.appendChild(tooltipBox);

            tooltip.addEventListener('mouseenter', () => {
                tooltipBox.style.opacity = '1';
                tooltipBox.style.visibility = 'visible';
            });

            tooltip.addEventListener('mouseleave', () => {
                tooltipBox.style.opacity = '0';
                tooltipBox.style.visibility = 'hidden';
            });
        }); 


        // -- Modal Form [ data-requiresPopUp ] will trigger the modal #disclaimer-modal 
        document.querySelectorAll('[data-requiresPopUp]').forEach(button => {
            button.addEventListener('click', event => {
                event.preventDefault();
                const targetUrl = button.getAttribute('href');
                document.getElementById('disclaimer-accept').setAttribute('href', targetUrl);
                document.getElementById('disclaimer-modal').style.display = 'flex';
            });
        });

        // -- Handle Declinebutton and Modal Close
        document.getElementById('disclaimer-decline').addEventListener('click', event => {
            event.preventDefault();
            document.getElementById('disclaimer-modal').style.display = 'none';
        });

        document.getElementById('disclaimer-modal-close').addEventListener('click', () => {
            document.getElementById('disclaimer-modal').style.display = 'none';
        });

      
    

    



    </script>

    <style>
        @media screen and (min-width: 768px) {
            .section--strip article {
                max-width: 635px;
            }
        }

        .button-group {
            flex-wrap: wrap;
        }

        .button-group .button {
            font-size: 85% !important;
        }

        .section__title p {
            max-width: 1020px;
        }

    </style>


<?php get_footer(); ?>
