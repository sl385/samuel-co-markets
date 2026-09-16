

<?php 
    $courses_manager = new User_Course_Manager;
    
    $user_progress = ( get_user_meta( get_current_user_id(), 'course_progress', true ) ) ? get_user_meta( get_current_user_id(), 'course_progress', true ) : [];

?>

<?php //$courses = $courses_manager->get_user_courses( get_current_user_id(), false ); 

$courses = $courses_manager->get_user_courses( get_current_user_id() ); 

?>

<?php if( $courses ) : ?>
    <h2>Your Courses</h2>
    <div class="account-courses">
        <?php foreach( $courses as $course ) : $_course = get_post($course); 
        
            if( $_course ) :
            $data = get_field('modules', $_course->ID); ?>
            <a class="course" href="<?php echo get_permalink($_course->ID); ?>">

                <?php // echo  $courses_manager->user_can_access_course(  get_current_user_id(), $_course->ID ); ?>

                <header>
                    <h3><?php echo $_course->post_title; ?></h3>
                    <h4>Modules: <?php echo sizeof($data); ?> 
                </h4>
                </header>

                <i class="fas fa-angle-right"></i>
            </a>
        <?php endif; endforeach; ?>
    </div>
<?php else: ?>
    <p class="no_subscriptions woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
You have no courses. <a class="woocommerce-Button button" href="<?php echo site_url(); ?>/trading-courses/">
Browse products </a>
</p>
<?php endif; ?>

