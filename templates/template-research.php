<?php
/**
 * Template Name: Research
 *
 * A hub linking to the two real destinations underneath it — Markets
 * (templates/template-markets.php) and Briefings (templates/
 * template-briefings.php) — reusing the same .scm-route card pattern
 * built for the homepage's "Choose your route" section rather than a new
 * component, since it's the same "pick a destination" shape. Both
 * destinations are themselves still concept/TBC content (see each
 * template's own review note) — this hub doesn't add any new real data
 * of its own, just real navigation to real pages.
 *
 * @package WordPress
 * @subpackage Sam & Co
 */
get_header();
?>

<?php scm_component('masthead', [
  'eyebrow' => 'Research',
  'title' => 'Markets and briefings, in one place.',
  'content' => 'Everything Samuel & Co publishes on what\'s moving markets right now — daily briefings and the wider market picture.',
]); ?>

<section class="scm-section">
  <div class="scm-shell">
    <div class="scm-routes scm-routes--2">
      <a class="scm-route scm-route--featured" href="<?php echo esc_url(home_url('/markets/')); ?>">
        <span class="scm-route__number">MARKETS</span>
        <h3>Markets</h3>
        <p>Real-time market snapshot, sector context and the latest research on what's moving right now.</p>
        <span class="scm-underlink scm-underlink--on-dark">Explore Markets →</span>
      </a>
      <a class="scm-route" href="<?php echo esc_url(home_url('/briefings/')); ?>">
        <span class="scm-route__number">BRIEFINGS</span>
        <h3>Briefings</h3>
        <p>The Morning Brief and US Open — real commentary and key points, every trading day before the bell.</p>
        <span class="scm-underlink">Explore Briefings →</span>
      </a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
