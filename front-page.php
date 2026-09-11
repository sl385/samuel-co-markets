<?php
get_header();

$morning = scm_posts_by_category_slug('morning-market-brief', 1);
$usopen  = scm_posts_by_category_slug('us-open', 1);
$research = scm_posts_by_category_slug('research', 3);
$exclude = [];
if ($morning->have_posts()) { $morning->the_post(); $morning_id = get_the_ID(); $exclude[] = $morning_id; wp_reset_postdata(); }
if ($usopen->have_posts()) { $usopen->the_post(); $usopen_id = get_the_ID(); $exclude[] = $usopen_id; wp_reset_postdata(); }
$markets = scm_latest_excluding($exclude, 4);
?>
<section class="scm-hero">
  <div class="scm-shell scm-hero__grid">
    <div class="scm-hero__copy">
      <h1>Markets.<br>Research.<br>Education.</h1>
      <p class="scm-hero__lead">Understand what is moving markets — and build the knowledge to act with confidence.</p>
      <p class="scm-hero__sub">Independent market intelligence from a trading business established in 2012.</p>
      <form id="morning-brief-signup" class="scm-signup" action="#" method="post">
        <input type="email" name="email" placeholder="Enter your email address" required>
        <button class="scm-button scm-button--gold" type="submit">Get the Morning Brief — Free</button>
      </form>
      <small>Delivered every weekday morning. Free. Unsubscribe anytime.</small>
    </div>
    <div class="scm-hero__visual">
      <div class="scm-hero__values">Discipline<br>Knowledge<br>Opportunity<br>A Brighter<br>Tomorrow</div>
      <div class="scm-hero__quote">
        <blockquote>“Better information leads to better decisions.”</blockquote>
        <cite>Samuel Leach · Founder, Samuel &amp; Co Trading</cite>
      </div>
    </div>
  </div>
</section>

<section class="scm-proof">
  <div class="scm-shell scm-proof__grid">
    <div><strong>Independent Analysis</strong><span>Clear thinking, not noise.</span></div>
    <div><strong>Real-world Education</strong><span>Built around markets.</span></div>
    <div><strong>A Global Community</strong><span>For serious traders.</span></div>
    <div><strong>Trusted Since 2012</strong><span>More than a decade in markets.</span></div>
  </div>
</section>

