<?php
/**
 * NeoBoard theme setup.
 */

if (!defined('ABSPATH')) {
    exit;
}

function neoboard_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption', 'style', 'script']);

    register_nav_menus([
        'primary' => __('Основное меню', 'neoboard'),
        'footer'  => __('Меню в подвале', 'neoboard'),
    ]);
}
add_action('after_setup_theme', 'neoboard_setup');

function neoboard_enqueue_assets(): void
{
    wp_enqueue_style('neoboard-style', get_stylesheet_uri(), [], '1.0.0');
    wp_enqueue_script('neoboard-script', get_template_directory_uri() . '/assets/js/theme.js', [], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'neoboard_enqueue_assets');

function neoboard_register_listing_post_type(): void
{
    register_post_type('nb_listing', [
        'labels' => [
            'name'               => __('Объявления', 'neoboard'),
            'singular_name'      => __('Объявление', 'neoboard'),
            'add_new'            => __('Добавить объявление', 'neoboard'),
            'add_new_item'       => __('Добавить новое объявление', 'neoboard'),
            'edit_item'          => __('Редактировать объявление', 'neoboard'),
            'new_item'           => __('Новое объявление', 'neoboard'),
            'view_item'          => __('Просмотр объявления', 'neoboard'),
            'search_items'       => __('Поиск объявлений', 'neoboard'),
            'not_found'          => __('Объявления не найдены', 'neoboard'),
            'not_found_in_trash' => __('В корзине нет объявлений', 'neoboard'),
            'menu_name'          => __('Доска объявлений', 'neoboard'),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-megaphone',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'listings'],
    ]);

    register_taxonomy('nb_listing_category', 'nb_listing', [
        'labels' => [
            'name'          => __('Категории объявлений', 'neoboard'),
            'singular_name' => __('Категория объявления', 'neoboard'),
        ],
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'hierarchical'      => true,
        'rewrite'           => ['slug' => 'listing-category'],
    ]);
}
add_action('init', 'neoboard_register_listing_post_type');

function neoboard_register_listing_meta_boxes(): void
{
    add_meta_box(
        'neoboard_listing_details',
        __('Детали объявления', 'neoboard'),
        'neoboard_listing_meta_box_callback',
        'nb_listing',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'neoboard_register_listing_meta_boxes');

function neoboard_listing_meta_box_callback(WP_Post $post): void
{
    wp_nonce_field('neoboard_save_listing_meta', 'neoboard_listing_meta_nonce');

    $price = get_post_meta($post->ID, '_nb_price', true);
    $city = get_post_meta($post->ID, '_nb_city', true);
    $phone = get_post_meta($post->ID, '_nb_phone', true);

    echo '<p><label for="nb_price"><strong>' . esc_html__('Цена', 'neoboard') . '</strong></label><br />';
    echo '<input type="text" id="nb_price" name="nb_price" class="widefat" value="' . esc_attr((string)$price) . '" placeholder="например, 40 000 ₽" /></p>';

    echo '<p><label for="nb_city"><strong>' . esc_html__('Город', 'neoboard') . '</strong></label><br />';
    echo '<input type="text" id="nb_city" name="nb_city" class="widefat" value="' . esc_attr((string)$city) . '" placeholder="например, Москва" /></p>';

    echo '<p><label for="nb_phone"><strong>' . esc_html__('Телефон', 'neoboard') . '</strong></label><br />';
    echo '<input type="text" id="nb_phone" name="nb_phone" class="widefat" value="' . esc_attr((string)$phone) . '" placeholder="+7 (___) ___-__-__" /></p>';
}

function neoboard_save_listing_meta(int $post_id): void
{
    if (!isset($_POST['neoboard_listing_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['neoboard_listing_meta_nonce'])), 'neoboard_save_listing_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $map = [
        '_nb_price' => 'nb_price',
        '_nb_city'  => 'nb_city',
        '_nb_phone' => 'nb_phone',
    ];

    foreach ($map as $meta_key => $field_name) {
        if (!isset($_POST[$field_name])) {
            continue;
        }

        update_post_meta($post_id, $meta_key, sanitize_text_field(wp_unslash($_POST[$field_name])));
    }
}
add_action('save_post_nb_listing', 'neoboard_save_listing_meta');

function neoboard_listing_submit_message(): string
{
    if (!isset($_GET['submitted'])) {
        return '';
    }

    $flag = sanitize_text_field(wp_unslash($_GET['submitted']));

    if ($flag === 'ok') {
        return '<div class="notice notice-success">' . esc_html__('Спасибо! Ваше объявление отправлено на модерацию.', 'neoboard') . '</div>';
    }

    if ($flag === 'fail') {
        return '<div class="notice notice-error">' . esc_html__('Не удалось отправить объявление. Проверьте поля формы.', 'neoboard') . '</div>';
    }

    return '';
}

function neoboard_handle_front_submission(): void
{
    if (!isset($_POST['neoboard_submit_listing'])) {
        return;
    }

    if (!isset($_POST['neoboard_submit_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['neoboard_submit_nonce'])), 'neoboard_front_submit')) {
        wp_safe_redirect(add_query_arg('submitted', 'fail', wp_get_referer() ?: home_url('/')));
        exit;
    }

    $title = isset($_POST['listing_title']) ? sanitize_text_field(wp_unslash($_POST['listing_title'])) : '';
    $content = isset($_POST['listing_description']) ? wp_kses_post(wp_unslash($_POST['listing_description'])) : '';
    $price = isset($_POST['listing_price']) ? sanitize_text_field(wp_unslash($_POST['listing_price'])) : '';
    $city = isset($_POST['listing_city']) ? sanitize_text_field(wp_unslash($_POST['listing_city'])) : '';
    $phone = isset($_POST['listing_phone']) ? sanitize_text_field(wp_unslash($_POST['listing_phone'])) : '';
    $cat = isset($_POST['listing_category']) ? absint($_POST['listing_category']) : 0;

    if ($title === '' || $content === '' || $phone === '') {
        wp_safe_redirect(add_query_arg('submitted', 'fail', wp_get_referer() ?: home_url('/')));
        exit;
    }

    $post_id = wp_insert_post([
        'post_type'   => 'nb_listing',
        'post_title'  => $title,
        'post_content'=> $content,
        'post_status' => 'pending',
    ], true);

    if (is_wp_error($post_id)) {
        wp_safe_redirect(add_query_arg('submitted', 'fail', wp_get_referer() ?: home_url('/')));
        exit;
    }

    update_post_meta($post_id, '_nb_price', $price);
    update_post_meta($post_id, '_nb_city', $city);
    update_post_meta($post_id, '_nb_phone', $phone);

    if ($cat > 0) {
        wp_set_post_terms($post_id, [$cat], 'nb_listing_category');
    }

    wp_safe_redirect(add_query_arg('submitted', 'ok', wp_get_referer() ?: home_url('/')));
    exit;
}
add_action('template_redirect', 'neoboard_handle_front_submission');

function neoboard_get_listing_price(int $post_id): string
{
    $price = get_post_meta($post_id, '_nb_price', true);
    return $price ? (string) $price : __('Цена не указана', 'neoboard');
}

function neoboard_get_listing_city(int $post_id): string
{
    $city = get_post_meta($post_id, '_nb_city', true);
    return $city ? (string) $city : __('Не указан', 'neoboard');
}

function neoboard_get_listing_phone(int $post_id): string
{
    $phone = get_post_meta($post_id, '_nb_phone', true);
    return $phone ? (string) $phone : __('Не указан', 'neoboard');
}
