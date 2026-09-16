<?php
/**
 * Author byline (spec C20). @dynamic
 * Matches reference/single_article.png exactly: avatar, "By {name}", date,
 * read time — all real (WP author + post data). No invented role/title field.
 * args: on_dark (bool, for the hero-overlay placement)
 */
$a = wp_parse_args($args ?? [], ['on_dark' => false]);
$author_id = get_the_author_meta('ID');
?>
<div class="scm-byline<?php echo $a['on_dark'] ? ' scm-byline--on-dark' : ''; ?>">
  <?php echo get_avatar($author_id, 40); ?>
  <div>
    <strong>By <?php the_author(); ?></strong>
    <span class="scm-meta"><?php echo esc_html(get_the_date()); ?> · <?php echo esc_html(scm_read_time()); ?> min read</span>
  </div>
</div>
