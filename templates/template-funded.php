<?php
/**
 * Template Name: Get Funded
 *
 * @package WordPress
 * @subpackage Sam & Co
 */

// redirect to https://www.samuelandcotrading.com/the-1-most-trusted-prop-firm-since-2012/
wp_redirect( 'https://www.samuelandcotrading.com/the-1-most-trusted-prop-firm-since-2012/', 301 );
exit;

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
                        <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=2222&quantity=1&redirect_to_cart=1">Buy Now</a>
                    </div>
                    <div class="opt-online-10 hidden opt-action">
                        <p><strong>Get Funded for</strong> $10,000</p>
                        <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=838&quantity=1&redirect_to_cart=1">Buy Now</a>
                    </div>
                    <div class="opt-online-50 hidden opt-action">
                        <p><strong>Get Funded for</strong> $50,000</p>
                        <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=2219&quantity=1&redirect_to_cart=1">Buy Now</a>
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
            <?php if( $_content['button_url'] ) : ?>
                    <a href="<?php echo $_content['button_url']; ?>"  data-jtp="inperson"  class="button button-modal"><?php echo $_content['button_label']; ?></a>
                <?php endif; ?>
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

                <div class="button-group">
                    <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=838&quantity=1&redirect_to_cart=1">$10,000</a>
                    <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=2222&quantity=1&redirect_to_cart=1">$25,000</a>
                    <a class="button" href="<?php echo wc_get_cart_url(); ?>?add-to-cart=2219&quantity=1&redirect_to_cart=1">$50,000</a>
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

<?php get_footer(); ?>
