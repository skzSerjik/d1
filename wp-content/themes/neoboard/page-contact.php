<?php
/**
 * Template Name: Контакты
 */
get_header();
?>
<div class="container" style="margin-top:1.3rem;">
    <section class="page-shell neocard">
        <h1><?php the_title(); ?></h1>
        <p>Свяжитесь с нами по любым вопросам размещения объявлений, сотрудничества или технической поддержки.</p>
        <div class="cards-grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
            <article class="neocard" style="padding:1rem;">
                <h3>Поддержка</h3>
                <p>support@neoboard.local</p>
            </article>
            <article class="neocard" style="padding:1rem;">
                <h3>Телефон</h3>
                <p>+7 (495) 000-00-00</p>
            </article>
            <article class="neocard" style="padding:1rem;">
                <h3>Офис</h3>
                <p>Москва, ул. Цифровая, 10</p>
            </article>
        </div>
        <div style="margin-top:1rem;"><?php the_content(); ?></div>
    </section>
</div>
<?php get_footer(); ?>
