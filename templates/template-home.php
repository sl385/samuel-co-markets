<?php
/**
 * Template Name: Homepage
 *
 * @package WordPress
 * @subpackage Sam & Co
 */
?>
<?php get_header(); ?>

<?php $_content = get_field('header'); ?>
<section class="masthead masthead--hero section section--dark">
        <div class="container">
            <article>
                <h1><?php echo $_content['header_title']; ?></h1>
                <?php echo wpautop( $_content['header_content'] ); ?>
                <div class="button-group">
                <?php if( $_content['header_button_url'] ) : ?>
                    <a href="<?php echo $_content['header_button_url']; ?>" class="button "><?php echo $_content['header_button_label']; ?></a>
                <?php endif; ?>
                <?php if( $_content['header_button_2_url'] ) : ?>
                    <a href="<?php echo $_content['header_button_2_url']; ?>" class="button button--white"><?php echo $_content['header_button_label_2']; ?></a>
                <?php endif; ?>
                </div>
                
                <?php if( $_content['header_logos']) : ?>
                <div class="masthead__logos">
                    <p>Samuel & Co. in the News:</p>
                    <ul>
                    <?php foreach( $_content['header_logos'] as $logo ) : ?>
                    <li><a target="_blank" href="<?php echo $logo['link']; ?>"><img src="<?php echo $logo['logo']; ?>" loading="lazy" alt="" /></a></li>
                    <?php endforeach; ?>
                    </ul>
                    </div>
                <?php endif; ?>
                
            </ul>

            </article>
            <figure>
                <img src="<?php echo $_content['header_image']; ?>" loading="lazy" alt="<?php echo $_content['header_title']; ?>" />
            </figure>
        </div>
        <div class="container">
            <div class="section section--nobase">
                <div class="u-tac capped masthead__secondary">
                    <?php echo wpautop($_content['header_secondary_content']); ?>

                    <?php if( $_content['secondary_content_buttons'] ) : ?>
                        <div class="button-group button-group--central">
                        <?php foreach( $_content['secondary_content_buttons'] as $button ) : ?>
                            <a href="<?php echo $button['button_url']; ?>" class="button "><?php echo $button['button_label']; ?></a>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                 
                       
                   
                </div>
            </div>
        </div>

    

        <div class="gfx gfx--grid"></div>
        <div class="gfx gfx--grid  gfx--grid-alt"></div>
</section>

