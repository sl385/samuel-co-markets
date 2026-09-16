<?php get_header(); ?>
<?php scm_component('hub-hero', [
  'eyebrow' => 'MARKETS',
  'title' => 'Know what is moving markets.',
  'lead' => 'Daily intelligence across equities, FX, commodities, rates and macro — built to help you understand the move, not simply see the headline.',
  'image_class' => 'scm-hub-hero__image--markets',
  'quote' => 'Start with what changed. Then understand why it matters.',
  'primary' => ['Explore today\'s markets', '#latest'],
  'secondary' => ['View membership', home_url('/membership/')],
]); ?>
<nav class="scm-subnav"><div class="scm-shell"><a href="#">Latest</a><a href="#">Morning Brief</a><a href="#">US Open</a><a href="#">Macro</a><a href="#">Equities</a><a href="#">FX</a><a href="#">Commodities</a><a href="#">Rates</a></div></nav>
<section class="scm-section" id="latest">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'LATEST', 'title' => "What's moving markets"]); ?>
    <div class="scm-card-grid scm-card-grid--4">
      <?php $q = scm_latest_excluding([], 8); while ($q->have_posts()): $q->the_post(); scm_component('card-post'); endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
