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

//$user_progress = SCO_Helpers::getCourseProgress( $post->ID );

$progress = $course_manager->get_user_course_progress( $post->ID, get_current_user_id() );


$show_as_single = get_field('show_as_single_list_of_videos') ;

get_header(); 

?>
<section class="section">
    <div class="container container--s">
        <h1 class="course_header"><?php the_title(); ?></h1>
        <div class="course_ui">
            <aside class="course__contents <?php if(  get_field('show_as_single_list_of_videos') ) : ?>course__contents--single<?php endif; ?>">
                
            <?php if( have_rows('modules') ) : ?>
                <?php $c=1; $i=1; while( have_rows('modules') ) : the_row(); $module_name = get_sub_field('module_name'); ?>
                    <div class="course__module <?php if( $module>=$c) : ?>active<?php endif; ?>" data-module="<?php echo $c; ?>">
                        <header <?php /* class=" <?php if( $c>$module ) : ?>disabled<?php endif; ?>" */ ?> >
                            <h2 ><span><?php echo $c; ?>.</span> <?php echo get_sub_field('module_name'); ?></h2>
                            <i class="fas fa-chevron-down"></i>
                        </header>

                        <?php if( have_rows('module_lessons') ) : ?>
                        <div class="module__lessons">
                            <ul>
                                <?php $lesson_id=1; $lesson_counter=1; while( have_rows('module_lessons') ) : the_row();  ?>
                                    <?php /* if( $module > $c ) : ?>
                                        <li  data-lesson="<?php $lesson_id; ?>"><a data-module="<?php echo $c; ?>" data-lesson="<?php echo $lesson_counter; ?>"  href="#"><strong>Lesson&nbsp;<?php echo $i; ?>:</strong> <?php echo get_sub_field('lesson_name'); ?></a></li>
                                    <?php else : ?>
                                      
                                        <li <?php if( $lesson_counter>$lesson ) : ?>class="disabled"<?php endif; ?> ><a  data-module="<?php echo $c; ?>" data-lesson="<?php echo $lesson_counter; ?>" href="#"><strong>Lesson&nbsp;<?php echo $i; ?>:</strong> <?php echo get_sub_field('lesson_name'); ?></a></li>
                                    <?php endif; */ ?>

                                    <li  <?php user_has_completed_lesson($c, $lesson_id); ?> data-lesson="<?php $lesson_id; ?>"><a data-module="<?php echo $c; ?>" data-lesson="<?php echo $lesson_counter; ?>"  href="#">
                                        <?php if( $show_as_single  ) : ?>
                                            <strong><?php echo str_replace(" ", "&nbsp;", $module_name); ?>:</strong> 
                                        <?php else : ?>
                                            <strong>Lesson&nbsp;<?php echo $i; ?>:</strong> 
                                        <?php endif; ?>
                                        <?php echo get_sub_field('lesson_name'); ?></a>
                                    </li>

                                    
                                <?php $i++; $lesson_counter++; $lesson_id++; endwhile; ?>
                            </ul>
                        <?php endif; ?>
                        </div>
                    </div>
                <?php $c++; endwhile; ?>
            <?php endif; ?>
            </aside>

            

            <div class="lesson__ui">
         
            

            <div class="lesson__ui-player" data-platform="html5">
                <?php echo do_shortcode('[sc_player src="#"]'); ?>
                </div>
                <div class="lesson__ui-player" data-platform="vimeo">
                    <div class="video-embed">
                        <iframe id="player1" src="" width="630" controls="false" title="false" height="354" allow="autoplay" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>

                    <?php /*
                    <div class="vimeo-controls">
                        <div>
                            <button class="vimeo-play"><i class="fa-solid fa-play"></i></button>
                            <button class="vimeo-pause"><i class="fa-solid fa-pause"></i></button>
                        </div>
                        <div>

                        </div>
                        <div>
                            <button class="vimeo-mute"><i class="fa-solid fa-volume"></i></button>               
                        </div>
                      
                    </div>
                    */ ?>

                </div>
                <h2 id="lesson_name"></h2>
                <article id="lesson_content"></article>
                <ul id="lesson_assets"></ul>
             

                <button style="display:none" href="<?php echo get_permalink($post->ID); ?>" class="button button-hover--alt" id="button-next">Next Lesson</button>
            </div>
        </div>

    </div>
</section>

<div class="loader">
    <div>
        <i class="fa-solid fa-bounce fa-clock fa-4x"></i>
        <p>Loading Lesson...</p>
    </div>
</div>

<?php get_footer(); ?>