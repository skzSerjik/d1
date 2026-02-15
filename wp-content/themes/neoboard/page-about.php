<?php
/**
 * Template Name: О проекте
 */
get_header();
?>
<div class="container" style="margin-top:1.3rem;">
    <section class="page-shell neocard">
        <h1><?php the_title(); ?></h1>
        <p>NeoBoard создан как современная digital-площадка: аккуратный неоморфизм, удобная фильтрация, быстрая публикация объявлений и понятная структура для пользователей любого возраста.</p>
        <ul>
            <li>Гибкий каталог категорий для разных направлений бизнеса.</li>
            <li>Полная адаптация под смартфоны, планшеты и десктопы.</li>
            <li>Безопасная отправка объявлений с модерацией через WordPress.</li>
        </ul>
        <div><?php the_content(); ?></div>
    </section>
</div>
<?php get_footer(); ?>
