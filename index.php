<?php
get_header();
$cat_id = (int) get_query_var('cat');
$title = is_category() ? single_cat_title('', false) : get_the_title(get_option('page_for_posts'));
if (!$title) $title = 'Latest';
?>
<section class="scm-page__header"><div class="scm-shell"><span class="scm-eyebrow">News &amp; research</span><h1><?php echo esc_html($title); ?></h1></div></section>
<section class="scm-section"><div class="scm-shell">
  <?php $cats = get_terms(['taxonomy' => 'category', 'hide_empty' => true]); if ($cats && !is_wp_error($cats)): ?>
  <ul class="scm-filter">
    <li><a class="<?php echo !$cat_id ? 'active' : ''; ?>" href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/')); ?>">All</a></li>
    <?php foreach ($cats as $cat): if ($cat->slug === 'uncategorised' || $cat->slug === 'uncategorized') continue; ?>
      <li><a class="<?php echo $cat_id === (int) $cat->term_id ? 'active' : ''; ?>" href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
  <?php if (have_posts()): ?>
  <div class="scm-card-grid scm-card-grid--3">
    <?php while (have_posts()): the_post(); ?>
      <article <?php post_class('scm-card'); ?>>
        <a class="scm-card__image" href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail()) the_post_thumbnail('medium_large'); ?></a>
        <div class="scm-card__body">
          <span class="scm-meta"><?php echo wp_kses_post(get_the_category_list(' · ')); ?> · <?php echo esc_html(scm_read_time()); ?> min read</span>
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 18)); ?></p>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
  <?php the_posts_pagination(); ?>
  <?php else: ?><p>No content found.</p><?php endif; ?>
</div></section>
<?php get_footer(); ?>
