<?php
/**
 * Homepage — the real homepage template (WP's static-front-page hierarchy
 * always uses front-page.php over any custom template assigned to the front
 * page in Page Attributes, so this is it — see docs/BUILD-NOTES.md 12 Sep
 * 2026 entry). Composed from partials/components/*.
 * See reference/PHOTO-2026-09-10-11-51-31.jpg and docs/DESIGN-SYSTEM.md.
 */
get_header();

// Collect the two daily briefs so "What's Moving Markets" can exclude them.
$exclude = [];
foreach (['morning-market-brief', 'us-open'] as $slug) {
    $q = scm_first_in_category($slug);
    if ($q) { $q->the_post(); $exclude[] = get_the_ID(); wp_reset_postdata(); }
}

scm_component('hero');
scm_component('proof-bar');
scm_component('static-ticker'); // genuinely static — needs a licensed market-data feed, not an ACF field
scm_component('routes');
scm_component('morning-brief');
scm_component('moving-markets', ['exclude' => $exclude, 'count' => 4]);
scm_component('member-cta'); // full-width "Trader Hub" band — see partials/components/member-cta.php
?>
<section class="scm-section">
  <div class="scm-shell scm-learn">
    <?php scm_component('learn'); ?>
    <div class="scm-learn__visual"></div>
  </div>
</section>
<?php
scm_component('education');
get_footer();