<section class="scm-ticker" aria-label="Market snapshot">
  <div class="scm-shell scm-ticker__grid">
    <?php foreach ([['FTSE 100','—'],['S&P 500','—'],['Nasdaq','—'],['GBP/USD','—'],['EUR/USD','—'],['Gold','—'],['Brent','—'],['Bitcoin','—'],['US 10Y','—']] as $t): ?>
      <div class="scm-tick"><span><?php echo esc_html($t[0]); ?></span><strong><?php echo esc_html($t[1]); ?></strong></div>
    <?php endforeach; ?>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell scm-brief">
    <div class="scm-brief__lead">
      <span class="scm-eyebrow">THE MORNING BRIEF</span>
      <?php if ($morning->have_posts()): $morning->the_post(); ?>
        <h2><?php the_title(); ?></h2>
        <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 34)); ?></p>
        <div class="scm-actions-row">
          <a class="scm-button scm-button--gold" href="<?php the_permalink(); ?>">Read Today's Brief →</a>
          <a class="scm-underlink" href="<?php echo esc_url(get_category_link(get_cat_ID('Morning Market Brief'))); ?>">View Brief Archive</a>
        </div>
      <?php else: ?>
        <h2>Oil Near $100 as Markets Turn to CPI</h2>
        <p>Oil prices rise sharply as geopolitical tensions intensify, with traders focused on inflation, rates and the next major data release.</p>
      <?php endif; wp_reset_postdata(); ?>
      <ul class="scm-brief-points"><li>Oil remains the dominant inflation story</li><li>US futures steady ahead of CPI</li><li>Gold benefits from renewed risk demand</li><li>Central-bank expectations remain in focus</li></ul>
    </div>
    <div class="scm-brief__image">
      <?php if (!empty($morning_id) && has_post_thumbnail($morning_id)) echo get_the_post_thumbnail($morning_id, 'large'); else echo '<img src="https://www.samuelandcotrading.com/wp-content/uploads/2026/09/2026-09-09-morning-v2-notext.webp" alt="Morning market brief">'; ?>
      <div class="scm-image-caption">Higher Prices.<br>Bigger Questions.</div>
    </div>
    <aside class="scm-usopen">
      <span class="scm-eyebrow">US OPEN</span>
      <?php if ($usopen->have_posts()): $usopen->the_post(); ?>
        <h3><?php the_title(); ?></h3>
        <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 24)); ?></p>
        <a class="scm-button scm-button--gold" href="<?php the_permalink(); ?>">Read latest update →</a>
        <?php if (has_post_thumbnail()) the_post_thumbnail('medium_large'); else echo '<img src="https://www.samuelandcotrading.com/wp-content/uploads/2026/09/us-reopen.webp" alt="US Open markets">'; ?>
      <?php else: ?>
        <h3>A calmer open, but all eyes remain on inflation</h3><p>A concise update into the US session.</p><a class="scm-button scm-button--gold" href="#">Read latest update →</a><img src="https://www.samuelandcotrading.com/wp-content/uploads/2026/09/us-reopen.webp" alt="US Open markets">
      <?php endif; wp_reset_postdata(); ?>
    </aside>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell">
    <div class="scm-section-head"><div><span class="scm-eyebrow">MARKETS</span><h2>What's Moving Markets</h2></div><a class="scm-underlink" href="<?php echo esc_url(home_url('/news/')); ?>">View all news →</a></div>
    <div class="scm-card-grid scm-card-grid--4">
      <?php while ($markets->have_posts()): $markets->the_post(); ?>
        <article class="scm-card">
          <a class="scm-card__image" href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail()) the_post_thumbnail('medium_large'); ?></a>
          <div class="scm-card__body"><span class="scm-meta"><?php echo esc_html(scm_read_time()); ?> min read</span><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 20)); ?></p></div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell scm-research-layout">
    <div>
      <div class="scm-section-head"><div><span class="scm-eyebrow">RESEARCH</span><h2>Go beyond the headline.</h2></div><a class="scm-underlink" href="<?php echo esc_url(home_url('/research/')); ?>">View all research →</a></div>
      <div class="scm-card-grid scm-card-grid--3">
        <?php if ($research->have_posts()): while ($research->have_posts()): $research->the_post(); ?>
          <article class="scm-card"><a class="scm-card__image" href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail()) the_post_thumbnail('medium_large'); ?></a><div class="scm-card__body"><span class="scm-meta"><?php echo esc_html(scm_read_time()); ?> min read</span><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 18)); ?></p></div></article>
        <?php endwhile; else: ?>
          <article class="scm-card"><div class="scm-card__image"><img src="https://www.samuelandcotrading.com/wp-content/uploads/2026/09/carry-trade.webp" alt="Carry trade research"></div><div class="scm-card__body"><span class="scm-meta">Themes · 5 min read</span><h3>How Carry Trades Work — and When They Break</h3><p>A practical look at the mechanics and risks behind carry.</p></div></article>
          <article class="scm-card"><div class="scm-card__image"><img src="https://www.samuelandcotrading.com/wp-content/uploads/2026/09/oil-risk.webp" alt="Oil research"></div><div class="scm-card__body"><span class="scm-meta">Macro · 7 min read</span><h3>What $100 Oil Means for Global Inflation</h3><p>How higher energy prices feed into rates and the economy.</p></div></article>
          <article class="scm-card"><div class="scm-card__image"><img src="https://www.samuelandcotrading.com/wp-content/uploads/2026/09/bunds.webp" alt="European bond research"></div><div class="scm-card__body"><span class="scm-meta">Rates · 8 min read</span><h3>The Investment Case for European Duration</h3><p>What growth, inflation and policy expectations imply for bonds.</p></div></article>
        <?php endif; wp_reset_postdata(); ?>
      </div>
    </div>
    <aside class="scm-member"><span class="scm-eyebrow">SAMUEL &amp; CO MARKETS</span><h3>Join Samuel &amp; Co Markets</h3><p>Daily briefings, premium research, live market sessions and structured learning in one membership.</p><a class="scm-button scm-button--gold scm-button--block" href="<?php echo esc_url(home_url('/membership/')); ?>">Join for £99/month</a><ul><li>Morning &amp; US Open Briefs</li><li>Premium research</li><li>Weekly live market session</li><li>Complete lesson library</li><li>Trader community</li><li>Member events &amp; more</li></ul></aside>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell scm-learn">
    <div><span class="scm-eyebrow">LEARN</span><h2>Build the knowledge behind better decisions.</h2><div class="scm-learn-grid">
      <?php foreach ([['Markets 101','Start with the foundations.'],['Macroeconomics','Understand the bigger picture.'],['Equities','Learn to analyse companies.'],['FX','Understand currency markets.'],['Commodities','Key drivers and market structure.'],['Risk & Psychology','Build discipline and resilience.']] as $topic): ?>
        <a class="scm-learn-item" href="<?php echo esc_url(home_url('/learn/')); ?>"><strong><?php echo esc_html($topic[0]); ?></strong><span><?php echo esc_html($topic[1]); ?></span></a>
      <?php endforeach; ?>
    </div></div>
    <div class="scm-learn__visual"></div>
  </div>
</section>

<section class="scm-education"><div class="scm-shell scm-education__grid">
  <div class="scm-education__intro"><span class="scm-eyebrow">EDUCATION</span><h2>Take Your Education Further</h2><p>Structured qualifications designed for serious traders.</p></div>
  <a class="scm-qualification" href="<?php echo esc_url(home_url('/product/level-5-diploma-in-financial-trading/')); ?>"><span class="scm-meta">OFQUAL-REGULATED</span><h3>Level 5 Diploma in Financial Trading</h3><span>Explore the Level 5 →</span></a>
  <a class="scm-qualification" href="<?php echo esc_url(home_url('/product/formula-for-success/')); ?>"><span class="scm-meta">ADVANCED EDUCATION</span><h3>Level 7 Diploma in Applied Financial Trading</h3><span>Explore the Level 7 →</span></a>
</div></section>
<?php get_footer(); ?>
