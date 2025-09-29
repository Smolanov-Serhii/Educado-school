<?php
/**
 * Template Name: Шаблон мови
 */
?>
<?php get_header(); ?>
<main class="ed-home еуіе">
    <?php
    $data = get_fields();
    ?>
    <section class="ed-home-banner ed-home-banner-language">
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title h1"><?= $data['section_1_zagolovok']; ?></h1>
                <div class="banner-subtitle h3"><?= $data['section_1_pdzagolovok']; ?></div>
                <div class="button callpopup" data-popup="trial"><?= get_field('tekst_zapisatisya', 'option'); ?></div>
            </div>
            <div class="banner-world">
                <div class="banner-world__image">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?= $data['kartinka_v_pershij_blok']; ?>" alt="<?= $data['section_1_zagolovok']; ?>">
                </div>
            </div>
        </div>
    </section>


    <section class="ed-home-look">
        <div class="container">
            <div class="look-benefits look-benefits-language">
                <?php
                if ($sec_2_benefits = $data['how_stady_perevagi']) {
                    foreach ($sec_2_benefits as $item) { ?>
                        <div class="look-benefits__item">
                            <div class="benefit-icon">
                                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                     data-src="<?= $item['konka']; ?>"
                                     decoding="async" alt="icon">
                            </div>

                            <div class="benefit-info">
                                <div class="benefit-info__percent h1"><?= $item['chislo']; ?></div>
                                <div class="benefit-info__title h5"><?= $item['tekst']; ?></div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <div class="ed-home-look__bg">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/look.svg"
                 decoding="async" alt="icon">
        </div>
    </section>
    <section class="ed-home-courses ed-home-courses-language">
        <div class="container">
            <div class="accordion">
                <div class="accordion-body">
                    <div class="accordion-title h2">
                        <svg>
                            <use xlink:href="#ed-svg-half1"></use>
                        </svg>
                        <span><?= $data['zagolovok_tretogo_bloku']; ?></span>
                    </div>

                    <?php if ($tags = get_field('tegi_tretogo_bloku')) : ?>
                        <div class="accordion-tags h5">
                            <?php foreach ($tags as $item) : ?>
                                <div class="accordion-tags__item">
                                    <svg>
                                        <use xlink:href="#ed-svg-tag"></use>
                                    </svg>
                                    <span><?= $item['tekst_tegu']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="accordion-block">
                        <?php the_field('tekst_tretogo_bloku'); ?>
                    </div>

                    <?php $price = get_field('cni') ?>
                        <div class="accordion-block">
                            <div class="h4"><?= $data['zagolovok_bloku_z_spiskom']; ?></div>

                            <div class="accordion-options">
                                <?php foreach ($price as $item) : ?>
                                    <div class="option">
                                        <div class="option__icon">
                                            <svg>
                                                <use xlink:href="#ed-svg-half2"></use>
                                            </svg>
                                        </div>

                                        <div class="option__title"><?= $item['zagolovok']; ?></div>
                                        <div class="option__cost h4"><?= $item['cna']; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    <div class="accordion-block">
                        <div class="accordion-parts">
                            <div class="accordion-parts__icon">
                                <svg width="39" height="40" viewBox="0 0 39 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_848_7294)">
                                        <path d="M31.0063 33.5077C31.0045 36.8392 29.0068 39.3062 25.7776 39.9051C24.534 40.136 23.3071 39.9304 22.1463 39.4357C20.5973 38.7748 19.0772 38.0426 17.5217 37.3987C15.874 36.7162 14.1482 36.5839 12.3758 36.7312C10.7747 36.8636 9.16146 37.0006 7.56128 36.9406C4.57131 36.8289 2.24318 34.4304 2.01884 31.4227C1.82708 28.8535 2.86222 26.7667 4.57317 24.9915C5.28901 24.249 6.1659 23.6642 6.97483 23.0155C8.14773 22.0759 9.36253 21.1859 10.4926 20.1965C11.8582 18.9997 13.1093 17.6685 14.4879 16.4886C17.854 13.6076 22.4534 15.363 24.0629 18.3745C24.83 19.8098 25.4453 21.3286 26.1034 22.8221C26.7606 24.3128 27.5854 25.6918 28.5795 26.9798C29.8334 28.6047 30.6945 30.4155 30.9682 32.4797C30.9803 32.5727 31.0035 32.6647 31.0054 32.7576C31.011 33.0073 31.0073 33.257 31.0073 33.5067L31.0063 33.5077Z" fill="black"/>
                                        <path d="M21.3848 10.569C21.5914 7.48717 22.7504 4.91225 25.3615 3.17655C28.3012 1.22212 31.4606 2.54385 32.2388 5.99648C32.9984 9.36744 31.1953 13.4969 28.2174 15.2054C25.2432 16.911 22.2002 15.4785 21.5533 12.0588C21.4611 11.5716 21.4397 11.0693 21.3857 10.569H21.3848Z" fill="black"/>
                                        <path d="M9.58223 6.54365C9.57106 5.09238 10.0179 3.10322 11.498 1.48768C13.3905 -0.578456 16.1282 -0.478012 17.9052 1.69889C19.2047 3.29191 19.6804 5.1656 19.6255 7.18855C19.5817 8.7919 19.1656 10.292 18.2422 11.624C16.278 14.4571 12.8971 14.4383 10.9683 11.5808C10.083 10.2694 9.66694 8.80598 9.58317 6.54459L9.58223 6.54365Z" fill="black"/>
                                        <path d="M28.401 21.2105C28.401 17.8958 31.0977 14.6572 34.3028 14.1231C36.9585 13.68 39.1591 15.7818 38.9897 18.5989C38.811 21.5813 36.804 24.3007 34.0086 25.2517C32.3405 25.8187 30.7608 25.6628 29.4817 24.3026C28.6775 23.4465 28.3852 22.3754 28.401 21.2105Z" fill="black"/>
                                        <path d="M9.44269 15.2779C9.42314 16.3978 9.21556 17.4717 8.68403 18.4686C7.41896 20.8445 4.81065 21.4256 2.67243 19.8025C0.0566626 17.8162 -0.805329 13.5356 0.834876 10.6753C2.11949 8.43643 4.64402 7.89573 6.71057 9.44462C8.28933 10.6284 9.0601 12.2899 9.3617 14.2058C9.39801 14.4368 9.42035 14.6696 9.44083 14.9024C9.45107 15.0263 9.44269 15.1521 9.44269 15.2769V15.2779Z" fill="black"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_848_7294">
                                            <rect width="39" height="40" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>

                            </div>

                            <div class="accordion-parts__title" style="color: #000000">Доступна покупка частинами від monobank</div>
                        </div>
                    </div>

                    <div class="accordion-image">
                        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                             data-src="<?php the_field('napis_na_knopc'); ?>"
                             decoding="async" alt="icon">
                    </div>

                    <form class="form accordion-form">
                        <input type="hidden" name="action" value="ed_callback">
                        <input type="hidden" name="url" value="<?php echo admin_url('admin-ajax.php'); ?>">
                        <?php wp_nonce_field('ed_callback'); ?>
                        <input type="hidden" name="title" value="<?php the_title(); ?>">

                        <div class="form-row">
                            <input class="form-row__input required-phone" type="text" name="phone"
                                   placeholder="Введіть номер телефону">
                        </div>

                        <div class="form-row form-row--button">
                            <button class="button" type="submit"><?= $data['napis_na_knopc']; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="ed-home-start">
        <div class="start-lines">
            <svg viewBox="0 0 1920 627" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M-216.31 2.08907C-26.895 -8.2502 112.585 97.1772 270.874 181.262C494.364 299.991 751.319 346.774 1002.72 321.832C1147.71 307.451 1276.08 257.222 1411.38 206.508C1639.12 121.15 1875.45 -1.17742 2125.31 21.0514"
                      stroke="#2D2D0B" stroke-miterlimit="10" stroke-dasharray="8 8"/>
                <path d="M-258.481 626.211C-85.6046 506.417 90.4827 390.429 276.992 292.753C464.898 194.343 659.185 124.251 873.042 180.059C1004.88 214.468 1122.44 285.583 1243.26 346.082C1368.28 408.685 1501.08 459.593 1638 488.894C1912.78 547.677 2206.06 532.189 2479.28 472.216"
                      stroke="#2D2D0B" stroke-miterlimit="10" stroke-dasharray="8 8"/>
                <path d="M2263.75 286.649C2172.49 253.112 2068.48 256.517 1972.62 254.496C1551 245.596 1097.27 283.271 699.502 430.402C562.845 480.963 419.697 525.49 272.3 520.05C57.654 512.133 -137.477 400.159 -322.616 291.23"
                      stroke="#2D2D0B" stroke-miterlimit="10" stroke-dasharray="8 8"/>
            </svg>
        </div>

        <div class="start-content">
            <div class="container">
                <div class="start-title h1"><?= $data['how_start_zagolovok']; ?></div>

                <div class="start-steps">
                    <div class="start-step">
                        <?php
                        $how_start_card_1 = $data['how_start_kartka_1'];
                        ?>
                        <div class="start-step__icon">
                            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                 data-src="<?= $how_start_card_1['konka']; ?>"
                                 decoding="async" alt="icon">
                        </div>

                        <div class="start-step__pretitle h4"><?= $how_start_card_1['tekst_1']; ?></div>
                        <div class="start-step__title h3"><?= $how_start_card_1['tekst_2']; ?></div>

                        <div class="button callpopup"
                             data-popup="callback"><?= $how_start_card_1['tekst_knopki']; ?></div>
                    </div>

                    <div class="start-step">
                        <?php
                        $how_start_card_2 = $data['how_start_kartka_2'];
                        ?>
                        <div class="start-step__icon">
                            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                 data-src="<?= $how_start_card_2['konka']; ?>"
                                 decoding="async" alt="icon">
                        </div>

                        <div class="start-step__pretitle h4"><?= $how_start_card_2['tekst_1']; ?></div>
                        <div class="start-step__title h3"><?= $how_start_card_2['tekst_2']; ?></div>
                    </div>

                    <div class="start-step">
                        <?php
                        $how_start_card_3 = $data['how_start_kartka_3'];
                        ?>
                        <div class="start-step__title h3"><?= $how_start_card_3['zagolovok']; ?></div>

                        <div class="start-step__list h5">
                            <?php
                            if ($how_start_card_3['spisok']) {
                                foreach ($how_start_card_3['spisok'] as $item) { ?>
                                    <div class="start-step__list-item">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.89478 10.374L7.89478 16.374L19.8948 4.37402" stroke="#161616"
                                                  stroke-width="2" stroke-linecap="round"/>
                                        </svg>

                                        <svg>
                                            <use xlink:href="#ed-svg-check2"></use>
                                        </svg>
                                        <span><?= $item['tekst']; ?></span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>

                        <div class="start-step__title h3"><?= $how_start_card_3['tekst']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ($sec_languages = $data['lang_movi']) : ?>
        <section class="ed-home-languages">
            <div class="languages-ticker">
                <div class="languages-ticker__wrapper">
                    <div class="languages-ticker__line">
                        <?php foreach ($sec_languages as $item) { ?>
                            <div class="language">
                                <div class="language__image">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['konka']; ?>"
                                         class="language__image-normal" alt="language-icon">

                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['konka_hover']; ?>"
                                         class="language__image-active" alt="language-icon">
                                </div>

                                <div class="technology__title"><?= $item['mova']; ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="languages-ticker languages-ticker--reverse">
                <div class="languages-ticker__wrapper">
                    <div class="languages-ticker__line">
                        <?php
                        $reversed_languages = array_reverse($sec_languages);
                        foreach ($reversed_languages as $item) { ?>
                            <div class="language">
                                <div class="language__image">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['konka']; ?>"
                                         class="language__image-normal" alt="language-icon">

                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['konka_hover']; ?>"
                                         class="language__image-active" alt="language-icon">
                                </div>

                                <div class="technology__title"><?= $item['mova']; ?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="ed-home-trial">
        <div class="trial-bg">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/trial-bg.svg"
                 decoding="async" alt="icon">
        </div>

        <div class="container">
            <div class="trial-title h2"><?= $data['can_zagolovok']; ?></div>
        </div>

        <div class="languages-ticker languages-ticker--trial callpopup" data-popup="trial">
            <div class="languages-ticker__wrapper">
                <div class="languages-ticker__line">
                    <?php
                    if ($can_list = $data['can_spisok']) {
                        foreach ($can_list as $item) { ?>
                            <div class="language">
                                <div class="language__image">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?php echo get_template_directory_uri(); ?>/assets/img/trial-icon.svg"
                                         alt="icon">
                                </div>

                                <div class="technology__title"><?= $item['tekst']; ?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section class="ed-home-about">
        <div class="container">
            <div class="about-teachers" id="ed-teachers">
                <div class="about-teachers-title h1"><?= $data['teachers_zagolovok']; ?></div>

                <?php if ($teachers_list = $data['teachers_vikladach']) : ?>
                    <div class="about-teachers-slider swiper">
                        <div class="swiper-wrapper">
                            <?php
                            foreach ($teachers_list as $item) {
                                get_template_part('parts/pt-cards/content', 'teacher-card', array(
                                    'id' => $item,
                                ));
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="about-bg-left">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/about-left.svg"
                 decoding="async" alt="icon">
        </div>

        <div class="about-bg-right">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/about-right.svg"
                 decoding="async" alt="icon">
        </div>
    </section>


    <section class="ed-home-questions">
        <div class="container">
            <div class="questions-wrapper">
                <div class="questions-form">
                    <div class="questions-title h1"><?= $data['questions_zagolovok']; ?></div>
                    <div class="questions-subtitle h3"><?= $data['questions_pdzagolovok']; ?></div>

                    <form class="form">
                        <input type="hidden" name="action" value="ed_callback">
                        <input type="hidden" name="url" value="<?php echo admin_url('admin-ajax.php'); ?>">
                        <?php wp_nonce_field('ed_callback'); ?>
                        <input type="hidden" name="title" value="Залишилися питання">

                        <div class="form-row">
                            <input class="form-row__input required" type="text" name="name"
                                   placeholder="<?php the_field('common_form_data_plejsholder_v_pol_mya', 'options'); ?>">
                        </div>

                        <div class="form-row">
                            <input class="form-row__input required-phone" type="text" name="phone"
                                   placeholder="Введіть номер телефону">
                        </div>

                        <div class="form-row">
                            <?php 
                                $agreement_txt = get_field('common_form_data_tekst_poltiki', 'options');
                                $agreement_link = get_field('common_form_data_tekst_poltiki_link', 'options');
                                $agreement_link_txt = get_field('common_form_data_tekst_poltiki_2', 'options');
                            ?>

                            <div class="form-row__agreement"><?= $agreement_txt; ?> <a href="<?= $agreement_link; ?>" target="_blank"><?= $agreement_link_txt; ?></a></div>
                        </div>

                        <div class="form-row form-row--button">
                            <button class="button"
                                    type="submit"><?php the_field('common_form_data_tekst_knopki_vdpravki_formi', 'options'); ?></button>
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
<?php get_footer(); ?>
