<?php if (!defined('ABSPATH')) { exit; } ?>
</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <section class="neocard" style="padding:1rem;">
            <h4>NeoBoard</h4>
            <p>Современная доска объявлений на WordPress: недвижимость, услуги, техника и вакансии.</p>
        </section>
        <section class="neocard" style="padding:1rem;">
            <h4>Навигация</h4>
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container' => false,
                'fallback_cb' => function (): void {
                    echo '<ul><li><a href="' . esc_url(home_url('/')) . '">Главная</a></li><li><a href="' . esc_url(get_post_type_archive_link('nb_listing') ?: home_url('/listings')) . '">Все объявления</a></li></ul>';
                },
            ]);
            ?>
        </section>
        <section class="neocard" style="padding:1rem;">
            <h4>Контакты</h4>
            <ul>
                <li>support@neoboard.local</li>
                <li>+7 (495) 000-00-00</li>
                <li>Пн–Вс: 09:00–21:00</li>
            </ul>
        </section>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
