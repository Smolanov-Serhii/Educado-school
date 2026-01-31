<?php
get_header();

$taxonomy = 'language';
$current_term = get_queried_object();

/**
 * Термины таксономии для верхних ссылок
 */
$terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false, // true — если показывать только термины с постами
        'orderby'    => 'name',
        'order'      => 'ASC',
]);

/**
 * Будем собирать ID уже выведенных постов, чтобы не дублировать их в блоке "populyarni"
 */
$shown_post_ids = [];
?>

<main class="taxonomy-language">
    <section class="ed-blog">
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title h1">
                    <?= get_field('zagolovok_bloku', 1084); ?>
                </h1>
                <div class="banner-subtitle h3">
                    <?= get_field('pdgagolovok_bloku', 1084); ?>
                </div>
            </div>
            <div class="banner-world">
                <img src="<?= get_field('kartinka_dlya_bloku', 1084); ?>" alt="<?= get_field('zagolovok_bloku', 1084); ?>">
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
                            <a
                                    href="<?php echo esc_url($term_link); ?>"
                                    class="language <?php echo $is_active ? 'is-active' : ''; ?>"
                            >
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
            <div class="languages-ticker languages-ticker--reverse">
                <div class="languages-ticker__wrapper">
                    <div class="languages-ticker__line">

                        <?php
                        $reversed_terms = array_reverse($terms);

                        foreach ($reversed_terms as $term) :

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
                            <a
                                    href="<?php echo esc_url($term_link); ?>"
                                    class="language <?php echo $is_active ? 'is-active' : ''; ?>"
                            >
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
    $popular_q = new WP_Query([
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 8,
            'ignore_sticky_posts' => true,
            'suppress_filters'    => true,
            'post__not_in'        => !empty($shown_post_ids) ? $shown_post_ids : [],
            'tax_query'           => [[
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => ['populyarni'],
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
                                <?php $shown_post_ids[] = get_the_ID(); ?>

                                <?php
                                $acf_image   = get_field('zobrazhennya_na_rozvodyashu');   // image (array/url/id)
                                $acf_title   = get_field('zagolovok_na_rozvodyashu');      // text
                                $acf_excerpt = get_field('korotkij_tekst_na_rozvodyashu'); // textarea
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
                                            echo '<img src="' . esc_url($acf_image['url']) . '" alt="' . esc_attr(($acf_image['alt'] ?? '') ?: $title_to_show) . '">';
                                        } else {
                                            echo '<img src="' . esc_url($acf_image) . '" alt="' . esc_attr($title_to_show) . '">';
                                        }
                                    } elseif (has_post_thumbnail()) {
                                        the_post_thumbnail('large');
                                    }
                                    ?>
                                </div>
                                <div class="taxonomy-post__date">
                                    <?php echo esc_html(get_the_date('d.m.y')); ?>
                                </div>
                                <h3 class="taxonomy-post__title banner-subtitle h3">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo esc_html($title_to_show); ?>
                                    </a>
                                </h3>

                                <div class="taxonomy-post__excerpt">
                                    <?php
                                    // Короткий текст: ACF > обычный excerpt
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
                </div>
            </div>
        </section>
    <?php
    endif;

    wp_reset_postdata();
    ?>
    <?php
    $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
    $lang_term_id = ($current_term instanceof WP_Term) ? (int) $current_term->term_id : 0;

    $posts_q = new WP_Query([
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => (int) get_option('posts_per_page'),
            'paged'               => $paged,
            'ignore_sticky_posts' => true,
            'tax_query'           => $lang_term_id ? [[
                    'taxonomy'         => $taxonomy,
                    'field'            => 'term_id',
                    'terms'            => [$lang_term_id],
                    'include_children' => false,
            ]] : [],
    ]);

    if ($posts_q->have_posts()) : ?>
        <div class="taxonomy-posts">
            <div class="container">
                <h2 class="taxonomy-language__title">Всі статті: </h2>

                <div class="taxonomy-posts__list">
                    <?php while ($posts_q->have_posts()) : $posts_q->the_post(); ?>
                        <?php $shown_post_ids[] = get_the_ID(); ?>

                        <?php
                        $acf_image   = get_field('zobrazhennya_na_rozvodyashu');   // image (array/url/id)
                        $acf_title   = get_field('zagolovok_na_rozvodyashu');      // text
                        $acf_excerpt = get_field('korotkij_tekst_na_rozvodyashu'); // textarea
                        $title_to_show = $acf_title ? $acf_title : get_the_title();
                        ?>

                        <article <?php post_class('taxonomy-posts__item'); ?>>
                            <a href="<?php the_permalink(); ?>" class="thumbnail">
                                <?php
                                // Картинка: ACF > featured image
                                if ($acf_image) {
                                    if (is_numeric($acf_image)) {
                                        echo wp_get_attachment_image((int)$acf_image, 'large');
                                    } elseif (is_array($acf_image) && !empty($acf_image['id'])) {
                                        echo wp_get_attachment_image((int)$acf_image['id'], 'large');
                                    } elseif (is_array($acf_image) && !empty($acf_image['url'])) {
                                        echo '<img src="' . esc_url($acf_image['url']) . '" alt="' . esc_attr(($acf_image['alt'] ?? '') ?: $title_to_show) . '">';
                                    } else {
                                        echo '<img src="' . esc_url($acf_image) . '" alt="' . esc_attr($title_to_show) . '">';
                                    }
                                } elseif (has_post_thumbnail()) {
                                    the_post_thumbnail('large');
                                }
                                ?>
                            </a>

                            <div class="taxonomy-posts__item-date">
                                <?php echo esc_html(get_the_date('d.m.y')); ?>
                            </div>

                            <h3 class="taxonomy-posts__item-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo esc_html($title_to_show); ?>
                                </a>
                            </h3>

                            <div class="taxonomy-posts__item-excerpt">
                                <?php
                                // Короткий текст: ACF > обычный excerpt
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
                            'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format'    => '',
                            'current'   => $paged,
                            'total'     => (int) $posts_q->max_num_pages,
                            'prev_text' => '←',
                            'next_text' => '→',
                    ]);
                    ?>
                </div>
            </div>
        </div>
    <?php
    endif;

    wp_reset_postdata();

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
                            </div>
                        </div>

                        <div class="form-row form-row--button">
                            <button class="button" type="submit"><?php echo $button_text; ?></button>
                        </div>
                        <div class="" style="margin-top: 20px">
                            <div class="form-row__agreement">
                                <?= $agreement_txt; ?>
                                <a href="<?= $agreement_link; ?>" target="_blank"><?= $agreement_link_txt; ?></a>
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
        const popularPostsSwiper = new Swiper('.popular-posts__list', {
            slidesPerView: 2,
            spaceBetween: 20,
            speed: 600,
            loop: false,

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 16,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                }
            }
        });
    });
</script>

<?php get_footer(); ?>
