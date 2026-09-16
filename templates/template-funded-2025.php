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

<?php
$_content = get_field('header');
scm_component('masthead', [
  'title' => $_content['header_title'] ?? '',
  'content' => wpautop($_content['header_content'] ?? ''),
  'primary' => !empty($_content['header_button_url']) ? [$_content['header_button_label'], $_content['header_button_url']] : null,
  'secondary' => !empty($_content['header_button_url_2']) ? [$_content['header_button_label_2'], $_content['header_button_url_2']] : null,
  'image' => $_content['header_image'] ?? '',
]);
?>

<?php
// Proof-stats row — every figure computed directly from the real programme
// data above rather than restated as prose, no new claims invented.
$funded_rows_hero = get_field('get_funded_services') ?: [];
$max_balance = $funded_rows_hero ? max(array_column($funded_rows_hero, 'starting_balance')) : 0;
$first_row = $funded_rows_hero[0] ?? [];
$programme_count = count($funded_rows_hero) + count(get_field('get_qualified_services') ?: []);
if ($max_balance):
?>
<section class="scm-section scm-proof-stats">
  <div class="scm-shell scm-proof-stats__grid">
    <div><strong>$<?php echo esc_html(number_format($max_balance)); ?></strong><span>Maximum simulated account</span></div>
    <?php if (!empty($first_row['profit_share'])): ?><div><strong><?php echo esc_html($first_row['profit_share']); ?></strong><span>Profit share in the trader's favour</span></div><?php endif; ?>
    <?php if (!empty($first_row['leverage_up_to'])): ?><div><strong><?php echo esc_html($first_row['leverage_up_to']); ?></strong><span>Leverage available</span></div><?php endif; ?>
    <div><strong><?php echo esc_html($programme_count); ?></strong><span>Funding &amp; qualification programmes</span></div>
  </div>
</section>
<?php endif; ?>


<?php
$matix = get_field('pricing_matrix_options');
/** Build the 8-row metrics array for one matrix row using the real key_labels
 *  order (index position is the real data contract here, ported as-is). A
 *  closure, not a named function — this file is a page template, and a
 *  top-level function declaration here would fatal ("cannot redeclare") if
 *  WP ever includes it twice in one request. */
