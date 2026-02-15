<?php
get_header();

$paged = max(1, (int) get_query_var('paged'));
$search = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$city = isset($_GET['city']) ? sanitize_text_field(wp_unslash($_GET['city'])) : '';
$cat = isset($_GET['cat']) ? absint($_GET['cat']) : 0;

$args = [
    'post_type'      => 'nb_listing',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
    's'              => $search,
];

if ($city !== '') {
    $args['meta_query'] = [[
        'key'     => '_nb_city',
        'value'   => $city,
        'compare' => 'LIKE',
    ]];
}

if ($cat > 0) {
    $args['tax_query'] = [[
        'taxonomy' => 'nb_listing_category',
        'field'    => 'term_id',
        'terms'    => $cat,
    ]];
}

$query = new WP_Query($args);
$terms = get_terms(['taxonomy' => 'nb_listing_category', 'hide_empty' => false]);
?>
<div class="container layout">
    <aside class="sidebar neocard">
        <h3>Фильтр объявлений</h3>
        <form class="filters-form" method="get" action="<?php echo esc_url(get_post_type_archive_link('nb_listing') ?: home_url('/listings')); ?>">
            <label>
                <span>Ключевой запрос</span>
                <input class="neoinput" type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Например, iPhone" />
            </label>
            <label>
                <span>Город</span>
                <input class="neoinput" type="text" name="city" value="<?php echo esc_attr($city); ?>" placeholder="Санкт-Петербург" />
            </label>
            <label>
                <span>Категория</span>
                <select class="neoinput" name="cat">
                    <option value="0">Все категории</option>
                    <?php foreach ($terms as $term) : ?>
                        <option value="<?php echo esc_attr((string) $term->term_id); ?>" <?php selected($cat, (int) $term->term_id); ?>>
                            <?php echo esc_html($term->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button class="neobtn" type="submit">Применить фильтры</button>
        </form>
    </aside>

    <section class="content-panel neocard">
        <div class="section-title">
            <h1><?php esc_html_e('Каталог объявлений', 'neoboard'); ?></h1>
            <span>Найдено: <?php echo esc_html(number_format_i18n((int)$query->found_posts)); ?></span>
        </div>

        <?php if ($query->have_posts()) : ?>
            <div class="cards-grid">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php get_template_part('template-parts/listing', 'card'); ?>
                <?php endwhile; ?>
            </div>

            <?php
            echo '<div style="margin-top:1rem;">' . wp_kses_post(paginate_links([
                'total'   => (int) $query->max_num_pages,
                'current' => $paged,
            ])) . '</div>';
            ?>
        <?php else : ?>
            <p>По вашему запросу ничего не найдено. Попробуйте изменить фильтры.</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </section>
</div>
<?php get_footer(); ?>
