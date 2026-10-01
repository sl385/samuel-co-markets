<?php
/**
 * "The Morning Brief" signup panel (navy, in the hero grid). @dynamic
 * Copy from ACF options (Homepage — newsroom sections). Form: native
 * Beehiiv signup (partials/components/newsletter-form.php).
 */
$title  = scm_opt('scm_brief_title', 'Your daily edge, before the bell.');
$text   = scm_opt('scm_brief_text', 'A clear, concise briefing on the 5 things that matter for markets today — straight to your inbox every weekday.');
$points = array_slice(scm_opt_lines('scm_brief_points', ['5 minute read', 'Markets overnight', "Today's key calendar", "What we're watching"]), 0, 4);
$cta    = scm_opt('scm_brief_cta', "Get the Brief — It's Free");
$icons  = ['fa-regular fa-clock', 'fa-solid fa-globe', 'fa-regular fa-calendar', 'fa-regular fa-eye'];
?>
<aside class="scm-brief-panel scm-on-dark" id="morning-brief-signup">
  <span class="scm-eyebrow">The Morning Brief</span>
  <h3><?php echo esc_html($title); ?></h3>
  <p><?php echo esc_html($text); ?></p>
  <ul class="scm-brief-panel__points">
    <?php foreach ($points as $i => $p): ?><li><i class="<?php echo esc_attr($icons[$i % 4]); ?>" aria-hidden="true"></i><?php echo esc_html($p); ?></li><?php endforeach; ?>
  </ul>
  <?php scm_component('newsletter-form', ['cta' => $cta, 'source' => 'homepage-brief-panel', 'layout' => 'stacked']); ?>
</aside>