$scm_funding_metrics = function ($indexes) {
  return [
    [$indexes[0]['label'] ?? '', $indexes[0]['description'] ?? '', get_sub_field('funding_type')],
    [$indexes[1]['label'] ?? '', $indexes[1]['description'] ?? '', '$' . number_format(get_sub_field('maximum_daily_loss'))],
    [$indexes[2]['label'] ?? '', $indexes[2]['description'] ?? '', '$' . number_format(get_sub_field('max_loss'))],
    [$indexes[3]['label'] ?? '', $indexes[3]['description'] ?? '', get_sub_field('profit_share')],
    [$indexes[4]['label'] ?? '', $indexes[4]['description'] ?? '', get_sub_field('profit_builder')],
    [$indexes[5]['label'] ?? '', $indexes[5]['description'] ?? '', get_sub_field('consistency_rule')],
    [$indexes[6]['label'] ?? '', $indexes[6]['description'] ?? '', get_sub_field('leverage_up_to')],
    [$indexes[7]['label'] ?? '', $indexes[7]['description'] ?? '', get_sub_field('payout_frequency')],
  ];
};
?>
<section class="scm-section scm-section--matrix" id="programmes">
  <div class="scm-shell">
    <header class="scm-section-head"><div><span class="scm-eyebrow">Choose your route</span><h2><?php echo esc_html($matix['title'] ?? ''); ?></h2></div></header>
    <?php echo wpautop( $matix['content'] ?? '' ); ?>

    <?php
    // Quick-reference summary above the interactive matrix — the real
    // $100,000 Get Funded row (same tier the matrix defaults to), read
    // directly rather than via have_rows()/get_sub_field() since we're
    // outside that loop here.
    $funded_rows = get_field('get_funded_services') ?: [];
    $featured_row = null;
    foreach ($funded_rows as $row) {
      if ((int) ($row['starting_balance'] ?? 0) === 100000) { $featured_row = $row; break; }
    }
    if ($featured_row):
      scm_component('rules-panel', [
        'title' => '$100,000 Get Funded — at a glance',
        'metrics' => [
          [$indexes[0]['label'] ?? '', $indexes[0]['description'] ?? '', $featured_row['funding_type'] ?? ''],
          [$indexes[1]['label'] ?? '', $indexes[1]['description'] ?? '', '$' . number_format($featured_row['maximum_daily_loss'] ?? 0)],
          [$indexes[2]['label'] ?? '', $indexes[2]['description'] ?? '', '$' . number_format($featured_row['max_loss'] ?? 0)],
          [$indexes[3]['label'] ?? '', $indexes[3]['description'] ?? '', $featured_row['profit_share'] ?? ''],
          [$indexes[4]['label'] ?? '', $indexes[4]['description'] ?? '', $featured_row['profit_builder'] ?? ''],
          [$indexes[5]['label'] ?? '', $indexes[5]['description'] ?? '', $featured_row['consistency_rule'] ?? ''],
          [$indexes[6]['label'] ?? '', $indexes[6]['description'] ?? '', $featured_row['leverage_up_to'] ?? ''],
          [$indexes[7]['label'] ?? '', $indexes[7]['description'] ?? '', $featured_row['payout_frequency'] ?? ''],
        ],
      ]);
    endif;
    ?>

    <div class="matrix-v2">
      <header class="matrix-v2__header">
        <div class="matrix-v2__headerItem">
          <h3>I would like to:</h3>
          <ul class="matrix-v2__pills">
            <li><button class="matrix-v2__pill" data-target="getQualfied">Get Qualified</button></li>
            <li><button class="matrix-v2__pill active" data-target="getFunded">Get Funded</button></li>
          </ul>
        </div>
        <div class="matrix-v2__headerItem">
          <h3>Account Balance:</h3>
          <ul class="matrix-v2__pills getQualified-options" hidden>
            <?php while( have_rows('get_qualified_services') ) : the_row();
              $service_amount = get_sub_field('starting_balance'); ?>
              <li><button class="matrix-v2__pill" data-amount="<?php echo esc_attr($service_amount); ?>">$<?php echo esc_html(number_format($service_amount)); ?></button></li>
            <?php endwhile; ?>
          </ul>
          <ul class="matrix-v2__pills getFunded-options">
            <?php while( have_rows('get_funded_services') ) : the_row();
              $service_amount = get_sub_field('starting_balance'); ?>
              <li><button class="matrix-v2__pill" data-amount="<?php echo esc_attr($service_amount); ?>">$<?php echo esc_html(number_format($service_amount)); ?></button></li>
            <?php endwhile; ?>
          </ul>
        </div>
      </header>

      <div class="matrix-v2__frame">
        <div class="matrix-v2__nav matrix-v2__nav--right" aria-hidden="true">
          <button type="button" class="matrix-v2__arrow" data-direction="right"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <div class="matrix-v2__grid" id="getQualfied-matrix">
          <div class="matrix-v2__column" id="getQualfied-data" hidden>
            <?php while( have_rows('get_qualified_services') ) : the_row();
              scm_component('funding-tier-card', [
                'tag' => 'Get Qualified',
                'balance' => get_sub_field('starting_balance'),
                'cost_label' => 'Setup Cost',
                'setup_fee' => get_sub_field('setup_fee'),
                'note' => 'Plus 11 monthly payments of £' . number_format(get_sub_field('cost'), 2),
                'metrics' => $scm_funding_metrics($indexes),
                'product_id' => get_sub_field('woocommerce_product'),
                'requires_popup' => get_sub_field('requires_pop_up_notcie'),
              ]);
            endwhile; ?>
          </div>

          <div class="matrix-v2__column" id="getFunded-data">
            <?php while( have_rows('get_funded_services') ) : the_row();
              scm_component('funding-tier-card', [
                'tag' => 'Get Funded',
                'balance' => get_sub_field('starting_balance'),
                'cost_label' => 'Total Setup Cost',
                'setup_fee' => get_sub_field('setup_fee'),
                'note' => 'Setup fee of £' . number_format(get_sub_field('setup_fee')) . ' plus monthly cost of £' . number_format(get_sub_field('cost'), 2),
                'metrics' => $scm_funding_metrics($indexes),
                'product_id' => get_sub_field('woocommerce_product'),
                'requires_popup' => get_sub_field('requires_pop_up_notcie'),
              ]);
            endwhile; ?>
          </div>
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

