<?php
get_header();
?>
<div class="container page-shell neocard" style="margin-top:1.2rem;">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div><?php the_excerpt(); ?></div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Контент пока не добавлен.</p>
    <?php endif; ?>
</div>
<?php
get_footer();
