<?php
/**
 * "Your Courses" — real enrolled courses as a card widget (thumbnail,
 * real per-lesson progress, "Continue" button), replacing a bare
 * unstyled link list. @dynamic
 *
 * Progress: the real `wp_user_lesson_progress` DB table, via the same
 * User_Course_Manager::get_user_course_progress($course_id, $user_id)
 * single-course.php itself uses — NOT the `course_progress` user meta key
 * (a different, apparently-unused legacy source; reading it here first
 * silently showed 0 progress everywhere despite the real course player
 * correctly showing completed lessons, since that meta key was never the
 * one actually being written to). Keyed [module_index][lesson_index] =>
 * "Complete" | a raw number (video watch-position in seconds, not a
 * percentage — real but not reliably normalisable without each lesson's
 * duration). Only the "Complete" flag is trustworthy, so only that is
 * counted. "X of Y lessons completed" + a bar built from that ratio, not
 * an invented figure.
 */
$courses_manager = new User_Course_Manager;
$courses = $courses_manager->get_user_courses(get_current_user_id());
?>

<?php if ($courses): ?>
  <h2 class="scm-my-courses__heading">Your Courses</h2>
  <div class="scm-my-courses">
    <?php foreach ($courses as $course_id): $_course = get_post($course_id); if (!$_course) continue;
      $modules = get_field('modules', $_course->ID) ?: [];
      $progress_for_course = $courses_manager->get_user_course_progress($_course->ID, get_current_user_id());
      $total = 0; $done = 0;
      foreach ($modules as $mi => $mod) {
        foreach (($mod['module_lessons'] ?? []) as $li => $lesson) {
          $total++;
          // single-course.php's own module/lesson counters are 1-based
          // ($c / $lesson_id, both starting at 1) — that's what's actually
          // used as the key into this progress array, so match it here
          // rather than the 0-based array index.
          if (($progress_for_course[$mi + 1][$li + 1] ?? null) === 'Complete') $done++;
        }
      }
      $pct = $total ? round($done / $total * 100) : 0;
    ?>
      <a class="scm-my-course" href="<?php echo esc_url(get_permalink($_course->ID)); ?>">
        <div class="scm-my-course__art">
          <?php if (scm_has_real_thumbnail($_course->ID)) echo get_the_post_thumbnail($_course->ID, 'medium'); else echo '<img src="' . esc_url(_i('editorial/premarket-check.webp')) . '" alt="">'; ?>
        </div>
        <div class="scm-my-course__body">
          <h3><?php echo esc_html($_course->post_title); ?></h3>
          <?php if ($total): ?>
            <span class="scm-my-course__meta"><?php echo (int) $done; ?> of <?php echo (int) $total; ?> lessons completed</span>
            <div class="scm-my-course__bar"><span style="width:<?php echo (int) $pct; ?>%"></span></div>
          <?php else: ?>
            <span class="scm-my-course__meta"><?php echo (int) count($modules); ?> module<?php echo count($modules) === 1 ? '' : 's'; ?></span>
          <?php endif; ?>
        </div>
        <span class="scm-button scm-button--gold scm-my-course__cta">Continue <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <p class="no_subscriptions woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
You have no courses. <a class="woocommerce-Button button" href="<?php echo esc_url(site_url('/trading-courses/')); ?>">
Browse products </a>
</p>
<?php endif; ?>
