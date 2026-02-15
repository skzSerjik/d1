<?php
get_header();

$listing_count = wp_count_posts('nb_listing');
$published = $listing_count ? (int) $listing_count->publish : 0;
$categories = wp_count_terms([
    'taxonomy' => 'nb_listing_category',
    'hide_empty' => false,
]);

$latest_listings = new WP_Query([
    'post_type'      => 'nb_listing',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
]);
?>
<section class="container hero neocard">
    <div>
        <h1>Умная доска объявлений для города и региона</h1>
        <p>Публикуйте предложения, находите клиентов и откликайтесь на актуальные объявления в удобном интерфейсе. Стиль неоморфизм, адаптивная верстка и продуманная структура страниц.</p>
        <form class="quick-search" action="<?php echo esc_url(get_post_type_archive_link('nb_listing') ?: home_url('/listings')); ?>" method="get">
            <input class="neoinput" type="text" name="s" placeholder="Что вы ищете?" />
            <input class="neoinput" type="text" name="city" placeholder="Город" />
            <button class="neobtn" type="submit">Найти</button>
        </form>
    </div>
    <div class="stats-grid">
        <article class="neocard stat-item">
            <strong><?php echo esc_html(number_format_i18n($published)); ?></strong>
            <span>активных объявлений</span>
        </article>
        <article class="neocard stat-item">
            <strong><?php echo esc_html(number_format_i18n((int)$categories)); ?></strong>
            <span>категорий</span>
        </article>
        <article class="neocard stat-item">
            <strong>24/7</strong>
            <span>доступ к сайту</span>
        </article>
        <article class="neocard stat-item">
            <strong>100%</strong>
            <span>адаптивный интерфейс</span>
        </article>
    </div>
</section>

<section class="container">
    <div class="section-title">
        <h2>Свежие объявления</h2>
        <a href="<?php echo esc_url(get_post_type_archive_link('nb_listing') ?: home_url('/listings')); ?>">Смотреть все</a>
    </div>

    <?php if ($latest_listings->have_posts()) : ?>
        <div class="cards-grid">
            <?php
            while ($latest_listings->have_posts()) :
                $latest_listings->the_post();
                get_template_part('template-parts/listing', 'card');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    <?php else : ?>
        <div class="neocard page-shell">
            <p>Пока нет объявлений. Станьте первым и разместите предложение.</p>
        </div>
    <?php endif; ?>
</section>

<?php
get_footer();
