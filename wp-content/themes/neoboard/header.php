<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="container header-wrap">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">NeoBoard</a>

        <button class="neobtn mobile-toggle" type="button" data-nav-toggle>
            <?php esc_html_e('Меню', 'neoboard'); ?>
        </button>

        <nav class="main-nav" data-main-nav>
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => function (): void {
                    echo '<ul>';
                    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Главная', 'neoboard') . '</a></li>';
                    echo '<li><a href="' . esc_url(get_post_type_archive_link('nb_listing') ?: home_url('/listings')) . '">' . esc_html__('Объявления', 'neoboard') . '</a></li>';
                    echo '<li><a href="' . esc_url(home_url('/submit-listing')) . '">' . esc_html__('Подать объявление', 'neoboard') . '</a></li>';
                    echo '</ul>';
                },
            ]);
            ?>
        </nav>

        <div class="header-actions">
            <a class="neobtn" href="<?php echo esc_url(home_url('/submit-listing')); ?>"><?php esc_html_e('Разместить', 'neoboard'); ?></a>
        </div>
    </div>
</header>
<main>
