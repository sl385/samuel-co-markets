
<a href="<?php echo get_field('event_url'); ?>" target="_blank" class="article-card event-card">
    <div>
        <figure>
            <?php the_post_thumbnail(); ?>
            <figcaption>
                <span class="event_date">
                    <span><?php echo date('M', strtotime(get_field('event_date'))); ?></span>
                    <span><?php echo date('d', strtotime(get_field('event_date'))); ?></span>
                </span>
            </figcaption>
        </figure>
        <span class="article__tag"><?php echo date('l jS F Y', strtotime(get_field('event_date'))); ?></span>
        <h3><?php the_title(); ?></h3>
        <p class="event_cost">Cost: <?php echo get_field('event_cost'); ?></p>
    </div>
    <span class="button">Read More</span>
</a>
