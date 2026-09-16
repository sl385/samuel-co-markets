<?php
/**
 * Article sidebar (reference/single_article.png). @dynamic
 * Search, related articles (compact list), mailing-list CTA (reuses the same
 * CF7 493 form as the pre-footer band), categories with real post counts.
 * args: post_id
 */
$a = wp_parse_args($args ?? [], ['post_id' => get_the_ID()]);
$cats = wp_get_post_categories($a['post_id']);
$q = new WP_Query([
  'post_type' => 'post', 'posts_per_page' => 4,
  'post__not_in' => [$a['post_id']], 'category__in' => $cats ?: null, 'ignore_sticky_posts' => true,
]);
if (!$q->have_posts()) { wp_reset_postdata(); $q = new WP_Query(['post_type' => 'post', 'posts_per_page' => 4, 'post__not_in' => [$a['post_id']]]); }

$all_cats = get_categories(['hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC']);
?>
<aside class="scm-article-sidebar">
  <form class="scm-article-sidebar__search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <input class="scm-field" type="search" name="s" placeholder="Search articles…" value="<?php echo esc_attr(get_search_query()); ?>">
    <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
  </form>

  <?php if ($q->have_posts()): ?>
  <div class="scm-article-sidebar__block">
    <h3>Related articles</h3>
    <ul class="scm-related-list">
      <?php while ($q->have_posts()): $q->the_post(); $c = get_the_category(); ?>
        <li>
          <a class="scm-related-list__image" href="<?php the_permalink(); ?>"><?php scm_the_thumbnail('thumbnail'); ?></a>
          <div>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <?php if ($c): ?><span class="scm-meta"><?php echo esc_html($c[0]->name); ?></span><?php endif; ?>
          </div>
        </li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>
  </div>
  <?php endif; ?>

  <div class="scm-article-sidebar__block scm-article-sidebar__cta scm-on-dark">
    <span class="scm-eyebrow">Discipline creates opportunity</span>
    <p>Get practical insights, education updates and exclusive content.</p>
    <div class="scm-newsletter">
      <?php echo do_shortcode('[contact-form-7 id="493" title="Subscription Sign Up Form"]'); ?>
    </div>
  </div>

  <?php if ($all_cats): ?>
  <div class="scm-article-sidebar__block">
    <h3>Categories</h3>
    <ul class="scm-category-list">
      <?php foreach ($all_cats as $cat): ?>
        <li><a href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a><span><?php echo (int) $cat->count; ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>
</aside>
