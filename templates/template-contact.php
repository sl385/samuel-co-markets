<?php
/**
 * Template Name: Contact Us
 *
 * @package WordPress
 * @subpackage Sam & Co
 */
$location = get_field('location');
?>
<?php get_header(); ?>

<div class="masthead masthead--contact" >
    <div class="container container--s">
        <article>

            <h1><?php the_title(); ?></h1>

            <?php the_content(); ?>

            <ul class="footer__contacts">
                <li><i class="fas fa-phone"></i> <?php echo get_field('contact_phone_number','option'); ?></li>
                <li><i class="fas fa-envelope"></i><a href="mailto:<?php echo get_field('website_email','option'); ?>"><?php echo get_field('website_email','option'); ?></a></li>
                <li><i class="fa-solid fa-location-dot"></i><address><?php echo nl2br(get_field('business_address','option')); ?></address></li>
            </ul>
            

        </article>
      
        <aside id="map" data-lat="<?php echo esc_attr($location['lat']); ?>" data-lng="<?php echo esc_attr($location['lng']); ?>" data-marker="<?php echo _i('map-marker.png'); ?>"></aside>
       
    </div>
</div>

<div class="container container--s">
    <div class="contact__form">
        <?php echo do_shortcode( '[contact-form-7 id="7" title="Contact form 1"]' ); ?>
    </div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&callback=ginit"></script>

<?php get_footer(); ?>