<?php
/**
 * Learn topic grid. @static
 * To make dynamic: a "learning path" taxonomy or child pages of /learn/ with an
 * icon field; loop those instead of $topics.
 */
$topics = [
  ['fa-solid fa-book', 'Markets 101', 'Start with the foundations'],
  ['fa-solid fa-arrow-right-arrow-left', 'FX', 'How the currency markets work'],
  ['fa-solid fa-landmark', 'Macroeconomics', 'Understand the bigger picture'],
  ['fa-solid fa-oil-well', 'Commodities', 'Key drivers and market structure'],
  ['fa-solid fa-chart-line', 'Equities', 'Learn to analyse companies'],
  ['fa-solid fa-brain', 'Risk & Psychology', 'Trade with discipline'],
];
?>
<div class="scm-learn__topics">
  <?php scm_component('section-head', ['title' => 'Learn', 'link' => home_url('/learn/'), 'link_label' => 'Explore all topics']); ?>
  <p class="scm-learn__intro">Build the knowledge behind better decisions.</p>
  <div class="scm-learn-grid scm-learn-grid--2">
    <?php foreach ($topics as [$icon, $title, $text]): ?>
      <a class="scm-learn-item" href="<?php echo esc_url(home_url('/learn/')); ?>"><i class="scm-learn-item__icon <?php echo esc_attr($icon); ?>" aria-hidden="true"></i><span class="scm-learn-item__text"><strong><?php echo esc_html($title); ?></strong><span><?php echo esc_html($text); ?></span></span></a>
    <?php endforeach; ?>
  </div>
</div>
