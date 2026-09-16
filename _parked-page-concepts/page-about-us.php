<?php
/**
 * About Us — drafted first as a design canvas (see docs/BUILD-NOTES.md), then
 * built here for real. Matches reference/about.png's section structure
 * (hero+quote, story/impact/mission, journey timeline, team, join CTA, belief
 * band) with real data swapped in: the `team` CPT (10 real members, real roles
 * and LinkedIn links) instead of the mockup's fictional 4, and impact stats
 * marked "No Data" rather than the mockup's invented "100,000+ members".
 */
get_header();
?>
<section class="scm-about-hero">
  <div class="scm-shell scm-about-hero__grid">
    <div class="scm-about-hero__copy">
      <span class="scm-eyebrow">About Us</span>
      <h1>Built by traders.<br>For what's next.</h1>
      <p>Samuel &amp; Co Trading was founded in 2012 on a simple belief: that with the right knowledge, discipline and support, anyone can take control of their financial future.</p>
      <a class="scm-button scm-button--gold" href="#story">Our story →</a>
    </div>
    <div class="scm-about-hero__image" style="background-image:url('<?php echo esc_url(_i('fallback/fallback-1-canary-wharf.webp')); ?>')">
      <div class="scm-about-hero__quote">&ldquo;Better traders. Brighter tomorrows.&rdquo;</div>
    </div>
  </div>
</section>

<section class="scm-section" id="story">
  <div class="scm-shell scm-about-story">
    <div>
      <span class="scm-eyebrow">Our Story</span>
      <h2>A simple idea that grew.</h2>
      <p>What started as one trader sharing what he'd learned has grown into a trading education and research business built around a straightforward idea — better information leads to better decisions.</p>
      <p>Over a decade on, that idea still shapes everything we build: clear education, honest research, and a community that takes the markets seriously.</p>
      <a class="scm-underlink" href="#journey">Our journey →</a>
    </div>
    <div class="scm-about-story__image">
      <img src="<?php echo esc_url(_i('fallback/fallback-4-trading-floor.webp')); ?>" alt="Trading floor at the New York Stock Exchange">
      <div class="scm-image-caption">Discipline creates<br>opportunity</div>
    </div>
    <div class="scm-about-story__side">
      <div>
        <span class="scm-eyebrow">Our Impact</span>
        <ul class="scm-about-stats">
          <li><strong>No Data</strong><span>Members worldwide — design shows "100,000+", no real figure available</span></li>
          <li><strong>No Data</strong><span>Educational resources — design shows "500+", no real figure available</span></li>
          <li><strong>2012</strong><span>Founded</span></li>
        </ul>
      </div>
      <div class="scm-about-mission">
        <span class="scm-eyebrow">Our Mission</span>
        <p>To give traders clear, practical education and honest research — the knowledge to make their own decisions with confidence.</p>
        <div class="scm-about-mission__sig">Samuel</div>
        <span class="scm-meta">Samuel Leach, Founder</span>
      </div>
    </div>
  </div>
</section>

<section class="scm-section scm-section--soft" id="journey">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'Our Journey', 'title' => 'From one trader to a trading community.']); ?>
    <div class="scm-about-timeline">
      <?php foreach ([
        ['2012', 'Samuel & Co Trading founded.'],
        ['Early years', 'Community and course library grow.'],
        ['Since', 'Structured education programme takes shape.'],
        ['More recently', 'Research and membership offering introduced.'],
        ['Today', 'Continuing to build for the next generation of traders.'],
      ] as [$year, $text]): ?>
        <div class="scm-about-timeline__item"><strong><?php echo esc_html($year); ?></strong><p><?php echo esc_html($text); ?></p></div>
      <?php endforeach; ?>
    </div>
    <p class="scm-article__meta" style="margin-top:24px;">No Data — exact milestone dates/details beyond the 2012 founding aren't available yet; these are structural placeholders.</p>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'Meet The Team', 'title' => 'The people behind Samuel &amp; Co.']); ?>
    <div class="scm-about-team-grid">
      <?php
      $team = new WP_Query(['post_type' => 'team', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC']);
      while ($team->have_posts()): $team->the_post(); scm_component('team-card'); endwhile; wp_reset_postdata();
      ?>
    </div>
  </div>
</section>

<section class="scm-section scm-section--soft">
  <div class="scm-shell scm-about-join">
    <div>
      <h3>Join our community.</h3>
      <p>Be part of a global community of traders, learners and forward thinkers.</p>
    </div>
    <a class="scm-button scm-button--gold" href="#morning-brief-signup">Join Free →</a>
  </div>
</section>

<section class="scm-about-belief" style="background-image:url('<?php echo esc_url(_i('fallback/fallback-2-london-skyline.webp')); ?>')">
  <div class="scm-shell scm-about-belief__grid scm-on-dark">
    <div>
      <span class="scm-eyebrow">Our Belief</span>
      <h2>Knowledge today.<br>A brighter tomorrow.</h2>
    </div>
    <p class="scm-about-belief__quote">&ldquo;The best investment you can make is in yourself.&rdquo;</p>
  </div>
</section>
<?php get_footer(); ?>
