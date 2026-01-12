<?php
/**
 * Template Name: Шаблон Блогу
 */
?>
<?php
get_header();

$taxonomy = 'language';
$current_term = get_queried_object();

// термины для тикера
$terms = get_terms([
    'taxonomy'   => $taxonomy,
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

/**
 * Определяем: мы на терм-странице или на "лендинге" /language/
 * Важно: на /language/ $current_term может быть WP_Post (если это Page) или что-то другое.
 */
$is_term_page = ($current_term instanceof WP_Term && $current_term->taxonomy === $taxonomy);

// сюда соберём ID постов, чтобы не дублировать между блоками
$shown_post_ids = [];

/**
 * Блок "Популярно" (НЕ зависит от языка)
 */
$popular_q = new WP_Query([
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 8,
    'ignore_sticky_posts' => true,

    // чтобы не вмешивались фильтры (Polylang/WPML и т.п.)
    'suppress_filters'    => true,

    'tax_query'           => [[
        'taxonomy'         => 'category',
        'field'            => 'slug',
        'terms'            => ['populyarni'],
        'include_children' => false,
    ]],
]);

if ($popular_q->have_posts()) {
    foreach ($popular_q->posts as $p) {
        $shown_post_ids[] = (int) $p->ID;
    }
}
wp_reset_postdata();

/**
 * Основной вывод "Всі статті":
 * - если открыт term языка -> фильтр по термину
 * - если term НЕ открыт -> все посты всех языков
 *
 * Пагинация: используем $paged.
 */
$paged = max(1, (int) get_query_var('paged'));

$all_posts_args = [
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => (int) get_option('posts_per_page'),
    'paged'               => $paged,
    'ignore_sticky_posts' => true,

    // чтобы вывести реально "все языки" даже при языковых плагинах
    'suppress_filters'    => true,

    // чтобы не дублировать "Популярно" в общем списке
//    'post__not_in'        => !empty($shown_post_ids) ? $shown_post_ids : [],
];

if ($is_term_page) {
    $all_posts_args['tax_query'] = [[
        'taxonomy' => $taxonomy,
        'field'    => 'term_id',
        'terms'    => [(int) $current_term->term_id],
    ]];
}

$all_posts_q = new WP_Query($all_posts_args);
?>

<main class="taxonomy-language">
    <section class="ed-blog">
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title h1">
                    <?= esc_html(get_field('zagolovok_bloku', 1084)); ?>
                </h1>
                <div class="banner-subtitle h3">
                    <?= wp_kses_post(get_field('pdgagolovok_bloku', 1084)); ?>
                </div>
            </div>
            <div class="banner-world">
                <?php
                $banner_img = get_field('kartinka_dlya_bloku', 1084);
                if (!empty($banner_img)) : ?>
                    <img src="<?= esc_url($banner_img); ?>" alt="<?= esc_attr(get_field('zagolovok_bloku', 1084)); ?>">
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if (!is_wp_error($terms) && !empty($terms)) : ?>
        <section class="ed-home-languages taxonomy-switcher">
            <div class="container">
                <div class="taxonomy-language__title">
                    Розділи за мовами:
                </div>
            </div>

            <!-- 1-я линия -->
            <div class="languages-ticker">
                <div class="languages-ticker__wrapper">
                    <div class="languages-ticker__line">

                        <?php foreach ($terms as $term) :

                            $term_link = get_term_link($term);
                            if (is_wp_error($term_link)) continue;

                            $is_active = (
                                $current_term instanceof WP_Term &&
                                (int)$current_term->term_id === (int)$term->term_id
                            );

                            $image      = get_field('zobrazhennya_movi', 'term_' . $term->term_id);
                            $imagehover = get_field('zobrazhennya_movi_pri_navedenn', 'term_' . $term->term_id);

                            $image_url      = is_array($image) ? ($image['url'] ?? '') : (string)$image;
                            $imagehover_url = is_array($imagehover) ? ($imagehover['url'] ?? '') : (string)$imagehover;
                            ?>
                            <a href="<?php echo esc_url($term_link); ?>"
                               class="language <?php echo $is_active ? 'is-active' : ''; ?>">
                                <div class="language__image">
                                    <?php if (!empty($image_url)) : ?>
                                        <img
                                                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                                data-src="<?php echo esc_url($image_url); ?>"
                                                class="language__image-normal"
                                                alt="<?php echo esc_attr($term->name); ?>"
                                        >
                                    <?php endif; ?>

                                    <?php if (!empty($imagehover_url)) : ?>
                                        <img
                                                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                                data-src="<?php echo esc_url($imagehover_url); ?>"
                                                class="language__image-active"
                                                alt="<?php echo esc_attr($term->name); ?>"
                                        >
                                    <?php endif; ?>
                                </div>

                                <div class="technology__title">
                                    <?php echo esc_html($term->name); ?>
                                </div>
                            </a>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>

            <!-- 2-я линия (reverse) -->
            <div class="languages-ticker languages-ticker--reverse">
                <div class="languages-ticker__wrapper">
                    <div class="languages-ticker__line">

                        <?php foreach (array_reverse($terms) as $term) :

                            $term_link = get_term_link($term);
                            if (is_wp_error($term_link)) continue;

                            $is_active = (
                                $current_term instanceof WP_Term &&
                                (int)$current_term->term_id === (int)$term->term_id
                            );

                            $image      = get_field('zobrazhennya_movi', 'term_' . $term->term_id);
                            $imagehover = get_field('zobrazhennya_movi_pri_navedenn', 'term_' . $term->term_id);

                            $image_url      = is_array($image) ? ($image['url'] ?? '') : (string)$image;
                            $imagehover_url = is_array($imagehover) ? ($imagehover['url'] ?? '') : (string)$imagehover;
                            ?>
                            <a href="<?php echo esc_url($term_link); ?>"
                               class="language <?php echo $is_active ? 'is-active' : ''; ?>">
                                <div class="language__image">
                                    <?php if (!empty($image_url)) : ?>
                                        <img
                                                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                                data-src="<?php echo esc_url($image_url); ?>"
                                                class="language__image-normal"
                                                alt="<?php echo esc_attr($term->name); ?>"
                                        >
                                    <?php endif; ?>

                                    <?php if (!empty($imagehover_url)) : ?>
                                        <img
                                                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                                data-src="<?php echo esc_url($imagehover_url); ?>"
                                                class="language__image-active"
                                                alt="<?php echo esc_attr($term->name); ?>"
                                        >
                                    <?php endif; ?>
                                </div>

                                <div class="technology__title">
                                    <?php echo esc_html($term->name); ?>
                                </div>
                            </a>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    /**
     * Популярно (swiper)
     */
    $popular_q = new WP_Query([
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 8,
        'ignore_sticky_posts' => true,
        'suppress_filters'    => true,
        'tax_query'           => [[
            'taxonomy'         => 'category',
            'field'            => 'slug',
            'terms'            => ['populyarni'],
            'include_children' => false,
        ]],
    ]);

    if ($popular_q->have_posts()) : ?>
        <section class="popular-posts">
            <div class="container">
                <h2 class="taxonomy-language__title">Популярно: </h2>

                <div class="popular-posts__list swiper-container">
                    <div class="swiper-wrapper">
                        <?php while ($popular_q->have_posts()) : $popular_q->the_post(); ?>

                            <article <?php post_class('popular-post swiper-slide'); ?>>
                                <?php
                                $acf_image   = get_field('zobrazhennya_na_rozvodyashu');
                                $acf_title   = get_field('zagolovok_na_rozvodyashu');
                                $acf_excerpt = get_field('korotkij_tekst_na_rozvodyashu');
                                $title_to_show = $acf_title ? $acf_title : get_the_title();
                                ?>

                                <div class="thumbnail">
                                    <?php
                                    if ($acf_image) {
                                        if (is_numeric($acf_image)) {
                                            echo wp_get_attachment_image((int)$acf_image, 'large');
                                        } elseif (is_array($acf_image) && !empty($acf_image['id'])) {
                                            echo wp_get_attachment_image((int)$acf_image['id'], 'large');
                                        } elseif (is_array($acf_image) && !empty($acf_image['url'])) {
                                            echo '<img src="' . esc_url($acf_image['url']) . '" alt="' . esc_attr($acf_image['alt'] ?: $title_to_show) . '">';
                                        } else {
                                            echo '<img src="' . esc_url($acf_image) . '" alt="' . esc_attr($title_to_show) . '">';
                                        }
                                    } elseif (has_post_thumbnail()) {
                                        the_post_thumbnail('large');
                                    }
                                    ?>
                                </div>

                                <div class="taxonomy-post__date"><?php echo esc_html(get_the_date('d.m.y')); ?></div>

                                <h3 class="taxonomy-post__title banner-subtitle h3">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo esc_html($title_to_show); ?>
                                    </a>
                                </h3>

                                <div class="taxonomy-post__excerpt">
                                    <?php
                                    if (!empty($acf_excerpt)) {
                                        echo wp_kses_post(wpautop($acf_excerpt));
                                    } else {
                                        the_excerpt();
                                    }
                                    ?>
                                </div>
                            </article>

                        <?php endwhile; ?>
                    </div>

<!--                    <div class="swiper-button-prev"></div>-->
<!--                    <div class="swiper-button-next"></div>-->
                </div>
            </div>
        </section>
    <?php
    endif;
    wp_reset_postdata();
    ?>

    <!-- ===== Все статьи (все языки на /language/, или только выбранный язык на терм-странице) ===== -->
    <div class="taxonomy-posts">
        <div class="container">
            <h2 class="taxonomy-language__title">Всі статті: </h2>

            <?php if ($all_posts_q->have_posts()) : ?>
                <div class="taxonomy-posts__list">
                    <?php while ($all_posts_q->have_posts()) : $all_posts_q->the_post(); ?>

                        <?php
                        $acf_image   = get_field('zobrazhennya_na_rozvodyashu');
                        $acf_title   = get_field('zagolovok_na_rozvodyashu');
                        $acf_excerpt = get_field('korotkij_tekst_na_rozvodyashu');
                        $title_to_show = $acf_title ? $acf_title : get_the_title();
                        ?>

                        <article <?php post_class('taxonomy-posts__item'); ?>>
                            <a href="<?php the_permalink(); ?>" class="thumbnail">
                                <?php
                                if ($acf_image) {
                                    if (is_numeric($acf_image)) {
                                        echo wp_get_attachment_image((int)$acf_image, 'large');
                                    } elseif (is_array($acf_image) && !empty($acf_image['id'])) {
                                        echo wp_get_attachment_image((int)$acf_image['id'], 'large');
                                    } elseif (is_array($acf_image) && !empty($acf_image['url'])) {
                                        echo '<img src="' . esc_url($acf_image['url']) . '" alt="' . esc_attr($acf_image['alt'] ?: $title_to_show) . '">';
                                    } else {
                                        echo '<img src="' . esc_url($acf_image) . '" alt="' . esc_attr($title_to_show) . '">';
                                    }
                                } elseif (has_post_thumbnail()) {
                                    the_post_thumbnail('large');
                                }
                                ?>
                            </a>

                            <div class="taxonomy-posts__item-date"><?php echo esc_html(get_the_date('d.m.y')); ?></div>

                            <h3 class="taxonomy-posts__item-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo esc_html($title_to_show); ?>
                                </a>
                            </h3>

                            <div class="taxonomy-posts__item-excerpt">
                                <?php
                                if (!empty($acf_excerpt)) {
                                    echo wp_kses_post(wpautop($acf_excerpt));
                                } else {
                                    the_excerpt();
                                }
                                ?>
                            </div>
                        </article>

                    <?php endwhile; ?>
                </div>

                <div class="taxonomy-pagination">
                    <?php
                    echo paginate_links([
                        'total'   => (int) $all_posts_q->max_num_pages,
                        'current' => $paged,
                    ]);
                    ?>
                </div>

            <?php else : ?>
                <p>Немає записів.</p>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>
        </div>
    </div>

    <?php
    // ====== дальше твой блок формы я оставил как есть (минимальные правки только на безопасность) ======
    $data = get_fields();
    $phone_number_text = get_field('tekst_vvedt_nomer_telefonu', 'option');
    $name_text = get_field('common_form_data_plejsholder_v_pol_mya', 'option');
    $button_text = get_field('common_form_data_tekst_knopki_vdpravki_formi', 'option');
    $agreement_txt = get_field('common_form_data_tekst_poltiki', 'options');
    $agreement_link = get_field('common_form_data_tekst_poltiki_link', 'options');
    $agreement_link_txt = get_field('common_form_data_tekst_poltiki_2', 'options');
    ?>

    <section class="ed-home-questions ed-home-questions-language">
        <div class="container">
            <div class="questions-wrapper">
                <div class="questions-form">
                    <div class="questions-title h1">Хочеш нарешті вивчити іноземну? </div>
                    <div class="questions-subtitle h3">Заповни форму і ми зв'яжемося з тобою:</div>

                    <form class="form">
                        <input type="hidden" name="action" value="ed_callback">
                        <input type="hidden" name="url" value="<?php echo admin_url('admin-ajax.php'); ?>">
                        <?php wp_nonce_field('ed_callback'); ?>
                        <input type="hidden" name="title" value="Залишилися питання">

                        <div class="form-row">
                            <input class="form-row__input required" type="text" name="name"
                                   placeholder="<?php echo $phone_number_text; ?>">
                        </div>

                        <div class="form-row">
                            <input class="form-row__input required-phone" type="text" name="phone"
                                   placeholder="<?php echo $name_text; ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-select">
                                <Label>
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <rect width="70" height="70" fill="white"/>
                                        <g clip-path="url(#clip0_776_7291)">
                                            <path d="M20.7633 52.8418C19.5292 54.01 19.19 56.0638 19.8966 57.8962C20.3865 59.1727 21.2815 60.1148 22.3743 60.8968C24.5082 62.423 26.9388 63.1766 29.4919 63.577C36.4917 64.6793 43.1711 63.7183 49.3277 60.1195C57.378 55.409 62.2486 48.4045 63.7277 39.2002C65.6731 27.0566 59.4035 14.8517 48.4563 9.11905C44.7633 7.18775 40.8489 6.10434 36.6942 5.86882C27.9986 5.37893 20.5514 8.30414 14.3759 14.3289C9.80674 18.785 7.12647 24.3057 6.16082 30.613C5.0303 37.9755 6.67426 44.7209 10.8902 50.854C11.7192 52.0646 12.5341 53.2846 13.3537 54.4999C13.415 54.5894 13.4621 54.6883 13.5139 54.7825C12.5671 55.3808 11.4319 55.2583 10.5086 54.3869C9.86797 53.7792 9.26032 53.1292 8.7139 52.4367C5.74158 48.6542 3.98928 44.3535 3.33923 39.5912C2.1993 31.2301 3.9516 23.5143 8.81282 16.6276C13.9049 9.40168 20.8811 4.88903 29.652 3.44291C45.4651 0.842724 60.5246 9.87273 65.3481 25.1065C69.1872 37.2265 66.4362 48.0607 57.7265 57.2979C52.8889 62.4324 46.7888 65.3576 39.8691 66.4646C34.6404 67.2983 29.4306 67.0439 24.3951 65.1786C22.5109 64.4767 20.768 63.5205 19.3266 62.0838C16.3826 59.1445 15.9681 55.3054 18.3374 51.8903C18.898 51.0895 18.9027 50.5667 18.3704 49.7847C17.3577 48.3009 17.4613 46.6098 17.7628 44.9329C18.7472 39.4122 20.7775 34.2401 23.1751 29.2093C25.926 23.4342 29.5531 18.2291 33.6983 13.3679C34.7158 12.1715 36.0206 11.5167 37.5562 11.366C38.8281 11.2388 40.1093 11.1822 41.3859 11.154C42.7707 11.121 43.2606 11.6345 43.3407 13.0382C43.4491 14.8564 43.0298 16.5946 42.4881 18.2951C42.1207 19.4539 41.6355 20.5844 41.1032 21.6725C40.4767 22.9491 39.3886 23.6085 37.9566 23.7404C36.4681 23.877 35.3093 24.6637 34.5415 25.8978C33.4487 27.6501 32.3935 29.4307 31.4609 31.2725C30.2361 33.6843 29.1245 36.1573 28.4932 38.8046C28.3755 39.2992 28.2719 39.7985 28.22 40.2978C28.107 41.3529 28.4461 42.2668 29.1998 43.011C29.8876 43.6893 30.0995 44.4807 29.7745 45.3851C28.6534 48.5176 26.8917 51.2073 24.202 53.2234C22.949 54.1608 22.4356 54.1325 21.1967 53.1668C21.0507 53.0538 20.9047 52.9454 20.7586 52.8418H20.7633Z"
                                                  fill="#858523"/>
                                            <path d="M41.1973 24.2774C43.9199 20.7587 44.8762 16.7595 44.9704 12.374C46.2234 13.09 47.6035 13.4763 48.5645 14.4985C48.979 14.9412 49.2616 15.6525 49.304 16.2602C49.5254 19.2231 48.7105 21.9033 46.8687 24.268C46.2469 25.0688 45.4461 25.3891 44.4852 25.1677C43.4065 24.9228 42.3466 24.5883 41.1973 24.2727V24.2774Z"
                                                  fill="#858523"/>
                                            <path d="M31.3381 44.9375C32.2614 45.7242 33.1611 46.4072 33.9619 47.1891C34.6214 47.8345 34.6873 48.7294 34.4706 49.5726C33.7735 52.2953 32.2049 54.4621 29.9957 56.1579C28.9923 56.9304 27.8477 57.0199 26.6983 56.4452C25.8975 56.0448 25.1156 55.6115 24.2441 55.1451C27.8053 52.5591 30.2453 49.2806 31.3428 44.9375H31.3381Z"
                                                  fill="#858523"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_776_7291">
                                                <rect width="64.0438" height="63.9213" fill="white"
                                                      transform="translate(3 3)"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <input type="checkbox" name="Call" value="Звонок">
                                    <span>
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 6L7 14L17 1" stroke="white" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </Label>
                                <Label>
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="34.9998" cy="34.9998" r="31.1111" stroke="#32ACDF"
                                                stroke-width="2"/>
                                        <path d="M47.9251 23.333L13.6113 35.4857L23.4152 40.3468L39.8613 32.083L30.1391 43.7497L43.0231 52.4997L47.9251 23.333Z"
                                              stroke="#32ACDF" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                    </svg>
                                    <input type="checkbox" name="Telegram" value="Telegram">
                                    <span>
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 6L7 14L17 1" stroke="white" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </Label>
                                <Label>
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M46.7045 43.395C47.325 41.5334 47.325 40.0855 47.1182 39.6718C46.9113 39.2581 46.4976 39.2581 45.6703 38.8445C44.8429 38.4308 41.1198 36.5692 40.4992 36.3624C39.8787 36.1555 39.0513 36.1555 38.6377 36.9829C37.8103 38.0171 37.1898 39.0513 36.5693 39.6718C36.1556 40.0855 35.3282 40.2923 34.7077 39.8787C33.8803 39.465 31.3982 38.6376 28.5025 36.1555C26.2272 34.0871 24.7793 31.605 24.1588 30.7777C23.7451 29.9503 24.1588 29.5366 24.5725 29.1229C24.9862 28.7092 25.3998 28.2956 25.8135 27.8819C26.2272 27.4682 26.434 27.2614 26.6409 26.6408C26.8477 26.2271 26.6409 25.6066 26.434 25.1929C26.2272 24.7793 24.7793 21.0561 24.1588 19.6082C23.7451 18.574 23.3314 18.574 22.5041 18.574C22.2972 18.3672 21.8835 18.3672 21.6767 18.3672C20.6425 18.3672 19.6083 18.574 18.9878 19.4014C18.1604 20.2288 16.2988 22.0903 16.2988 25.8135C16.2988 29.5366 18.9878 33.2597 19.4014 33.6734C19.8151 34.0871 24.7793 41.9471 32.4324 45.2565C38.4308 47.7386 40.2924 47.5318 41.5334 47.3249C43.6019 46.7044 46.0839 45.2565 46.7045 43.395Z"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M34.9148 3.88867C17.747 3.88867 3.88867 7.81865 3.88867 30.778C3.88867 45.6706 9.68022 52.4963 18.3676 55.599V64.7C18.3676 65.941 19.8154 66.5615 20.8496 65.7342L28.9165 57.6674C30.778 57.6674 32.8464 57.6674 34.9148 57.6674C52.0827 57.6674 65.941 53.7374 65.941 30.778C65.941 7.81865 52.0827 3.88867 34.9148 3.88867Z"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M33.8809 24.9863C36.5698 24.9863 38.6382 27.0547 38.6382 29.7437"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M33.8809 12.1621C43.6024 12.1621 51.4624 20.0221 51.4624 29.7436"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M33.8809 18.5742C40.0861 18.5742 45.0503 23.5384 45.0503 29.7436"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <input type="checkbox" name="Viber" value="Viber">
                                    <span>
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 6L7 14L17 1" stroke="white" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </Label>
                                <Label>
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M34.871 3.88867C18.9007 3.88867 5.83398 16.9553 5.83398 32.9257C5.83398 39.9776 8.53028 46.6146 12.6784 51.5924L7.90806 66.1109L23.0488 59.4739C26.5747 61.1331 30.7229 61.9628 34.871 61.9628C50.8414 61.9628 63.9081 48.8961 63.9081 32.9257C63.9081 16.9553 50.8414 3.88867 34.871 3.88867Z"
                                              stroke="#5CCF67" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.8405 43.5035C51.4627 41.6369 51.4627 40.185 51.2553 39.7702C51.0479 39.3554 50.633 39.3554 49.8034 38.9406C48.9738 38.5257 45.2405 36.6591 44.6182 36.4517C43.996 36.2443 43.1664 36.2443 42.7516 37.0739C41.9219 38.1109 41.2997 39.148 40.6775 39.7702C40.2627 40.185 39.433 40.3924 38.8108 39.9776C37.9812 39.5628 35.4923 38.7332 32.5886 36.2443C30.3071 34.1702 28.8553 31.6813 28.233 30.8517C27.8182 30.022 28.233 29.6072 28.6479 29.1924C29.0627 28.7776 29.4775 28.3628 29.8923 27.948C30.3071 27.5332 30.5145 27.3257 30.7219 26.7035C30.9293 26.2887 30.7219 25.6665 30.5145 25.2517C30.3071 24.8369 28.8553 21.1035 28.233 19.6517C27.8182 18.4072 27.4034 18.4072 26.5738 18.4072C26.3664 18.4072 25.9516 18.4072 25.7442 18.4072C24.7071 18.4072 23.6701 18.6146 23.0479 19.4443C22.2182 20.2739 20.3516 22.1406 20.3516 25.8739C20.3516 29.6072 23.0479 33.3406 23.4627 33.7554C23.8775 34.1702 28.8553 42.0517 36.5293 45.3702C42.5442 47.8591 44.4108 47.6517 45.6553 47.4443C47.7293 46.822 50.2182 45.3702 50.8405 43.5035Z"
                                              stroke="#5CCF67" stroke-width="2"/>
                                    </svg>
                                    <input type="checkbox" name="Whatsap" value="Whatsap">
                                    <span>
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 6L7 14L17 1" stroke="white" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </Label>
                                <!--                                <div>Оберіть месенджер</div>-->
                            </div>
                        </div>
                        <div class="form-row form-row--button">
                            <button class="button"
                                    type="submit"><?php echo $button_text; ?></button>
                        </div>
                        <div class="" style="margin-top: 20px">
                            <div class="form-row__agreement"><?= $agreement_txt; ?> <a href="<?= $agreement_link; ?>"
                                                                                       target="_blank"><?= $agreement_link_txt; ?></a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="questions-trial">
                    <div class="questions-trial__button callpopup" data-popup="trial">
                        <span><?= $data['questions_tekst_knopki']; ?></span>

                        <svg viewBox="0 0 188 198" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.3786 174.642C-11.7504 89.8565 26.0552 40.2692 70.2724 13.4833C92.1599 0.422404 115.816 -2.23406 136.819 5.95669C158.265 14.3688 174.625 33.6281 181.921 58.6431C192.312 94.2839 185.237 133.688 163.792 161.359C145.442 184.825 118.469 197.222 88.4014 196.115C50.1536 193.901 28.4872 185.268 6.3786 174.642Z"
                                  stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.querySelector('.popular-posts__list');
        if (!el) return;

        new Swiper('.popular-posts__list', {
            slidesPerView: 2,
            spaceBetween: 20,
            speed: 600,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                0: { slidesPerView: 1, spaceBetween: 16 },
                768: { slidesPerView: 2, spaceBetween: 20 }
            }
        });
    });
</script>

<?php get_footer(); ?>