<?php $_content = get_field('services'); ?>
<section class="section">
        <div class="container">
            <header class="section__title">
                <h2><?php echo $_content['services_header']; ?></h2>
            </header>

            <ul class="grid grid--4 grid--products cards" >
                <?php foreach( $_content['service_call_to_actions'] as $cta ) : ?>
                <li>
                    <a href="<?php echo $cta['call_to_action_url']; ?>" class="card card--product">
                        <div>
                            <figure>
                                <img src="<?php echo $cta['call_to_action_image']; ?>" alt="" />
                            </figure>
                            <header class="card__header">
                                <h3><?php echo $cta['call_to_action_title']; ?></h3>
                            </header>
                            <?php echo wpautop( $cta['call_to_action_content']  ); ?>
                        </div>
                        <?php if( $cta['call_to_action_url'] ) : ?>
                            <span href="<?php echo $cta['call_to_action_url']; ?>" class="button">Learn More</span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>

            </ul>



        </div>
    </section>

    <?php $_content = get_field('team'); ?>

    <section class="section section--light">
        <div class="container container--s">
            <header class="section__title section__title--wide">
                <h2><?php echo $_content['section_title']; ?></h2>
                <?php echo wpautop( $_content['section_sub_title'] ); ?>
            </header>

            <ul class="grid grid--3 grid--team slick-m">


                <?php foreach( $_content['featured_team_members'] as $_person ) : $person = $_person['team_member'];  ?>

                    <li class="team__member">
                    <figure>
                        <img src="<?php echo get_the_post_thumbnail_url($person->ID); ?>" alt="<?php echo $person->post_title; ?>" />
                        <figcaption>
                        <?php echo wpautop($person->post_content); ?>
                        </figcaption>
                    </figure>
                    <a href="#" class="team__member--exit"><i class="fa-solid fa-times"></i></a>
                    <div>
                        <div>
                            <h3><?php echo $person->post_title; ?></h3>
                            <ul>
                                <?php if (get_field('email_address', $person->ID) ) : ?>
                                    <li><a href="mailto:<?php echo get_field('email_address', $person->ID); ?>"><i class="fa-solid fa-envelope"></i></a></li>
                                <?php endif; ?>
                                
                                <?php if (get_field('linkedin_url', $person->ID) ) : ?>
                                    <li><a target="_blank" href="<?php echo get_field('linkedin_url', $person->ID); ?>"><i class="fa-brands fa-linkedin"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <h4><?php echo get_field('role', $person->ID); ?></h4>
                       
                    </div>
                </li>

                <?php endforeach; ?>

              
            </ul>
            <div class="section__action">
                <a class="button button-hover--alt" href="<?php echo get_permalink(27); ?>#team">Find out more about us and meet the whole team</a>
            </div>
        </div>
    </section>

    <?php $stories = $args = array(
        'post_type' => 'success_story',
        'meta_key' => 'display_on_home_page',
        'meta_query' => array(
            array(
                'key' => 'display_on_home_page',
                'value' => 1,
                'compare' => '=',
            )
        )
        );
    $stories = new WP_Query($args); ?>

    <section class="section section--dark section--success">
        <div class="container container--s">
            <header>
                <h2 class="stories--header">Success Stories</h2>
            </header>
            <div class="slick-wrapper">                  
            <div class="stories-slider">
                <?php foreach( $stories->posts as $story ) : ?>

                    <div class="stories__slide">
                        <div>
                            <header>
                                <h3><?php echo $story->post_title; ?></h3>
                            </header>
                            <p><?php echo get_field('course_taken', $story->ID); ?></p>
                           <?php echo wpautop( $story->post_content ); ?>
                        </div>
                        <figure>
                            <img lazy="true" src="<?php echo get_the_post_thumbnail_url($story->ID); ?>" alt="<?php echo $story->post_title; ?>" />
                        </figure>
                    </div>

                <?php endforeach; ?>
                
                </div>
            </div>


            <?php $reviews = SCO_Helpers::getBusinessReviews(); ?>
            <?php if( $reviews ) : ?>
            <div class="slick-wrapper">
                <div class="testimonial--slider">
                    <?php foreach( $reviews  as $review ) : ?>
                    <a class="testimonial" href="<?php echo $review['author_url']; ?>" target="_blank">
                        <div>
                            <figure>
                                <img loading="lazy" src="<?php echo $review['profile_photo_url'] ?>" alt="" />
                            </figure>
                            <h3><?php echo $review['author_name']; ?>  <span><?php echo date('d/m/y', $review['time']); ?></span></h3>
                            
                        </div>
                        <div class="stars">
                            <?php for($i=1;$i<=$review['rating'];$i++) : ?>
                            <i class="fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <?php echo wpautop( wp_trim_words($review['text'], 15, '...')); ?>
                    </a>
                    <?php endforeach; ?>
                    
                </div>
            </div>    
            <?php endif; ?>            
        </div>
    </section>


    <?php $latest = get_posts('posts_per_page=3'); ?>
    <section class="section section--newsthumbs">
        <div class="container container--s">
            <header class="section__title">
                <h2>News & Research</h2>
            </header>

            <ul class="grid grid--3 grid--news slick-m">
                <?php foreach( $latest as $post ) : setup_postdata( $post ) ?>
                    <li><?php get_template_part('partials/loop', 'post'); ?></li>
                <?php endforeach; wp_reset_query( ); ?>
            </ul>

            <div class="gfx gfx--grid gfx--grid-b"></div>
            <div class="gfx gfx--arrow-d"></div>
            <div class="gfx gfx--grid gfx--grid-d"></div>
        </div>
    </section>




<?php get_footer(); ?>