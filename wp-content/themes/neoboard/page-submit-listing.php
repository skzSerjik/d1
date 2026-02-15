<?php
/**
 * Template Name: Подать объявление
 */
get_header();

$terms = get_terms([
    'taxonomy' => 'nb_listing_category',
    'hide_empty' => false,
]);
?>
<div class="container" style="margin-top:1.3rem;">
    <section class="form-wrap neocard">
        <h1><?php the_title(); ?></h1>
        <p>Заполните форму. После отправки объявление попадет на модерацию и станет доступно в каталоге после подтверждения.</p>

        <?php echo wp_kses_post(neoboard_listing_submit_message()); ?>

        <form class="submit-form" method="post" action="">
            <?php wp_nonce_field('neoboard_front_submit', 'neoboard_submit_nonce'); ?>
            <label>
                <span>Заголовок объявления *</span>
                <input class="neoinput" type="text" name="listing_title" required placeholder="Например, Сдам студию рядом с метро" />
            </label>

            <div class="split">
                <label>
                    <span>Цена</span>
                    <input class="neoinput" type="text" name="listing_price" placeholder="35 000 ₽" />
                </label>
                <label>
                    <span>Город</span>
                    <input class="neoinput" type="text" name="listing_city" placeholder="Казань" />
                </label>
            </div>

            <div class="split">
                <label>
                    <span>Телефон *</span>
                    <input class="neoinput" type="text" name="listing_phone" required placeholder="+7 (900) 000-00-00" />
                </label>
                <label>
                    <span>Категория</span>
                    <select class="neoinput" name="listing_category">
                        <option value="0">Без категории</option>
                        <?php foreach ($terms as $term) : ?>
                            <option value="<?php echo esc_attr((string)$term->term_id); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>

            <label>
                <span>Описание *</span>
                <textarea class="neoinput" name="listing_description" required placeholder="Опишите товар или услугу: состояние, условия, преимущества..."></textarea>
            </label>

            <button class="neobtn" name="neoboard_submit_listing" type="submit">Отправить объявление</button>
        </form>
    </section>
</div>
<?php get_footer(); ?>
