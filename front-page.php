<?php
/**
 * Homepage — composed from partials/components/*.
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
scm_component('static-proof-bar');
scm_component('static-ticker');
scm_component('morning-brief');
scm_component('moving-markets', ['exclude' => $exclude, 'count' => 4]);
scm_component('research');
?>
<section class="scm-section">
  <div class="scm-shell scm-learn">
    <?php scm_component('static-learn'); ?>
    <div class="scm-learn__visual"></div>
  </div>
</section>
<?php
scm_component('education');
get_footer();
