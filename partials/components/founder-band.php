<?php
/**
 * "From Samuel Leach" navy band: photo, column intro, latest posts by the
 * founder. @dynamic — copy/photo from ACF options, list from real posts by
 * the configured author (default: the samuel.leach user). No photo set →
 * the band renders without the image column rather than using stock.
 */
$user_id = (int) scm_opt('scm_founder_user', 0);
if (!$user_id) { $u = get_user_by('login', 'samuel.leach'); $user_id = $u ? $u->ID : 0; }
$user = $user_id ? get_userdata($user_id) : null;
$name = $user ? $user->display_name : 'Samuel Leach';
$photo   = scm_opt('scm_founder_photo', '');
$eyebrow = scm_opt('scm_founder_eyebrow', 'From ' . $name);
$title   = scm_opt('scm_founder_title', "Where I see opportunity in today's markets");
$text    = scm_opt('scm_founder_text', "Each week, I share my perspective on the biggest themes moving markets, the opportunities I'm watching and how to stay ahead.");
$cta     = scm_opt('scm_founder_cta', 'Read the latest column');
$url     = scm_opt('scm_founder_url', $user ? get_author_posts_url($user_id) : home_url('/news/'));
$q = $user_id ? new WP_Query(['author' => $user_id, 'posts_per_page' => 3, 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'post__not_in' => scm_shown_ids()]) : null;
$icons = ['fa-regular fa-user', 'fa-solid fa-chart-line', 'fa-regular fa-lightbulb'];
?>
<section class="scm-founder scm-on-dark<?php echo $photo ? '' : ' scm-founder--no-photo'; ?>">
  <div class="scm-shell scm-founder__grid">
    <?php if ($photo): ?><div class="scm-founder__photo" style="background-image:url('<?php echo esc_url($photo); ?>')"></div><?php endif; ?>
    <div class="scm-founder__copy">
      <span class="scm-eyebrow"><?php echo esc_html($eyebrow); ?></span>
      <h2><?php echo esc_html($title); ?></h2>
      <p><?php echo esc_html($text); ?></p>
      <a class="scm-button scm-button--gold" href="<?php echo esc_url($url); ?>"><?php echo esc_html($cta); ?> →</a>
    </div>
    <?php if ($q && $q->have_posts()): ?>
    <div class="scm-founder__latest">
      <span class="scm-eyebrow">Latest from <?php echo esc_html(explode(' ', $name)[0]); ?></span>
      <ul>
        <?php $i = 0; while ($q->have_posts()): $q->the_post(); ?>
          <li><i class="<?php echo esc_attr($icons[$i % 3]); ?>" aria-hidden="true"></i><div><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span><?php echo esc_html(scm_time_ago()); ?></span></div></li>
        <?php $i++; endwhile; wp_reset_postdata(); ?>
      </ul>
    </div>
    <?php endif; ?>
  </div>
</section>
