<?php
/**
 * Template Name: About
 *
 * @package WordPress
 * @subpackage Sam & Co
 */

$markers = [
    "ni",
    "de",
    "be",
    "uk",
    "sw",
    "su",
    "tu",
    "us",
    "ca",
    "me",
    "ar",
    "co",
    "br",
    "ja",
    "ch",
    "ua",
    "au",
    "nz"
]


?>
<?php get_header(); ?>
<?php $_content = get_field('header'); ; ?>
<section class="masthead section section--dark">
        <div class="container">
            <article>
                <h1><?php echo $_content['header_title']; ?></h1>
                <?php echo wpautop( $_content['header_content'] ); ?>
                <?php if( $_content['header_button_url'] ) : ?>
                    <a href="<?php echo $_content['header_button_url']; ?>" class="button"><?php echo $_content['header_button_label']; ?></a>
                <?php endif; ?>
            </article>
            <figure>
                <img src="<?php echo $_content['header_image']; ?>" loading="lazy" alt="<?php echo $_content['header_title']; ?>" />
            </figure>
        </div>
</section>

<?php $_content = get_field('content_strip_3'); ?>
<section class="section">
    <div class="container  container--s container--layout ">
    <figure>
        <img src="<?php echo $_content['section_image']; ?>" alt="<?php echo $_content['main_title']; ?>" />
    </figure>
        <article class="content content--branded">
            <h2><?php echo $_content['main_title']; ?></h2>
            <?php echo wpautop( $_content['content'] ); ?>
            <?php if( $_content['button_url'] ) : ?>
                <a href="<?php echo $_content['button_url']; ?>" class="button"><?php echo $_content['button_label']; ?></a>
            <?php endif; ?>
        </article>
    </div>
</section>

<section class="section section--dark">
    <div class="container container--s">
        <header class="section__title">
            <h2><?php echo get_field('section_title_principles'); ?></h2>
            <?php echo wpautop( get_field('section_content_principles') ); ?>
        </header>    
        <div class="processes">
            <div class="process--track">
            <?php $i=1; while( have_rows('process_boxes') ) : the_row(); ?>
                <div class="process-box">
                    <h3><?php the_sub_field('title'); ?></h3>
                    <?php echo wpautop( get_sub_field('content') ); ?>
                    <?php if( $i == 1 ) : ?>
                        <span class="gfx gfx--grid gfx--grid-b"></span>
                        <span class="gfx gfx--arrow-d"></span>
                    <?php endif; ?>
                    <?php if( $i == 2 ) : ?>
                        <span class="gfx gfx--grid gfx--grid-b"></span>
                        <span class="gfx gfx--circle gfx--circle--half"></span>
                    <?php endif; ?>
                    <?php if( $i == 3 ) : ?>
                        <span class="gfx gfx--arrow-d"></span>
                        <span class="gfx gfx--circle--o"></span>
                    <?php endif; ?>
                </div>
            <?php $i++; endwhile; ?>
            </div>
        </div>
    </div>
</section>



<section class="section">
    <div class="container">
        <header class="section__title section__title--wide">
            <h2><?php echo get_field('section_title'); ?></h2>
            <?php echo wpautop( get_field('section_content') ); ?>
        </header>    

        <div class="map-canvas">
            <div class="map-canvas__ui">
                <img class="map-m" loading="lazy" src="<?php echo _i('map.png'); ?>" alt="" />
                <?php /* foreach( $markers as $marker) : ?>
                <i data-key="<?php echo $marker; ?>" class="fa-solid fa-location-pin"></i>
                <?php endforeach; */ ?>
                <img class="map-d" loading="lazy" src="<?php echo _i('map-world.png'); ?>" alt="" />
               
            </div>
            <aside class="map-canvas__key">
                <ul class="map-key">
                <?php foreach( $markers as $marker) : ?>
                    <li class="key key--<?php echo $marker; ?>" data-keyfor="<?php echo $marker; ?>"><span></span> 999</li>
                <?php endforeach; ?>
                </ul>
            </aside>
        </div>

  
    </div>
</section>


