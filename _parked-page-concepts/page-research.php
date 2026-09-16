<?php get_header(); ?>
<?php scm_component('hub-hero', [
  'eyebrow' => 'RESEARCH',
  'title' => 'Go beyond the headline.',
  'lead' => 'Structured market research with a clear thesis, bull case, bear case, risks and the evidence that would change our view.',
  'image_class' => 'scm-hub-hero__image--research',
  'quote' => 'Research should help you think better — not tell you what to think.',
  'primary' => ['View latest research', '#latest'],
  'secondary' => ['View membership', home_url('/membership/')],
]); ?>
<nav class="scm-subnav"><div class="scm-shell"><a href="#">Latest</a><a href="#">Macro</a><a href="#">Equities</a><a href="#">FX</a><a href="#">Commodities</a><a href="#">Rates</a><a href="#">Themes</a></div></nav>
<section class="scm-section" id="latest">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'RESEARCH LIBRARY', 'title' => 'Latest research']); ?>
    <div class="scm-card-grid scm-card-grid--3">
      <?php $q = scm_posts_by_category_slug('research', 9); while ($q->have_posts()): $q->the_post(); scm_component('card-post'); endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
