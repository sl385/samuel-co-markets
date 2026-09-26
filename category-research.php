<?php
/**
 * Research category archive (/category/research/) — editorial layout in the
 * spirit of the homepage. Picked up automatically by WP's template hierarchy
 * (category-{slug}.php); every other category still uses index.php.
 *
 * Page 1: masthead → topic pills → featured story + two overlay cards →
 * one block per topic (child category, latest three) → founder band →
 * everything else as editorial cards. Page 2+: masthead → pills → cards.
 * Posts shown in an upper block are excluded from lower ones (scm_shown_ids).
 */
get_header();

$term  = get_queried_object();
$first = !is_paged();
$lead  = $term->description ?: 'Structured market research with a clear thesis, the evidence behind it and what would change our view.';
$posts_page = get_permalink(get_option('page_for_posts')) ?: home_url('/');

scm_component('masthead', ['eyebrow' => 'News & Research', 'title' => $term->name, 'content' => $lead]);

$children = get_terms(['taxonomy' => 'category', 'parent' => $term->term_id, 'hide_empty' => true]);
if (is_wp_error($children)) $children = [];
?>
<section class="scm-section">
  <div class="scm-shell">
    <?php
    $items = [['label' => 'All', 'url' => $posts_page, 'active' => false]];
    foreach (scm_top_categories() as $c) $items[] = ['label' => $c->name, 'url' => get_term_link($c), 'active' => $c->term_id === $term->term_id];
    scm_component('pill-nav', ['items' => $items]);
    if ($children) {
      $topics = [['label' => 'All ' . $term->name, 'url' => get_term_link($term), 'active' => true]];
      foreach ($children as $c) $topics[] = ['label' => $c->name, 'url' => get_term_link($c), 'active' => false];
      echo '<div class="scm-topic-pills">'; scm_component('pill-nav', ['items' => $topics]); echo '</div>';
    }
    ?>

    <?php if ($first && have_posts()): ?>
      <div class="scm-research-lead">
        <?php the_post(); scm_shown_ids(get_the_ID()); scm_component('card-feature', ['words' => 36]); ?>
        <div class="scm-research-lead__stack">
          <?php $n = 0; while (have_posts() && $n < 2): the_post(); $n++; scm_shown_ids(get_the_ID()); scm_component('card-overlay'); endwhile; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php if ($first && $children): foreach ($children as $topic):
  $tq = new WP_Query(['cat' => $topic->term_id, 'posts_per_page' => 3, 'ignore_sticky_posts' => true, 'post__not_in' => scm_shown_ids()]);
  if (!$tq->have_posts()) continue; ?>
  <section class="scm-section scm-section--topic">
    <div class="scm-shell">
      <?php scm_component('section-head', ['eyebrow' => $term->name, 'title' => esc_html($topic->name), 'link' => get_term_link($topic), 'link_label' => 'View all ' . $topic->name]); ?>
      <div class="scm-card-grid scm-card-grid--3">
        <?php while ($tq->have_posts()): $tq->the_post(); scm_component('card-post', ['editorial' => true, 'words' => 20]); endwhile; ?>
      </div>
    </div>
  </section>
  <?php scm_shown_ids(wp_list_pluck($tq->posts, 'ID')); wp_reset_postdata();
endforeach; endif; ?>

<?php if ($first) scm_component('founder-band'); ?>

<section class="scm-section">
  <div class="scm-shell">
    <?php if ($first) scm_component('section-head', ['title' => 'All research', 'link' => '', 'link_label' => '']); ?>
    <?php
    // Remaining posts from the main query, skipping anything already shown above.
    rewind_posts();
    $shown = scm_shown_ids();
    $rendered = 0;
    if (have_posts()): ?>
      <div class="scm-card-grid scm-card-grid--3">
        <?php while (have_posts()): the_post(); if (in_array(get_the_ID(), $shown, true)) continue; $rendered++; scm_component('card-post', ['editorial' => true]); endwhile; ?>
      </div>
    <?php endif; ?>
    <?php if (!$rendered && !$first): ?><p class="scm-empty">Nothing more here.</p><?php endif; ?>
    <?php the_posts_pagination(['prev_text' => '← Newer', 'next_text' => 'Older →']); ?>
  </div>
</section>
<?php get_footer(); ?>
