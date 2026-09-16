<?php
/**
 * Template Name: Service
 *
 * @package WordPress
 * @subpackage Sam & Co
 */

$_content = get_field('header');
$woo_tax = get_field('woocoomerce_top_level_category');

$args = [
    'post_type' => 'product',
    'posts_per_page' => -1,
    //'orderby' => 'meta_value_num',
    //'meta_key' => '_price',
    'order' => 'desc'
];

$filter = $_GET['filter'] ?? '';

$args['tax_query'] = [
    [
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => $filter ?: $woo_tax->slug,
    ]
];

$products = new WP_Query( $args );


?>
<?php get_header(); ?>

<?php woocommerce_output_all_notices(); ?>

    <?php scm_component('masthead', [
      'eyebrow' => $_content['header_sub_title'] ?? '',
      'title' => $_content['header_title'] ?? '',
      'content' => wpautop($_content['header_content'] ?? ''),
      'primary' => !empty($_content['header_button_url']) ? [$_content['header_button_label'], $_content['header_button_url']] : null,
      'image' => $_content['header_image'] ?? '',
      'dark' => !empty($_content['dark_background']),
    ]); ?>

<section class="scm-section" id="products">
  <div class="scm-shell">
    <?php
    $cats = get_terms(['taxonomy' => 'product_cat', 'parent' => $woo_tax->term_id, 'orderby' => 'slug', 'hide_empty' => false]);
    if ($cats):
      $items = [['label' => 'All', 'url' => get_permalink($post->ID) . '#products', 'active' => !$filter]];
      foreach ($cats as $cat) {
        $items[] = ['label' => $cat->name, 'url' => add_query_arg('filter', $cat->slug, get_permalink($post->ID)) . '#products', 'active' => $filter === $cat->slug];
      }
      scm_component('pill-nav', ['items' => $items]);
    endif;
    ?>
    <?php if ($products->have_posts()): ?>
      <ul class="scm-card-grid scm-card-grid--3">
        <?php while ($products->have_posts()): $products->the_post(); wc_get_template_part('content', 'product'); endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No products found.</p>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  </div>
</section>

<?php get_footer(); ?>