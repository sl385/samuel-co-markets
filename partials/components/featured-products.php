<?php
/**
 * "Take your knowledge further" — four WooCommerce products. @dynamic
 * Uses products marked Featured (the star in Products → All Products); if
 * none are starred, the four newest published products. Headings from ACF
 * options (Homepage — newsroom sections).
 */
if (!function_exists('wc_get_products')) return;
$title = scm_opt('scm_knowledge_title', 'Take your knowledge further');
$text  = scm_opt('scm_knowledge_text', 'Gain the skills, qualifications and opportunities to become a more confident, informed trader and investor.');
$products = wc_get_products(['status' => 'publish', 'featured' => true, 'limit' => 4, 'orderby' => 'menu_order title', 'order' => 'ASC']);
$is_fallback = false;
if (!$products) { $products = wc_get_products(['status' => 'publish', 'limit' => 4, 'orderby' => 'date', 'order' => 'DESC']); $is_fallback = true; }
if (!$products) return;
$icons = ['fa-solid fa-book-open', 'fa-solid fa-graduation-cap', 'fa-solid fa-chart-simple', 'fa-solid fa-bullseye'];
?>
<section class="scm-section scm-knowledge">
  <div class="scm-shell">
    <div class="scm-knowledge__head">
      <h2><?php echo esc_html($title); ?></h2>
      <p><?php echo esc_html($text); ?></p>
    </div>
    <div class="scm-knowledge__grid">
      <?php foreach ($products as $i => $product): $pid = $product->get_id(); $short = $product->get_short_description() ?: $product->get_description(); ?>
        <article class="scm-knowledge__card">
          <a class="scm-knowledge__image" href="<?php echo esc_url($product->get_permalink()); ?>" style="background-image:url('<?php echo esc_url(scm_the_thumbnail_url('medium_large', $pid)); ?>')" aria-hidden="true" tabindex="-1"></a>
          <span class="scm-knowledge__icon"><i class="<?php echo esc_attr($icons[$i % 4]); ?>" aria-hidden="true"></i></span>
          <h3><a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
          <p><?php echo esc_html(wp_trim_words(wp_strip_all_tags($short), 16)); ?></p>
          <a class="scm-button scm-button--sm" href="<?php echo esc_url($product->get_permalink()); ?>">Find out more →</a>
        </article>
      <?php endforeach; ?>
    </div>
    <?php if ($is_fallback && current_user_can('edit_products')): ?><p class="scm-review-note scm-review-note--inline">Showing the newest products. Star products as <strong>Featured</strong> in Products → All Products to choose these four. (Only shown to editors.)</p><?php endif; ?>
  </div>
</section>
