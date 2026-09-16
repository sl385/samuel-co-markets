<?php
/**
 * Single-product "extras" — everything below the buy box (spec D3 cont.).
 * @dynamic — every section here is real per-product ACF data
 * (what_do_you_get_content, benefits_content, faqs, linked course modules),
 * just never had a real component; reskinned in place, same real fields,
 * same real hooks (woocommerce_template_single_add_to_cart untouched).
 * A section simply doesn't render when its field is empty — no placeholders.
 */
global $product;
$product_id = $product->get_id();
?>

<?php if ($content = get_field('what_do_you_get_content', $product_id)): ?>
<section class="scm-section scm-product-extra">
  <div class="scm-shell scm-product-extra__prose">
    <span class="scm-eyebrow">What's included</span>
    <div class="scm-prose"><?php echo wp_kses_post($content); ?></div>
  </div>
</section>
<?php endif; ?>

<?php if ($content = get_field('benefits_content', $product_id)):
  // Real content is a WYSIWYG <ul> of "Title - description" lines (Slate
  // editor markup) — parsed into cards rather than left as a dense legacy
  // list. Any line without a " - " just renders with no separate title.
  $items = [];
  if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $content, $m)) {
    foreach ($m[1] as $raw) {
      // The editor saves a plain " - " as a smart-punctuation en dash
      // entity (&#8211;), so wp_strip_all_tags() alone leaves a literal
      // "&#8211;" in the text — decode entities before splitting on it.
      $text = trim(html_entity_decode(wp_strip_all_tags($raw), ENT_QUOTES, 'UTF-8'));
      if (!$text) continue;
      if (preg_match('/\s[\x{2013}\x{2014}-]\s/u', $text)) {
        [$title, $desc] = preg_split('/\s[\x{2013}\x{2014}-]\s/u', $text, 2);
      } else {
        $title = ''; $desc = $text;
      }
      $items[] = [$title, $desc];
    }
  }
?>
<section class="scm-section scm-section--soft scm-product-extra">
  <div class="scm-shell">
    <div class="scm-section-head"><div><span class="scm-eyebrow">Why it works</span><h2>Benefits</h2></div></div>
    <?php if ($items): ?>
      <div class="scm-benefit-grid">
        <?php foreach ($items as $i => [$title, $desc]): ?>
          <article class="scm-benefit">
            <span class="scm-benefit__mark"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
            <?php if ($title): ?><h3><?php echo esc_html($title); ?></h3><?php endif; ?>
            <p><?php echo esc_html($desc); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="scm-prose"><?php echo wp_kses_post($content); ?></div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php $courses = get_post_meta($product_id, '_sc_courses', true); ?>
<?php if ($courses): ?>
<section class="scm-section scm-product-extra">
  <div class="scm-shell">
    <div class="scm-section-head"><div><span class="scm-eyebrow">Included course</span><h2>Here's what you'll learn</h2></div></div>

    <?php if ($summary = get_field('here_is_what_you_will_learn_summary', $product_id)):
      $videos = [];
      foreach ($courses as $_course) {
        foreach ((array) get_field('modules', $_course) as $module) {
          foreach ((array) ($module['module_lessons'] ?? []) as $lesson) $videos[] = $lesson;
        }
      }
    ?>
      <div class="scm-prose"><?php echo wp_kses_post($summary); ?></div>
      <?php if ($videos): ?>
        <h3 class="scm-product-extra__subhead">Example lessons</h3>
        <ul class="scm-lesson-list">
          <?php foreach (array_slice(array_reverse($videos), 0, 6) as $lesson): ?>
            <li><?php echo esc_html($lesson['lesson_name']); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    <?php else: ?>
      <?php foreach ($courses as $_course): $step = 1; ?>
        <?php foreach ((array) get_field('modules', $_course) as $m => $module): ?>
          <div class="scm-course-module">
            <h3>Section <?php echo esc_html($m + 1); ?>: <?php echo esc_html($module['module_name']); ?></h3>
            <ul class="scm-lesson-list">
              <?php foreach ((array) ($module['module_lessons'] ?? []) as $lesson): ?>
                <li><strong>Lesson <?php echo esc_html($step); ?>:</strong> <?php echo esc_html($lesson['lesson_name']); ?><?php $step++; ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if (have_rows('faqs', $product_id)): ?>
<section class="scm-section scm-section--soft scm-product-extra">
  <div class="scm-shell scm-product-extra__faq">
    <span class="scm-eyebrow">Before you buy</span>
    <h2>Frequently asked questions</h2>
    <div class="scm-faq">
      <?php while (have_rows('faqs', $product_id)): the_row(); ?>
        <details class="scm-faq__item">
          <summary class="scm-faq__question">
            <span><?php the_sub_field('question'); ?></span>
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
          </summary>
          <div class="scm-faq__answer"><?php the_sub_field('answer'); ?></div>
        </details>
      <?php endwhile; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="scm-section scm-section--dark scm-on-dark scm-product-cta">
  <div class="scm-shell scm-product-cta__grid">
    <div>
      <?php $cats = wp_strip_all_tags($product->get_categories()); ?>
      <span class="scm-eyebrow" style="color:var(--gold-soft)"><?php echo esc_html($cats ?: 'Ready to start?'); ?></span>
      <h2><?php the_title(); ?></h2>
    </div>
    <div class="scm-product-cta__buy">
      <span class="scm-product-cta__price"><?php echo wp_kses_post($product->get_price_html()); ?></span>
      <?php woocommerce_template_single_add_to_cart(); ?>
    </div>
  </div>
</section>

<div class="scm-mobile-buy" id="scm-mobile-buy" hidden>
  <div>
    <strong><?php the_title(); ?></strong>
    <span><?php echo wp_kses_post($product->get_price_html()); ?></span>
  </div>
  <a class="scm-button scm-button--gold" href="#product-<?php echo esc_attr($product_id); ?>">View options</a>
</div>
