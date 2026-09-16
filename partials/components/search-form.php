<?php
/**
 * Site search form (templates/template-search.php landing page,
 * search.php results page). Real GET submit to home_url('/') with the
 * native `s` query var — no JS, works exactly like WordPress search
 * always has, just styled to match the rest of the 2026 design.
 * args: large (bool, bigger field on the dedicated landing page)
 */
$a = wp_parse_args($args ?? [], ['large' => false]);
?>
<form class="scm-search-form<?php echo $a['large'] ? ' scm-search-form--lg' : ''; ?>" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
  <input class="scm-field" type="search" name="s" placeholder="Search articles, courses, products…" value="<?php echo esc_attr(get_search_query()); ?>" autofocus>
  <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
</form>
