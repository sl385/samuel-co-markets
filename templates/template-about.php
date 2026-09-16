<?php
/**
 * Template Name: About
 *
 * @package WordPress
 * @subpackage Sam & Co
 */

?>
<?php get_header(); ?>
<?php
$_content = get_field('header');
scm_component('masthead', [
  'title' => $_content['header_title'] ?? '',
  'content' => wpautop($_content['header_content'] ?? ''),
  'primary' => !empty($_content['header_button_url']) ? [$_content['header_button_label'], $_content['header_button_url']] : null,
  'image' => $_content['header_image'] ?? '',
]);
?>

<?php $_content = get_field('content_strip_3'); ?>
<section class="scm-section">
  <?php scm_component('content-strip', [
    'title' => $_content['main_title'] ?? '',
    'content' => wpautop($_content['content'] ?? ''),
    'image' => $_content['section_image'] ?? 0,
    'seed' => get_the_ID(),
    'button' => !empty($_content['button_url']) ? [$_content['button_label'], $_content['button_url']] : null,
  ]); ?>
</section>

<section class="scm-section scm-section--dark scm-on-dark">
  <div class="scm-shell">
    <header class="scm-principles-head">
        <h2><?php echo esc_html(get_field('section_title_principles')); ?></h2>
        <?php echo wpautop( get_field('section_content_principles') ); ?>
    </header>
    <div class="scm-process-grid">
    <?php $i = 1; while( have_rows('process_boxes') ) : the_row(); ?>
        <div class="scm-process-step">
            <span class="scm-process-step__marker" data-index="0<?php echo $i; ?>"></span>
            <h3><?php the_sub_field('title'); ?></h3>
            <?php echo wpautop( get_sub_field('content') ); ?>
        </div>
    <?php $i++; endwhile; ?>
    </div>
  </div>
</section>



<?php
// class="section" here (and section--dark below) resolves to nothing —
// its defining partial (assets/sass/legacy/local/_scaffold.scss) was
// never @import-ed into screen.scss, so it has zero padding and rendered
// flush against the previous section. Added the real scm-section classes
// alongside rather than replacing — .container (a different, actually-
// imported rule) already provides real width/centering, untouched.
?>
<section class="section scm-section">
    <div class="container">
        <header class="section__title section__title--wide">
            <h2><?php echo get_field('section_title'); ?></h2>
            <?php echo wpautop( get_field('section_content') ); ?>
        </header>    

        <?php
        // The old markup here was fake, not just unstyled — every country's
        // count was the literal string "999" (flagged in
        // docs/LEGACY-CODE-REVIEW.md as a placeholder shipped live), not real
        // per-country ACF data. Marking it rather than either faking numbers
        // or reviving a hardcoded "999" that was never real.
        scm_component('no-component', [
          'label' => 'World map — countries reached',
          'note' => 'Design shows a world map with a per-country visitor/student count. The old markup hardcoded every country to "999" — never real data — so there is nothing accurate to restyle here; needs a real data source before this is built.',
        ]);
        ?>

  
    </div>
</section>


<section class="section section--dark scm-section scm-section--dark scm-on-dark">
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

<?php foreach( $people as $person ) : if ( ! get_field('is_a_featured_team_member', $person->ID) ) continue; ?>
<section class="scm-section">
  <div class="scm-shell scm-team-feature">
    <img class="scm-team-feature__photo" src="<?php echo esc_url(scm_acf_image_url(get_field('featured_content_image', $person->ID) ?: 0, 'medium_large', $person->ID)); ?>" alt="<?php echo esc_attr($person->post_title); ?>">
    <div>
      <div class="scm-team-feature__head">
        <h2>Meet <?php echo esc_html($person->post_title); ?></h2>
        <div class="scm-team-feature__social">
          <?php if (get_field('email_address', $person->ID) ) : ?>
              <a href="mailto:<?php echo esc_attr(get_field('email_address', $person->ID)); ?>"><i class="fa-solid fa-envelope"></i></a>
          <?php endif; ?>
          <?php if (get_field('linkedin_url', $person->ID) ) : ?>
              <a target="_blank" rel="noopener" href="<?php echo esc_url(get_field('linkedin_url', $person->ID)); ?>"><i class="fa-brands fa-linkedin"></i></a>
          <?php endif; ?>
        </div>
      </div>
      <?php echo wpautop( get_field('featured_content', $person->ID )); ?>
      <?php if( get_field('featured_content_youtube_video', $person->ID) ) : ?>
          <div class="scm-team-feature__video">
              <iframe src="<?php echo esc_url(get_field('featured_content_youtube_video', $person->ID)); ?>" title="<?php echo esc_attr($person->post_title); ?> video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
          </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>

<section class="scm-section">
  <div class="scm-shell">
    <?php scm_component('section-head', ['title' => 'Meet The Team']); ?>
    <div class="scm-about-team-grid">
    <?php
    global $post;
    foreach( $people as $person ) :
        if ( get_field('is_a_featured_team_member', $person->ID) ) continue;
        $post = $person;
        setup_postdata( $post );
        scm_component('team-card');
    endforeach; wp_reset_postdata(); ?>
    </div>
  </div>
</section>




<?php get_footer(); ?>