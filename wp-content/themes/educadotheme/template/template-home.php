<?php
/**
 * Template Name: Home
 */
?>

<?php get_header(); ?>
<main class="ed-home еуіе">
    <?php
    $data = get_fields();
    ?>
    <section class="ed-home-banner">
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title h1"><?= $data['section_1_zagolovok']; ?></h1>

                <div class="banner-subtitle h3"><?= $data['section_1_pdzagolovok']; ?></div>

                <div class="banner-languages h4">
                    <?php
                    if ($sec_1_lang = $data['section_1_spisok_mov']) {
                        foreach ($sec_1_lang as $item) { ?>
                            <div class="banner-languages__row">
                                <?php
                                if ($languages = $item['ryadok']) {
                                    $languages_count = count($languages);
                                    $i = 1;
                                    foreach ($languages as $language) { ?>
                                        <div class="banner-languages__item"><?= $language['mova']; ?></div>

                                        <div class="banner-languages__icon">
                                            <svg><use xlink:href="#ed-svg-half<?= $i % 2 == 0 ? 2 : 1; ?>"></use></svg>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <?php
            $sec_1_txt_right = $data['section_1_tekst_pravoruch'];
            ?>
            <div class="banner-world">
                <div class="banner-world__content">
                    <div class="banner-world__pretitle"><?= $sec_1_txt_right['ryadok_1']; ?></div>
                    <div class="banner-world__title"><?= $sec_1_txt_right['ryadok_2']; ?></div>

                    <div class="banner-world__pretitle"><?= $sec_1_txt_right['ryadok_3']; ?></div>
                    <div class="banner-world__title"><?= $sec_1_txt_right['ryadok_4']; ?></div>
                </div>

                <div class="banner-world__image">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?php echo get_template_directory_uri(); ?>/assets/img/world.svg" alt="icon">
                </div>
            </div>
        </div>
    </section>


    <section class="ed-home-look">
        <div class="container">
            <div class="look-title">
                <?php
                $sec_2_title = $data['how_stady_zagolovok'];
                ?>
                <div class="look-title__row">
                    <span><?= $sec_2_title['chastina_1']; ?></span>
                </div>

                <div class="look-title__row">
                    <span><?= $sec_2_title['chastina_2']; ?></span>

                    <!-- <div class="look-title__media">
                        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                             data-src="<?= $data['how_stady_zobrazhennya_na_zagolovku_1']; ?>"
                             decoding="async" alt="icon">
                    </div>

                    <span><?= $sec_2_title['chastina_3']; ?></span>

                    <div class="look-title__media">
                        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                             data-src="<?= $data['how_stady_zobrazhennya_na_zagolovku_2']; ?>"
                             decoding="async" alt="icon">
                    </div> -->

                    <span><?= $sec_2_title['chastina_4']; ?></span>
                </div>
            </div>

            <div class="look-subtitle h2"><?= $data['how_stady_pdzagolovok']; ?></div>

            <div class="look-button">
                <div class="button callpopup" data-popup="callback"><?= $data['how_stady_tekst_knopki']; ?></div>
            </div>

            <div class="look-benefits">
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


    <section class="ed-home-whyus">
        <div class="container">
            <div class="whyus-content">
                <div class="whyus-head">
                    <div class="whyus-head__title h1"><?= $data['why_stady_zagolovok']; ?></div>
                    <div class="whyus-head__subtitle h3"><?= $data['why_stady_pdzagolovok']; ?></div>
                </div>

                <div class="whyus-list">
                    <?php
                    if ($sec_3_list = $data['why_stady_spisok']) {
                        foreach ($sec_3_list as $item) { ?>
                            <div class="whyus-list__item">
                                <div class="feature-title h3"><?= $item['zagolovok']; ?></div>
                                <div class="feature-text"><?= $item['tekst']; ?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <div class="whyus-try">
                        <div class="whyus-try__button callpopup" data-popup="callback">
                            <span><?= $data['why_stady_tekst_knopki']; ?></span>
                            <svg viewBox="0 0 188 198" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.3786 174.642C-11.7504 89.8565 26.0552 40.2692 70.2724 13.4833C92.1599 0.422404 115.816 -2.23406 136.819 5.95669C158.265 14.3688 174.625 33.6281 181.921 58.6431C192.312 94.2839 185.237 133.688 163.792 161.359C145.442 184.825 118.469 197.222 88.4014 196.115C50.1536 193.901 28.4872 185.268 6.3786 174.642Z"
                                      stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"/>
                            </svg>
                            <div class="whyus-try__button-line">
                                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                     data-src="<?php echo get_template_directory_uri(); ?>/assets/img/why-us-line.svg"
                                     alt="icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="ed-home-founder">
        <div class="container">
            <div class="founder-content">
                <div class="founder-media">
                    <div class="founder-media__image">
                        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                             data-src="<?= $data['owner_foto']; ?>"
                             decoding="async" alt="<?= $data['owner_mya']; ?>">
                    </div>

                    <div class="founder-media__avocado">
                        <svg viewBox="0 0 568 810" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M513.967 364.638C505.399 348.234 496.544 331.272 489.133 314.738C476.538 286.635 470.507 255.777 464.118 223.098C455.627 179.671 446.842 134.77 421.633 93.3749C399.619 57.2367 369.976 29.1978 335.912 12.3004C319.253 4.04257 301.325 -0.0980821 282.623 0.0017634C282.129 0.0017634 281.636 0.00763667 281.143 0.0193832C280.65 0.0193832 280.156 0.0193832 279.663 0.0193832C260.96 0.119229 243.08 4.44783 226.514 12.8877C192.632 30.1551 163.289 58.5054 141.668 94.8785C116.899 136.543 108.602 181.539 100.581 225.059C94.5443 257.803 88.8425 288.726 76.5521 316.958C69.3177 333.574 60.6387 350.636 52.2474 367.14C26.3983 417.961 -0.3316 470.509 0.0031112 533.13C0.33195 594.5 21.3365 654.824 59.1471 702.991C96.4997 750.576 149.143 785.152 207.371 800.352C232.434 806.895 258.653 810.137 285.3 809.996H285.7C312.347 809.849 338.525 806.325 363.517 799.512C421.581 783.689 473.842 748.55 510.684 700.559C547.972 651.993 568.325 591.445 567.996 530.07C567.661 467.455 540.368 415.195 513.961 364.638H513.967Z"
                                  fill="#E6C7B1"/>
                        </svg>
                    </div>
                    <div class="founder-media__line">
                        <svg viewBox="0 0 855 897" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M210.8 661.8C207.1 646.1 206.2 636.6 206.2 636.6C203.2 613.3 203.5 589.1 207.1 564.8C217.6 496.9 253.3 436.7 287.9 378.5L288.9 376.8C314.2 334.3 340.5 290.8 356.7 244.1C359.8 235.3 362.5 226.5 365.6 217.7C381.9 166.8 399 114.5 444 81.8C493.4 46.5 570.7 52.9 613.3 96.3C648.5 132 658.6 196 666.4 247.2C668 256.4 669.2 265.2 670.5 273.3C678.3 316.8 695.8 359.4 712.8 400.2C737.1 458.6 761.8 518.8 760.3 585.5C758.3 674.1 712.8 776.1 615.6 803.9C571.8 816.4 522 813.1 475.4 795.3C432.1 778.5 402.1 751.4 371.1 715.8C326.4 708.5 260.4 689.6 210.8 661.8ZM210.8 661.8C137.1 624.7 38.5 556.2 -26.5 506"
                                  stroke="#161616" stroke-width="2" stroke-miterlimit="10" stroke-linecap="square"/>
                            <path d="M774 0C774 19 778.8 52.7 784.3 85.5C787.9 106.9 813.5 266.4 754 271.3C694.5 276.3 862.8 540.2 852.9 667.8C840 765.7 735.2 852.8 666.9 877C621 893.1 570.5 898.8 520.1 894.6C466.1 889.8 411.8 873.6 362.4 846.5C284 803.3 229.2 736.3 210.8 662.2C210.8 662.1 210.7 662 210.7 661.8"
                                  stroke="#161616" stroke-width="2"/>
                        </svg>
                    </div>
                </div>

                <div class="founder-info">
                    <div class="founder-info__title h1"><?= $data['owner_mya']; ?></div>

                    <div class="founder-info__socials ed-socials">
                        <?php $owner_soc_links = $data['owner_soc_merezh']; ?>

                        <?php if ($owner_soc_links['instagram']) : ?>
                            <a href="<?= $owner_soc_links['instagram']; ?>" class="ed-socials__link" target="_blank">
                                <svg>
                                    <use xlink:href="#ed-svg-instagram"></use>
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php if ($owner_soc_links['facebook']) : ?>
                            <a href="<?= $owner_soc_links['facebook']; ?>" class="ed-socials__link" target="_blank">
                                <svg>
                                    <use xlink:href="#ed-svg-facebook"></use>
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php if ($owner_soc_links['linkedin']) : ?>
                            <a href="<?= $owner_soc_links['linkedin']; ?>" class="ed-socials__link" target="_blank">
                                <svg>
                                    <use xlink:href="#ed-svg-linked"></use>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if ($data['owner_tekst_1']) : ?>
                        <div class="founder-info__description h3"><?= $data['owner_tekst_1']; ?></div>
                    <?php endif; ?>

                    <?php if ($data['owner_tekst_2']) : ?>
                        <div class="founder-info__text">
                            <?= $data['owner_tekst_2']; ?>
                        </div>
                    <?php endif; ?>                    
                </div>

                <div class="founder-footer">
                    <?php if ($data['owner_tekst_3']) : ?>
                        <div class="h3"><?= $data['owner_tekst_3']; ?></div>
                    <?php endif; ?>

                    <div class="founder-info__button">
                        <div class="button callpopup" data-popup="trial"><?= $data['owner_tekst_knopki']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="ed-home-appointment">
        <div class="container">
            <div class="appointment-title h1"><?= $data['help_speak_zagolovok']; ?></div>

            <div class="appointment-list">
                <?php
                if ($help_cards = $data['help_speak_kartki']) {
                    foreach ($help_cards as $item) { ?>
                        <div class="appointment-item">
                            <div class="appointment-item__icon">
                                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                     data-src="<?= $item['konka']; ?>"
                                     decoding="async" alt="icon">
                            </div>

                            <div class="appointment-item__title h3"><?= $item['zagolovok']; ?></div>
                            <div class="appointment-item__text"><?= $item['tekst']; ?></div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <div class="appointment-footer h2">
                <?= $data['help_speak_tekst_vnizu']; ?>
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


    <section class="ed-home-courses">
        <div class="courses-banner">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/avocado-fly.svg"
                 decoding="async" alt="icon">
        </div>

        <div class="container">
            <div class="courses-title h1"><?= $data['courses_zagolovok']; ?></div>

            <div class="courses-features">
                <div class="courses-features__item">
                    <div class="button callpopup" data-popup="callback"><?= $data['courses_tekst_knopki']; ?></div>
                </div>

                <?php
                if ($courses_list = $data['courses_spisok']) {
                    foreach ($courses_list as $item) { ?>
                        <div class="courses-features__item">
                            <div class="feature">
                                <div class="feature__icon">
                                    <svg>
                                        <use xlink:href="#ed-svg-check"></use>
                                    </svg>
                                </div>

                                <div class="feature__title h4"><?= $item['tekst']; ?></div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <?php if ($courses_list = $data['courses_kursi']) : ?>
                <div class="ed-accordions" id="ed-courses">
                    <?php
                    $course_card = $data['courses_kartka_kursa'];
                    $courses_count = count($courses_list);
                    $i = 1;
                    foreach ($courses_list as $item_id) {
                        get_template_part('parts/pt-cards/content', 'course-card', array(
                            'id' => $item_id,
                            'data' => $course_card,
                            'count' => $courses_count,
                            'counter' => $i,
                        ));
                        $i++;
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>


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


    <section class="ed-home-classes">
        <div class="container">
            <div class="classes-content">
                <div class="classes-title h1 classes-content__item"><?= $data['lessons_type_zagolovok']; ?></div>

                <div class="classes-image classes-content__item">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?php echo get_template_directory_uri(); ?>/assets/img/classes.svg"
                         decoding="async" alt="icon">
                </div>

                <div class="classes-content__item">
                    <?php
                    $lessons_type_card_1 = $data['lessons_type_kartka_1'];
                    ?>
                    <div class="classes-block">
                        <div class="classes-block__title h3"><?= $lessons_type_card_1['zagolovok']; ?></div>

                        <div class="classes-block__list h5">
                            <?php
                            if ($lessons_type_card_1['spisok']) {
                                foreach ($lessons_type_card_1['spisok'] as $item) { ?>
                                    <div class="classes-block__list-item">
                                        <svg>
                                            <use xlink:href="#ed-svg-check"></use>
                                        </svg>
                                        <span><?= $item['tekst']; ?></span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>

                        <div class="button callpopup"
                             data-popup="callback"><?= $lessons_type_card_1['tekst_knopki']; ?></div>
                    </div>
                </div>

                <div class="classes-content__item">
                    <?php
                    $lessons_type_card_2 = $data['lessons_type_kartka_2'];
                    ?>
                    <div class="classes-block">
                        <div class="classes-block__title h3"><?= $lessons_type_card_2['zagolovok']; ?></div>

                        <div class="classes-block__list h5">
                            <?php
                            if ($lessons_type_card_2['spisok']) {
                                foreach ($lessons_type_card_2['spisok'] as $item) { ?>
                                    <div class="classes-block__list-item">
                                        <svg>
                                            <use xlink:href="#ed-svg-check"></use>
                                        </svg>
                                        <span><?= $item['tekst']; ?></span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>

                        <div class="button callpopup"
                             data-popup="callback"><?= $lessons_type_card_2['tekst_knopki']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="ed-home-guarantees">
        <div class="container">
            <div class="guarantees-content">
                <div class="h1 guarantees-title"><?= $data['guarantees_zagolovok']; ?></div>
                <div class="guarantees-subtitle h4"><?= $data['guarantees_tekst']; ?></div>

                <div class="guarantees-conditions h4">
                    <?php
                    if ($data['guarantees_spisok']) {
                        foreach ($data['guarantees_spisok'] as $item) { ?>
                            <div class="condition">
                                <div class="condition-icon">
                                    <svg>
                                        <use xlink:href="#ed-svg-condition"></use>
                                    </svg>
                                </div>
                                <div class="condition-title"><?= $item['tekst']; ?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>

                <div class="guarantees-footer h3"><?= $data['guarantees_tekst_unizu']; ?></div>

                <div class="guarantees-bg">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?php echo get_template_directory_uri(); ?>/assets/img/guarantees-bg.svg"
                         decoding="async" alt="icon">
                </div>
            </div>
        </div>
    </section>


    <section class="ed-home-clubs">
        <div class="container">
            <div class="classes-content">
                <div class="classes-title h1 classes-content__item"><?= $data['speak_club_zagolovok']; ?></div>
                <div class="clubs-text classes-content__item"><?= $data['speak_club_tekst']; ?></div>

                <div class="classes-content__item">
                    <?php
                    $speak_club_card_1 = $data['speak_club_kartka_1'];
                    ?>
                    <div class="classes-block">
                        <div class="classes-block__title h3"><?= $speak_club_card_1['zagolovok']; ?></div>
                        <?php if ($speak_club_card_1['tekst']) : ?>
                            <div class="classes-block__subtitle h4"><?= $speak_club_card_1['tekst']; ?></div>
                        <?php endif; ?>

                        <div class="classes-block__list h5">
                            <?php
                            if ($speak_club_card_1['spisok']) {
                                foreach ($speak_club_card_1['spisok'] as $item) { ?>
                                    <div class="classes-block__list-item">
                                        <svg>
                                            <use xlink:href="#ed-svg-check"></use>
                                        </svg>
                                        <span><?= $item['tekst']; ?></span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>

                        <div class="button callpopup"
                             data-popup="callback"><?= $speak_club_card_1['tekst_knopki']; ?></div>
                    </div>
                </div>

                <div class="classes-content__item">
                    <?php
                    $speak_club_card_2 = $data['speak_club_kartka_2'];
                    ?>
                    <div class="classes-block">
                        <div class="classes-block__title h3"><?= $speak_club_card_2['zagolovok']; ?></div>
                        <?php if ($speak_club_card_2['tekst']) : ?>
                            <div class="classes-block__subtitle h4"><?= $speak_club_card_2['tekst']; ?></div>
                        <?php endif; ?>

                        <div class="classes-block__list h5">
                            <?php
                            if ($speak_club_card_2['spisok']) {
                                foreach ($speak_club_card_2['spisok'] as $item) { ?>
                                    <div class="classes-block__list-item">
                                        <svg>
                                            <use xlink:href="#ed-svg-check"></use>
                                        </svg>
                                        <span><?= $item['tekst']; ?></span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>

                        <div class="button callpopup"
                             data-popup="callback"><?= $speak_club_card_2['tekst_knopki'];; ?></div>
                    </div>
                </div>

                <div class="classes-image classes-content__item">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?php echo get_template_directory_uri(); ?>/assets/img/clubs.svg"
                         decoding="async" alt="icon">
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


            <div class="about-reviews" id="ed-testimonials">
                <div class="about-reviews-title h1"><?= $data['what_say_zagolovok']; ?></div>


                <?php
                /** Reviews block **/
                get_template_part('parts/content', 'reviews');
                ?>
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
