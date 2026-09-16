<?php
/**
 * Template Name: Briefings
 *
 * Dedicated home for the real Morning Brief / US Open content — reuses
 * partials/components/morning-brief.php as-is (real lead story, real
 * "Complete"-backed key points, real US Open card, the two honest
 * "coming soon" cards for a video briefing and an economic calendar —
 * same real gaps as the homepage's version of this section) rather than
 * rebuilding it, since it's already a real, working component.
 *
 * Per your steer: the actual subscribe mechanism for this page is
 * expected to end up as a Beehiiv form, not confirmed yet — the signup
 * band below still uses the real CF7 493 mailing-list form as an interim,
 * flagged in the review note.
 *
 * @package WordPress
 * @subpackage Sam & Co
 */
get_header();
?>

<?php scm_component('masthead', [
  'eyebrow' => 'Briefings',
  'title' => 'Your daily edge, before the bell.',
  'content' => 'The Morning Brief and US Open — real market commentary, key points and the themes worth watching, every trading day.',
]); ?>

<section class="scm-section">
  <div class="scm-shell">
    <div class="scm-review-note">
      <span class="scm-review-note__tag">TBC — confirm signup platform</span>
      <p>The Morning Brief / US Open content below is real. The subscribe form is expected to move to Beehiiv once that's confirmed — for now it uses the same real mailing-list form (CF7 493) as the rest of the site, so nobody signing up here is lost in the meantime.</p>
    </div>
  </div>
</section>

<?php scm_component('morning-brief'); ?>

<section class="scm-section scm-section--dark scm-on-dark">
  <div class="scm-shell scm-hub__grid">
    <div class="scm-hub__copy">
      <span class="scm-eyebrow">Never miss a briefing</span>
      <h2>Get it in your inbox every weekday</h2>
      <p>Free, real market commentary from the Samuel &amp; Co team — sent before the trading day begins.</p>
      <div class="scm-newsletter" data-newsletter>
        <?php echo do_shortcode('[contact-form-7 id="493" title="Subscription Sign Up Form"]'); ?>
        <div class="scm-newsletter-success" hidden>
          <strong>You're on the list.</strong>
          <span>Look out for your confirmation email.</span>
        </div>
      </div>
    </div>
    <div class="scm-hub__art" aria-hidden="true"></div>
  </div>
</section>

<?php get_footer(); ?>
