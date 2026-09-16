<?php
/**
 * Styleguide — Foundations (spec A1–A13) rendered in one place, matching
 * reference/design_system.png §05. Internal QA page, not linked from any menu.
 * Not indexed (noindex via wp_head below) and not part of the header/footer nav.
 */
get_header();
?>
<div class="scm-shell scm-styleguide">
  <header class="scm-page__header"><h1>Styleguide</h1><p class="scm-article__meta">Foundations — docs/COMPONENT-SPEC.md section A. Internal QA only.</p></header>

  <section>
    <h2>A1 · Button</h2>
    <div class="scm-actions-row">
      <a class="scm-button scm-button--gold" href="#">Primary</a>
      <a class="scm-button" href="#">Secondary</a>
      <a class="scm-button scm-button--text" href="#">Text / ghost</a>
      <a class="scm-button scm-button--gold scm-button--sm" href="#">Small</a>
      <button class="scm-button scm-button--gold" disabled>Disabled</button>
      <button class="scm-button scm-button--gold is-loading">Loading</button>
    </div>
    <div class="scm-on-dark scm-styleguide__dark scm-actions-row">
      <a class="scm-button scm-button--gold" href="#">On dark, gold</a>
      <a class="scm-button scm-button--on-dark" href="#">On dark, outline</a>
    </div>
  </section>

  <section>
    <h2>A2 · Text link</h2>
    <div class="scm-actions-row">
      <a class="scm-underlink" href="#">View all →</a>
      <span class="scm-on-dark scm-styleguide__dark-inline"><a class="scm-underlink scm-underlink--on-dark" href="#">View all →</a></span>
    </div>
  </section>

  <section>
    <h2>A3 · Eyebrow label</h2>
    <span class="scm-eyebrow">Eyebrow label</span>
    <span class="scm-eyebrow" style="display:flex;align-items:center;gap:12px;">With dash<span style="width:28px;border-top:1px solid var(--gold);display:inline-block;"></span></span>
  </section>

  <section>
    <h2>A4 · Meta label</h2>
    <span class="scm-meta">Markets · 5 min read</span>
  </section>

  <section>
    <h2>A5 · Section heading row</h2>
    <?php scm_component('section-head', ['eyebrow' => 'Research', 'title' => 'Go beyond the headline.', 'link' => '#', 'link_label' => 'View all']); ?>
  </section>

  <section>
    <h2>A6 · Form controls</h2>
    <div class="scm-styleguide__form-grid">
      <div><input class="scm-field" type="email" placeholder="Email address"></div>
      <div><input class="scm-field scm-field--error" type="email" value="not-an-email"><span class="scm-field-help scm-field-help--error">Enter a valid email address.</span></div>
      <div><select class="scm-field"><option>Select an option</option></select></div>
      <div><textarea class="scm-field" placeholder="Your message…"></textarea></div>
      <div class="scm-field-row"><input class="scm-field" type="email" placeholder="Email address"><button class="scm-button scm-button--gold">Sign up</button></div>
      <label class="scm-check"><input type="checkbox"> I agree to the <a href="#">terms</a> and <a href="#">privacy policy</a></label>
    </div>
    <div class="scm-on-dark scm-styleguide__dark"><input class="scm-field" type="email" placeholder="Email address (on dark)"></div>
  </section>

  <section>
    <h2>A7 · Pill / tab</h2>
    <ul class="scm-tabs"><li><button class="is-active">All</button></li><li><button>Macro</button></li><li><button>Equities</button></li></ul>
  </section>

  <section>
    <h2>A8 · Status chip</h2>
    <div class="scm-actions-row">
      <span class="scm-chip scm-chip--up">+0.8%</span>
      <span class="scm-chip scm-chip--down">−0.4%</span>
      <span class="scm-chip scm-chip--neutral">0.0%</span>
    </div>
  </section>

  <section>
    <h2>A9 · Numbered marker</h2>
    <div class="scm-actions-row"><span class="scm-marker">1</span><span class="scm-marker">2</span><span class="scm-marker">3</span></div>
  </section>

  <section>
    <h2>A10 · Icon</h2>
    <p class="scm-article__meta">No Data — design (reference/design_system.png §05) specifies a custom gold line-icon
    set (Markets/Research/Learn/Education/Membership/Search/Cart/User/Play/Calendar/Location).
    FontAwesome is still in use theme-wide; see docs/BUILD-NOTES.md.</p>
    <div class="scm-actions-row"><i class="fa-solid fa-chart-simple" style="color:var(--gold);font-size:22px;"></i><i class="fa-solid fa-book-open" style="color:var(--gold);font-size:22px;"></i></div>
  </section>

  <section>
    <h2>A11 · Image treatment</h2>
    <div class="scm-styleguide__image">
      <img src="<?php echo esc_url(_i('editorial/london-session.webp')); ?>" alt="">
      <div class="scm-image-caption">Caption block.<br>Navy, uppercase, gold dash.</div>
    </div>
  </section>

  <section>
    <h2>A12 · Hairline</h2>
    <hr class="scm-hairline">
    <hr class="scm-hairline scm-hairline--strong">
  </section>

  <section>
    <h2>A13 · Container</h2>
    <p class="scm-article__meta">Shell 1450px (this page) · <code>.scm-shell--prose</code> 820px · <code>.scm-shell--article-hero</code> 1180px.</p>
  </section>

  <section>
    <h2>C24 · Testimonial (real data)</h2>
    <div class="scm-card-grid scm-card-grid--3">
      <?php
      $q = new WP_Query(['post_type' => 'success_story', 'posts_per_page' => 3]);
      if ($q->have_posts()): while ($q->have_posts()): $q->the_post(); scm_component('testimonial-card'); endwhile; wp_reset_postdata();
      else: echo '<p>No Data — success_story CPT has no posts.</p>'; endif;
      ?>
    </div>
  </section>
</div>
<?php get_footer(); ?>
