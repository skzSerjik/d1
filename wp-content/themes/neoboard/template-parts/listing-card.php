<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<article <?php post_class('listing-card neocard'); ?>>
    <a href="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium_large'); ?>
        <?php else : ?>
            <img src="https://placehold.co/900x600/e8edf8/5b6694?text=NeoBoard" alt="placeholder" />
        <?php endif; ?>
    </a>

    <span class="badge"><?php echo esc_html(get_the_date()); ?></span>

    <h3 style="margin:.2rem 0;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

    <p class="price"><?php echo esc_html(neoboard_get_listing_price(get_the_ID())); ?></p>

    <div class="meta-row">
        <span>📍 <?php echo esc_html(neoboard_get_listing_city(get_the_ID())); ?></span>
        <span>📞 <?php echo esc_html(neoboard_get_listing_phone(get_the_ID())); ?></span>
    </div>

    <div><?php echo wp_kses_post(wp_trim_words(get_the_excerpt() ?: get_the_content(), 20)); ?></div>
</article>
