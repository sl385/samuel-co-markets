<?php
/**
 * "Choose your route" homepage section. @dynamic
 * Three real commercial destinations. Get Funded and Get Qualified both
 * point at the real Get Funded page's programme matrix (templates/
 * template-funded-2025.php#programmes — a real client-side tab toggle
 * between the two, not two separate pages, so both routes land there and
 * let the visitor pick their tab). "Develop Your Trading" points at the
 * real Trading Courses page — the mock-up's own third "route" bundled
 * courses + tools + Trader Hub into one invented destination with nothing
 * real behind it; Trading Courses was chosen as the single honest anchor
 * (see docs/BUILD-NOTES.md).
 */
$routes = [
  ['n' => '01', 'tag' => 'Funding', 'title' => 'Get Funded', 'text' => 'Develop your trading with structured education, coaching and a route to simulated funded capital.', 'url' => home_url('/get-funded/#programmes'), 'featured' => true],
  ['n' => '02', 'tag' => 'Qualification', 'title' => 'Get Qualified', 'text' => 'Follow an Ofqual-regulated professional pathway combining education, mentoring and trading experience.', 'url' => home_url('/get-funded/#programmes')],
  ['n' => '03', 'tag' => 'Development', 'title' => 'Develop Your Trading', 'text' => 'Build your knowledge through structured courses, trading tools and ongoing market analysis.', 'url' => home_url('/trading-courses/')],
];
?>
<section class="scm-section">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'Choose your route', 'title' => 'Where do you want to go next?']); ?>
    <div class="scm-routes">
      <?php foreach ($routes as $r): ?>
        <a class="scm-route<?php echo !empty($r['featured']) ? ' scm-route--featured' : ''; ?>" href="<?php echo esc_url($r['url']); ?>">
          <span class="scm-route__number"><?php echo esc_html($r['n'] . ' · ' . strtoupper($r['tag'])); ?></span>
          <h3><?php echo esc_html($r['title']); ?></h3>
          <p><?php echo esc_html($r['text']); ?></p>
          <span class="scm-underlink<?php echo !empty($r['featured']) ? ' scm-underlink--on-dark' : ''; ?>">Explore <?php echo esc_html($r['title']); ?> →</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