<section class="section section--dark">
    <div class="container container--sm">
        <header class="section__title">
            <h2>Our Official Social Media Channels</h2>
        </header>    
        <div class="social-boxes">
           
            <div class="social-box">
                <?php if( get_field('insta_url')) : ?>
                    <a target="_blank" href="<?php the_field('insta_url'); ?>"><i class="fab fa-instagram"></i></a>
                <?php else: ?>
                    <i class="fab fa-instagram"></i>
                <?php endif; ?>
                <h3 data-format="0" data-count="<?php the_field('insta_subscribers'); ?>"><?php the_field('insta_subscribers'); ?></h3>
                <span>Instagram Followers</span>
            </div>

            <div class="social-box">
                <?php if( get_field('facebook_url')) : ?>
                    <a target="_blank" href="<?php the_field('facebook_url'); ?>"><i class="fab fa-facebook"></i></a>
                <?php else: ?>
                    <i class="fab fa-facebook"></i>
                <?php endif; ?>
               
                <h3 data-format="10" data-count="<?php the_field('facebook_followers'); ?>"><?php the_field('facebook_followers'); ?></h3>
                <span>Facebook Followers</span>
            </div>

            <div class="social-box">
                <?php if( get_field('youtube_url')) : ?>
                    <a target="_blank" href="<?php the_field('youtube_url'); ?>"><i class="fab fa-youtube"></i></a>
                <?php else: ?>
                    <i class="fab fa-youtube"></i>
                <?php endif; ?>

               
                <h3 data-format="0" data-count="<?php the_field('youtube_subscribers'); ?>"><?php the_field('youtube_subscribers'); ?></h3>
                <span>YouTube Subscribers</span>
            </div>

            <div class="social-box">
                <?php if( get_field('twitter_url')) : ?>
                    <a target="_blank" href="<?php the_field('twitter_url'); ?>"><i class="fab fa-twitter"></i></a>
                <?php else: ?>
                    <i class="fab fa-twitter"></i>
                <?php endif; ?>
               
                <h3 data-format="10" data-count="<?php the_field('twitter_subscribers'); ?>"><?php the_field('twitter_subscribers'); ?></h3>
                <span>Twitter Followers</span>
            </div>

            <div class="social-box">
                 <?php if( get_field('linked_in_url')) : ?>
                    <a target="_blank" href="<?php the_field('linked_in_url'); ?>"><i class="fab fa-linkedin"></i></a>
                <?php else: ?>
                    <i class="fab fa-linkedin"></i>
                <?php endif; ?>
              
                <h3 data-format="10" data-count="<?php the_field('linked_in_followers'); ?>"><?php the_field('linked_in_followers'); ?></h3>
                <span>Linkedin Followers</span>
            </div>

            <div class="social-box">
            <?php if( get_field('telegram_url')) : ?>
                    <a target="_blank" href="<?php the_field('telegram_url'); ?>"><i class="fab fa-telegram"></i></a>
                <?php else: ?>
                    <i class="fab fa-telegram"></i>
                <?php endif; ?>

                <h3 data-format="10" data-count="<?php the_field('telegram_subscribers'); ?>"><?php the_field('telegram_subscribers'); ?></h3>
                <span>Telegram Subscribers</span>
            </div>
        
        </div>
    </div>
    <div id="team"></div>
</section>

<?php $people = get_posts('post_type=team&posts_per_page=-1'); ?>

<section class="section" >
    <div class="container container--s">

        <?php foreach( $people as $person ) : ?>

            <?php if( get_field('is_a_featured_team_member', $person->ID) ) : ?>
                <div class="person person--featured">
                    <figure>
                        <img src="<?php echo get_field('featured_content_image', $person->ID); ?>" alt="" />
                    </figure>
                    <header>
                        <h3>Meet <?php echo $person->post_title; ?></h3>
                        <ul>
                        
                            <?php if (get_field('email_address', $person->ID) ) : ?>
                                <li><a href="mailto:<?php echo get_field('email_address', $person->ID); ?>"><i class="fa-solid fa-envelope"></i></a></li>
                            <?php endif; ?>
                            
                            <?php if (get_field('linkedin_url', $person->ID) ) : ?>
                                <li><a target="_blank" href="<?php echo get_field('linkedin_url', $person->ID); ?>"><i class="fa-brands fa-linkedin"></i></a></li>
                            <?php endif; ?>

                        </ul>
                    </header>
                    <article>
                        <div><?php echo wpautop( get_field('featured_content', $person->ID )); ?></div>
                        <?php if( get_field('featured_content_youtube_video', $person->ID) ) : ?>
                            <div class="team-video">
                                <div class="embed-container">
                                    <iframe width="560" height="315" src="<?php echo get_field('featured_content_youtube_video', $person->ID); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                             </div>
                        <?php endif; ?>
                    </article>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>


        <header class="section__title">
            <h2>Meet The Team</h2>
        </header>    
        <ul class="grid grid--3 grid--team">
        <?php foreach( $people as $person ) : ?>
            
            <?php if( !get_field('is_a_featured_team_member', $person->ID) ) : ?>
               
                <li class="team__member">
                    <figure>
                        <img src="<?php echo get_the_post_thumbnail_url($person->ID); ?>" alt="<?php echo $person->post_title; ?>" />
                        <figcaption>
                        <?php echo wpautop($person->post_content); ?>
                        </figcaption>
                    </figure>
                   
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
            <?php endif ; ?>
           
        <?php endforeach; ?>
        </ul>
    </div>
</section>




<?php get_footer(); ?>