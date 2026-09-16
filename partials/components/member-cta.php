<?php
/**
 * "Join Samuel & Co Markets" sticky panel. @dynamic (ACF options, with defaults)
 * Price/URL should ultimately come from the Woo subscription product.
 */
$title = scm_opt('scm_member_title', 'Join Samuel & Co Markets');
$text  = scm_opt('scm_member_text', 'Daily briefings. Premium research. Weekly live sessions. A serious trader community.');
$cta   = scm_opt('scm_member_cta', 'Join for £99/month');
$url   = scm_opt('scm_member_url', home_url('/membership/'));
$benefits = scm_opt_lines('scm_member_benefits', ['Morning & US Open Briefs', 'Premium research', 'Weekly live market session', 'Complete lesson library', 'Trader community', 'Member events & more']);
?>
<aside class="scm-member">
  <h3><?php echo esc_html($title); ?></h3>
  <p><?php echo esc_html($text); ?></p>
  <a class="scm-button scm-button--gold scm-button--block" href="<?php echo esc_url($url); ?>"><?php echo esc_html($cta); ?></a>
  <ul><?php foreach ($benefits as $b): ?><li><?php echo esc_html($b); ?></li><?php endforeach; ?></ul>
</aside>
