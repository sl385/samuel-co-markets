<?php

// -- Do Some Stuff here for the courses


if( !is_user_logged_in( ) ) {
    wp_redirect( site_url() );
    die();
}

$course_manager = new User_Course_Manager;
if( !$course_manager->user_can_access_course( get_current_user_id(), $post->ID) ) {
    wp_redirect( site_url() );
    die();
}

$progress = $course_manager->get_user_course_progress( $post->ID, get_current_user_id() );

$show_as_single = get_field('show_as_single_list_of_videos') ;

/**
 * Real "N of N lessons completed" header line — same real "Complete" flag
 * counting as woocommerce/account/courses.php's widget (module/lesson
 * counters below, $c / $lesson_id, are 1-based to match how $progress is
 * actually keyed).
 */
$modules_data = get_field('modules') ?: [];
$total_lessons = 0; $done_lessons = 0;
foreach ($modules_data as $mi => $mod) {
    foreach (($mod['module_lessons'] ?? []) as $li => $lesson) {
        $total_lessons++;
        if (($progress[$mi + 1][$li + 1] ?? null) === 'Complete') $done_lessons++;
    }
}
$course_pct = $total_lessons ? round($done_lessons / $total_lessons * 100) : 0;

get_header();

?>
<div class="scm-course-player scm-on-dark">
    <aside class="scm-course-sidebar <?php if( $show_as_single ) : ?>course__contents--single<?php endif; ?>">
        <a class="scm-course-sidebar__back" href="<?php echo esc_url(site_url('/my-account/my-courses/')); ?>"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to my courses</a>
        <h1 class="scm-course-sidebar__title"><?php the_title(); ?></h1>
        <?php if ($total_lessons): ?>
        <div class="scm-course-sidebar__progress">
            <span><?php echo (int) $done_lessons; ?> of <?php echo (int) $total_lessons; ?> lessons completed</span>
            <div class="scm-my-course__bar"><span style="width:<?php echo (int) $course_pct; ?>%"></span></div>
        </div>
        <?php endif; ?>

        <div class="course__contents">
        <?php if( have_rows('modules') ) : ?>
            <?php $c=1; $i=1; while( have_rows('modules') ) : the_row(); $module_name = get_sub_field('module_name'); ?>
                <div class="course__module<?php if( $c === 1 ) : ?> active<?php endif; ?>" data-module="<?php echo $c; ?>">
                    <header>
                        <h2><span><?php echo $c; ?>.</span> <?php echo esc_html($module_name); ?></h2>
                        <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </header>

                    <?php if( have_rows('module_lessons') ) : ?>
                    <div class="module__lessons">
                        <ul>
                            <?php $lesson_id=1; while( have_rows('module_lessons') ) : the_row();  ?>
                                <li <?php user_has_completed_lesson($c, $lesson_id); ?> data-lesson="<?php echo $lesson_id; ?>"><a data-module="<?php echo $c; ?>" data-lesson="<?php echo $lesson_id; ?>" href="#">
                                    <?php if( $show_as_single ) : ?>
                                        <strong><?php echo esc_html($module_name); ?>:</strong>
                                    <?php else : ?>
                                        <strong>Lesson <?php echo $i; ?>:</strong>
                                    <?php endif; ?>
                                    <?php echo esc_html(get_sub_field('lesson_name')); ?></a>
                                </li>
                            <?php $i++; $lesson_id++; endwhile; ?>
                        </ul>
                    <?php endif; ?>
                    </div>
                </div>
            <?php $c++; endwhile; ?>
        <?php endif; ?>
        </div>
    </aside>

    <div class="lesson__ui">
        <div class="lesson__ui-player" data-platform="html5">
            <?php echo do_shortcode('[sc_player src="#"]'); ?>
        </div>
        <div class="lesson__ui-player" data-platform="vimeo">
            <div class="video-embed">
                <iframe id="player1" src="" width="630" height="354" allow="autoplay" title="Lesson video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
        </div>

        <div class="scm-lesson-body">
            <h2 id="lesson_name"></h2>
            <article id="lesson_content" class="scm-prose"></article>
            <ul id="lesson_assets"></ul>
        </div>

        <button style="display:none" href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="scm-button scm-button--gold" id="button-next">Next Lesson <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
    </div>
</div>

<div class="loader">
    <div>
        <i class="fa-solid fa-bounce fa-clock fa-4x"></i>
        <p>Loading Lesson...</p>
    </div>
</div>

<?php get_footer(); ?>