<?php $_content = get_field('content_strip'); ?>
<section class="scm-section" id="jtp">
  <?php scm_component('content-strip', [
    'eyebrow' => $_content['sub_title'] ?? '',
    'title' => $_content['main_title'] ?? '',
    'content' => wpautop($_content['content'] ?? ''),
    'image' => $_content['section_image'] ?? 0,
    'seed' => 1,
    'button' => ['View Get Funded programmes', '#programmes'],
    'pdf_url' => $_content['information_pdf'] ?? '',
  ]); ?>
</section>


<?php
$grid = get_field('content_grid');
// Only columns 1 and 2 are ever populated on the real page (column_3 has
// been commented out in this template since before this reskin — left that
// way rather than second-guessing it).
?>
<section class="scm-section scm-section--soft">
  <div class="scm-shell">
    <div class="scm-section-head"><div><span class="scm-eyebrow">London trading environment</span><h2>Come and join us in our inspiring office space</h2></div></div>
    <div class="scm-facility-grid">
      <?php foreach (['column_1', 'column_2'] as $i => $col): $c = $grid[$col] ?? []; ?>
        <article class="scm-facility">
          <img class="scm-facility__image" src="<?php echo esc_url(scm_acf_image_url($c['column_image'] ?? 0, 'large', $i)); ?>" alt="">
          <?php foreach (['box_1', 'box_2'] as $box): if (empty($c[$box . '_title'])) continue; $icon = scm_acf_icon_url($c[$box . '_icon'] ?? 0); ?>
            <div class="scm-facility__card">
              <header>
                <?php if ($icon): ?><img class="scm-facility__icon" src="<?php echo esc_url($icon); ?>" alt="" loading="lazy"><?php endif; ?>
                <h3><?php echo esc_html($c[$box . '_title']); ?></h3>
              </header>
              <?php echo wp_kses_post(wpautop($c[$box . '_content'] ?? '')); ?>
            </div>
          <?php endforeach; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<?php $_content = get_field('content_strip_2'); ?>
<section class="scm-section" id="jtp-online">
  <?php scm_component('content-strip', [
    'eyebrow' => $_content['sub_title'] ?? '',
    'title' => $_content['main_title'] ?? '',
    'content' => wpautop($_content['content'] ?? ''),
    'image' => $_content['section_image'] ?? 0,
    'seed' => 2,
    'reverse' => true,
    'button' => ['View Get Qualified programmes', '#programmes'],
    'pdf_url' => $_content['information_pdf'] ?? '',
  ]); ?>
</section>

<?php
// Real team members whose real roles are coaching-relevant to this
// programme — same team CPT + team-card.php as About, not new content.
$coach_ids = [233, 444, 442]; // Samuel Leach, Raj Singh, Adrian Leach
$coaches = get_posts(['post_type' => 'team', 'post__in' => $coach_ids, 'orderby' => 'post__in']);
if ($coaches):
?>
<section class="scm-section">
  <div class="scm-shell">
    <div class="scm-section-head"><div><span class="scm-eyebrow">Meet the team</span><h2>Coaches who trade and teach</h2></div></div>
    <div class="scm-about-team-grid scm-about-team-grid--coaches">
      <?php global $post; foreach ($coaches as $post): setup_postdata($post); scm_component('team-card'); endforeach; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php $_content = get_field('content_strip_3'); ?>
