<?php
/**
 * Learn topic grid. @dynamic — ACF repeater on the options page ("Homepage
 * (2026)" > Learn topics tab), defaults matching the reference.
 */
$default = [
  ['icon' => 'fa-solid fa-book', 'title' => 'Markets 101', 'text' => 'Start with the foundations'],
  ['icon' => 'fa-solid fa-arrow-right-arrow-left', 'title' => 'Foreign Exchange', 'text' => 'How the currency markets work'],
  ['icon' => 'fa-solid fa-landmark', 'title' => 'Macroeconomics', 'text' => 'Understand the bigger picture'],
  ['icon' => 'fa-solid fa-oil-well', 'title' => 'Commodities', 'text' => 'Key drivers and market structure'],
  ['icon' => 'fa-solid fa-chart-line', 'title' => 'Equities', 'text' => 'Learn to analyse companies'],
  ['icon' => 'fa-solid fa-brain', 'title' => 'Risk & Psychology', 'text' => 'Trade with discipline'],
];
$topics = scm_opt('scm_learn_topics', $default);
?>
<div class="scm-learn__topics">
  <?php // "Learn" doesn't have real per-topic content yet (see learn.php's own header comment) — points at the real Trading Courses page, same as the routes/education sections' equivalent "develop your trading" destination, per request. ?>
  <?php scm_component('section-head', ['title' => 'Learn', 'link' => home_url('/trading-courses/'), 'link_label' => 'Explore all topics']); ?>
  <p class="scm-learn__intro">Build the knowledge behind better decisions.</p>
  <div class="scm-learn-grid scm-learn-grid--2">
    <?php foreach ($topics as $topic): ?>
      <a class="scm-learn-item" href="<?php echo esc_url(home_url('/trading-courses/')); ?>"><i class="scm-learn-item__icon <?php echo esc_attr($topic['icon']); ?>" aria-hidden="true"></i><span class="scm-learn-item__text"><strong><?php echo esc_html($topic['title']); ?></strong><span><?php echo esc_html($topic['text']); ?></span></span></a>
    <?php endforeach; ?>
  </div>
</div>
