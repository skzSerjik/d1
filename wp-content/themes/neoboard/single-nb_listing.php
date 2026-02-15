<?php
get_header();

while (have_posts()) :
    the_post();
?>
    <div class="container single-shell neocard">
        <article class="single-content">
            <span class="badge"><?php echo esc_html(get_the_date()); ?></span>
            <h1><?php the_title(); ?></h1>
            <?php if (has_post_thumbnail()) : ?>
                <div style="margin-bottom:.9rem;">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            <div><?php the_content(); ?></div>
        </article>

        <aside class="info-card neocard">
            <h3>Контакты продавца</h3>
            <dl>
                <div>
                    <dt>Цена</dt>
                    <dd><?php echo esc_html(neoboard_get_listing_price(get_the_ID())); ?></dd>
                </div>
                <div>
                    <dt>Город</dt>
                    <dd><?php echo esc_html(neoboard_get_listing_city(get_the_ID())); ?></dd>
                </div>
                <div>
                    <dt>Телефон</dt>
                    <dd><a href="tel:<?php echo esc_attr(neoboard_get_listing_phone(get_the_ID())); ?>"><?php echo esc_html(neoboard_get_listing_phone(get_the_ID())); ?></a></dd>
                </div>
                <div>
                    <dt>Категория</dt>
                    <dd><?php echo wp_kses_post(get_the_term_list(get_the_ID(), 'nb_listing_category', '', ', ')); ?></dd>
                </div>
            </dl>
        </aside>
    </div>
<?php
endwhile;

get_footer();