<section class="scm-section scm-section--soft">
  <?php scm_component('content-strip', [
    'title' => $_content['main_title'] ?? '',
    'content' => wpautop($_content['content'] ?? ''),
    'image' => $_content['section_image'] ?? 0,
    'seed' => 3,
    'button' => !empty($_content['button_url']) ? [$_content['button_label'], $_content['button_url']] : null,
  ]); ?>
    </section>

    <?php
    // Real success_story CPT — 80+ posts have real payout_earnt data. Capped
    // to a sensible carousel size rather than rendering every one.
    $stories = new WP_Query([
      'post_type' => 'success_story',
      'posts_per_page' => 30,
      'meta_key' => 'payout_earnt',
      'orderby' => 'rand',
    ]);
    $slides = array_filter($stories->posts, fn($s) => get_field('payout_earnt', $s->ID));
    $slides = array_slice($slides, 0, 8);
    ?>
    <?php if ($slides): ?>
    <section class="scm-section scm-section--dark scm-on-dark">
      <div class="scm-shell">
        <div class="scm-section-head"><div><span class="scm-eyebrow">Success stories</span><h2>Real traders. Real results.</h2></div></div>
        <div class="scm-story-carousel">
          <?php foreach ($slides as $i => $story):
            $initials = ''; foreach (explode(' ', $story->post_title) as $w) $initials .= mb_substr($w, 0, 1);
            $course = get_field('course_taken', $story->ID);
            $country = get_field('countryorigin_of_user', $story->ID);
            $payout = get_field('payout_earnt', $story->ID);
            $percentage = get_field('percentage', $story->ID);
          ?>
            <article class="scm-story-slide<?php echo $i === 0 ? ' is-active' : ''; ?>">
              <div class="scm-story-slide__portrait"><?php echo esc_html(mb_strtoupper($initials)); ?></div>
              <div class="scm-story-slide__copy">
                <span class="scm-eyebrow">Trader story</span>
                <h3><?php echo esc_html($story->post_title); ?></h3>
                <?php if ($course || $country): ?>
                  <span class="scm-meta"><?php echo esc_html(trim($course . ($country ? ' · ' . $country : ''))); ?></span>
                <?php endif; ?>
                <blockquote><?php echo esc_html(wp_trim_words($story->post_content, 32)); ?></blockquote>
                <div class="scm-story-slide__stats">
                  <div><span>Payout</span><strong>$<?php echo esc_html(number_format($payout)); ?></strong></div>
                  <?php if ($percentage): ?><div><span>Return</span><strong><?php echo esc_html($percentage); ?>%</strong></div><?php endif; ?>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
          <div class="scm-story-carousel__controls">
            <span class="scm-story-carousel__progress"><span id="scm-story-current">1</span> / <?php echo count($slides); ?></span>
            <div class="scm-story-carousel__arrows">
              <button type="button" class="scm-story-carousel__arrow" data-story-dir="-1" aria-label="Previous story">←</button>
              <button type="button" class="scm-story-carousel__arrow" data-story-dir="1" aria-label="Next story">→</button>
            </div>
          </div>
        </div>
        <p class="scm-story-disclaimer">Individual experiences vary. Testimonials and historic payouts are not a guarantee of future results or trading success.</p>
      </div>
    </section>
    <?php endif; wp_reset_postdata(); ?>

    <?php $faqs = get_field('faqs'); ?>
    <?php if (!empty($faqs['faqs'])): ?>
    <section class="scm-section scm-section--soft">
      <div class="scm-shell scm-product-extra__faq">
        <span class="scm-eyebrow">Before you begin</span>
        <h2><?php echo esc_html($faqs['section_title'] ?? ''); ?></h2>
        <?php echo wpautop($faqs['section_sub_title'] ?? ''); ?>
        <div class="scm-faq">
          <?php foreach ($faqs['faqs'] as $faq): ?>
            <details class="scm-faq__item">
              <summary class="scm-faq__question">
                <span><?php echo esc_html($faq['question']); ?></span>
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
              </summary>
              <div class="scm-faq__answer"><?php echo wp_kses_post(wpautop($faq['answer'])); ?></div>
            </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <section class="scm-section scm-section--dark scm-on-dark scm-product-cta">
      <div class="scm-shell scm-product-cta__grid">
        <div>
          <span class="scm-eyebrow" style="color:var(--gold-soft)">Choose your route</span>
          <h2>Build your next stage with structure.</h2>
        </div>
        <div class="scm-actions-row">
          <a class="scm-button scm-button--gold" href="#programmes">Explore funding</a>
          <a class="scm-button scm-button--on-dark" href="<?php echo esc_url(home_url('/contact-us/')); ?>">Get in touch</a>
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
