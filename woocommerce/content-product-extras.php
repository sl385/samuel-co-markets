<?php 

$bg = [
    0 => "section--dark",
    1 => "",
    2 => "section--light",
    4 => "",
]
?>

<?php $i=0; ?>

<?php global $product;  ?>
<?php if( get_field('what_do_you_get_content', $product->get_id() ) ) : ?>
<section class="section <?php echo $bg[$i]; ?> section--product section--product-what">
    <div class="container container--s">
       <h2>What Do You Get?</h2>
       <div class="wp-content wp-content-page"><?php echo get_field('what_do_you_get_content', $product->get_id()); ?></div>
        <?php woocommerce_template_single_add_to_cart(); ?>
    </div>

    <div class="gfx gfx--arrow"></div>
    <div class="gfx gfx--grid"></div>
    <div class="gfx gfx--circle-lg"></div>

</section>
<?php $i++; endif; ?>

<?php if( get_field('benefits_content', $product->get_id()) ) : ?>
<section class="section <?php echo $bg[$i]; ?> section--product ">
    <div class="container container--s">
        <h2>The Benefits</h2>
        <div class="wp-content wp-content-page"><?php echo get_field('benefits_content', $product->get_id()); ?></div>
        <?php woocommerce_template_single_add_to_cart(); ?>
    </div>
</section>
<?php $i++; endif; ?>

<?php $courses = get_post_meta( $product->get_id(), '_sc_courses', true); ?>

<?php if( $courses ) : ?>
<section class="section <?php echo $bg[$i]; ?> section--product section--product-ben">
    <div class="container container--s">
        <h2>Here's What You'll Learn</h2>

        <?php if( get_field('here_is_what_you_will_learn_summary', $product->get_id()) ) : ?>

            <?php $videos=[]; foreach(  $courses as $_course ) : $course = get_post( $_course ); ?>
                 <?php $modules = get_field('modules', $course->ID ); ?>
                 <?php foreach( $modules as $module ) : foreach( $module['module_lessons'] as $lesson ) : $videos[] = $lesson;  endforeach;  endforeach; ?>
            <?php endforeach; ?>

            <div class="wp-content wp-content-page"><?php echo get_field('here_is_what_you_will_learn_summary', $product->get_id()); ?></div>
            <div class="wp-content wp-content-post">
               <h3>Here are some example videos</h3>
               <?php $reversed = array_reverse($videos); ?>
               <ul>
                    <?php $c=0; foreach( $reversed as $lesson ) : if( $c==6 ) break; ?>
                        <li><?php echo $lesson['lesson_name']; ?></li>
                    <?php $c++; endforeach; ?>
                </ul>
            </div>

           
            
        <?php else: ?>

        <?php foreach(  $courses as $_course ) : $course = get_post( $_course ); ?>
            <?php $modules = get_field('modules', $course->ID ); ?>
            <?php $i=1; $step = 1; foreach( $modules as $module ) : ?>
                <div class="course-module wp-content-post">
                    <header class="course-module__header">
                        <h3>Section <?php echo $i; ?>: <?php echo $module['module_name']; ?></h3>
                    </header>
                    <ul>
                        <?php foreach( $module['module_lessons'] as $lesson ) : ?>
                            <li><strong>Lesson <?php echo $step; ?>:</strong> <?php echo $lesson['lesson_name']; ?></li>
                        <?php $step++; endforeach; ?>
                    </ul>
                </div>
            <?php $i++; endforeach; ?>
        <?php endforeach; ?>

        <?php endif; ?>

        <?php woocommerce_template_single_add_to_cart(); ?>
    </div>
</section>
<?php $i++; endif; ?>

<?php if( have_rows('faqs', $product->get_id()) ) : ?>
<section class="section <?php echo $bg[$i]; ?> section--product section--product-faqs">
    <div class="container container--s">
        <h2>FAQ's</h2>

        <div class="faqs">
        <?php while( have_rows('faqs', $product->get_id()) ) : the_row(); ?>
            <details class="faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <summary class="faq__question" >
                    <strong  itemprop="name"><?php the_sub_field('question'); ?></strong>
                    <i class="fas fa-chevron-down"></i>
                </summary>
                <div class="fa__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div itemprop="text"><?php the_sub_field('answer'); ?></div>
                </div>
            </details>
        <?php endwhile; ?>
</div>

        <?php woocommerce_template_single_add_to_cart(); ?>

    </div>

    <div class="gfx gfx--grid gfx--grid-d"></div>
    <div class="gfx gfx--arrow-d" style="position: absolute;left: 20px;bottom: 40%;"></div>
</section>
<?php $i++; endif; ?>