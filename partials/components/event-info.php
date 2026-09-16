<?php
/**
 * Event information panel (spec C09-ish, "supporting components" batch).
 * @dynamic — used on single-event.php. Date/time are real (event_date);
 * venue and sold-out are real ACF fields added for this component
 * (includes/theme-acf.php group_scm_event) — venue simply doesn't render a
 * row when empty rather than showing a fake placeholder.
 * args: post_id
 */
$post_id = ($args['post_id'] ?? 0) ?: get_the_ID();
$date = get_field('event_date', $post_id);
$ts = $date ? strtotime($date) : false;
$venue = get_field('event_venue', $post_id);
$sold_out = get_field('event_sold_out', $post_id);
$cost = get_field('event_cost', $post_id);
$book_url = get_field('event_url', $post_id) ?: get_permalink($post_id);

// A same-page "Add to calendar" .ics — no backend needed, just a data URI.
$ics_url = '';
if ($ts) {
  $ics = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nBEGIN:VEVENT\r\nSUMMARY:" . str_replace([",", "\n"], ['\,', '\n'], get_the_title($post_id))
    . "\r\nDTSTART:" . gmdate('Ymd\THis\Z', $ts) . "\r\nDTEND:" . gmdate('Ymd\THis\Z', $ts + 2 * HOUR_IN_SECONDS)
    . ($venue ? "\r\nLOCATION:" . str_replace([",", "\n"], ['\,', '\n'], $venue) : '')
    . "\r\nURL:" . esc_url_raw($book_url) . "\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";
  $ics_url = 'data:text/calendar;charset=utf8,' . rawurlencode($ics);
}
?>
<aside class="scm-event-info">
  <?php if ($cost !== '' && $cost !== null): ?><span class="scm-meta"><?php echo $cost === '0' || strtolower((string) $cost) === 'free' ? 'Free event' : esc_html($cost); ?></span><?php endif; ?>
  <h3><?php echo esc_html(get_the_title($post_id)); ?></h3>

  <?php if ($ts): ?>
    <div class="scm-event-info__row"><span>Date</span><strong><?php echo esc_html(date_i18n('j F Y', $ts)); ?></strong></div>
    <div class="scm-event-info__row"><span>Time</span><strong><?php echo esc_html(date_i18n('g:ia', $ts)); ?></strong></div>
  <?php endif; ?>
  <?php if ($venue): ?>
    <div class="scm-event-info__row"><span>Venue</span><strong><?php echo esc_html($venue); ?></strong></div>
  <?php endif; ?>

  <?php if ($sold_out): ?>
    <div class="scm-event-info__availability scm-event-info__availability--sold-out"><i></i>Sold out</div>
  <?php else: ?>
    <div class="scm-event-info__availability"><i></i>Places available</div>
  <?php endif; ?>

  <div class="scm-actions-row">
    <?php if (!$sold_out): ?>
      <a class="scm-button scm-button--gold" href="<?php echo esc_url($book_url); ?>" target="_blank" rel="noopener">Book now</a>
    <?php endif; ?>
    <?php if ($ics_url): ?>
      <a class="scm-button" href="<?php echo esc_attr($ics_url); ?>" download="<?php echo esc_attr(sanitize_title(get_the_title($post_id))); ?>.ics">Add to calendar</a>
    <?php endif; ?>
  </div>
</aside>
