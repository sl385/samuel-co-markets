<a href="<?php the_permalink(); ?>" class="article-card">
    <div>
        <figure>
            <?php the_post_thumbnail(); ?>
        </figure>
        <span class="article__tag"><?php $category = get_the_category(); echo $category[0]->cat_name;?> </span>
        <h3><?php the_title(); ?></h3>
    </div>
    <span class="button">Read More</span>
</a>
