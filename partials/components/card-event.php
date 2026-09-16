<?php
/**
 * Event card (spec C23): image + date badge, date line, title, cost, CTA. @dynamic
 * Use inside a loop on post_type=event. Real ACF fields: event_date, event_cost,
 * event_url (ported as-is from partials/loop-events.php).
 */
$date = get_field('event_date');
$ts = $date ? strtotime($date) : false;
$url = get_field('event_url') ?: get_permalink();
$is_past = $ts && $ts < current_time('timestamp');
?>
<article <?php post_class('scm-card scm-card--event' . ($is_past ? ' scm-card--past' : '')); ?>>
  <a class="scm-card__image scm-card__image--event" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
    <?php scm_the_thumbnail('medium_large'); ?>
    <?php if ($ts): ?>
      <span class="scm-card__date-badge">
        <span><?php echo esc_html(date('M', $ts)); ?></span>
        <span><?php echo esc_html(date('d', $ts)); ?></span>
      </span>
    <?php endif; ?>
  </a>
  <div class="scm-card__body">
    <span class="scm-meta"><?php echo $ts ? esc_html(date('l jS F Y', $ts)) : 'No Data — event date'; ?></span>
    <h3><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a></h3>
    <p class="scm-card__event-cost">Cost: <?php echo get_field('event_cost') ? esc_html(get_field('event_cost')) : 'No Data — event cost'; ?></p>
    <a class="scm-button scm-button--sm" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener"><?php echo $is_past ? 'View details' : 'Read more'; ?></a>
  </div>
</article>
