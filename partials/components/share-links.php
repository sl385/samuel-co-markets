<?php
/**
 * Share links (reference/single_article.png) — new, not in COMPONENT-SPEC by
 * name but present on the article mockup. Real permalink/title, no fake counts.
 */
$url = urlencode(get_permalink());
$title = urlencode(get_the_title());
?>
<div class="scm-share">
  <span class="scm-meta scm-share__label">Share</span>
  <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener" aria-label="Share on X"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a>
  <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><i class="fa-brands fa-linkedin" aria-hidden="true"></i></a>
  <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="fa-brands fa-facebook" aria-hidden="true"></i></a>
  <a href="mailto:?subject=<?php echo $title; ?>&body=<?php echo $url; ?>" aria-label="Share by email"><i class="fa-regular fa-envelope" aria-hidden="true"></i></a>
</div>
