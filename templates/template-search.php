<?php
/**
 * Template Name: Search
 *
 * Dedicated landing page for the header search icon — previously that icon
 * linked straight to `/?s=` with no query, which WordPress doesn't even
 * treat as a real search (an empty `s` never sets is_search()), so it just
 * silently fell through to the News & Research archive. This gives it a
 * real page to land on with a prompt; submitting the form here is a normal
 * GET to `/?s=query`, which now renders through the real search.php.
 *
 * @package WordPress
 * @subpackage Sam & Co
 */
?>
<?php get_header(); ?>

<section class="scm-masthead scm-masthead--dark scm-on-dark">
  <div class="scm-shell scm-masthead__grid">
    <div class="scm-masthead__copy">
      <span class="scm-eyebrow">Search</span>
      <h1>What are you looking for?</h1>
      <p class="scm-masthead__lead">Search articles, courses, funding programmes and trading tools.</p>
    </div>
  </div>
</section>

<section class="scm-section"><div class="scm-shell scm-search-page">
  <?php scm_component('search-form', ['large' => true]); ?>
</div></section>

<?php get_footer(); ?>
