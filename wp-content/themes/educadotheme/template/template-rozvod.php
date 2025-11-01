<?php
/**
 * Template Name: Шаблон розвідна
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
                <div class="button callpopup" data-popup="trial">
                    <?php
                    if ($data['tekst_knopki_banera']){
                        echo $data['tekst_knopki_banera'];
                    } else {
                        echo get_field('tekst_zapisatisya', 'option');
                    }
                    ?>
                </div>
            </div>
            <div class="banner-world">
                <div class="banner-world__image">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?= $data['kartinka_v_pershij_blok']; ?>" alt="<?= $data['section_1_zagolovok']; ?>">
                </div>
            </div>
        </div>
    </section>


    <section class="ed-home-look ed-home-look-language">
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
    <section class="ed-home-chield">
        <div class="ed-home-chield__top">
            <div class="container">
                <div class="ed-home-chield__title h1">
                    <svg width="60" height="70" viewBox="0 0 60 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M51.5706 31.6454C43.42 20.2436 45.8237 15.4021 42.0863 6.18706C38.5794 -2.46402 29.8195 1.43857 28.7586 1.94851C27.7008 1.43557 18.947 -2.47302 15.4279 6.17206C11.6784 15.3811 14.076 20.2256 5.91021 31.6184C-2.25253 43.0082 -1.31592 56.0568 7.61672 63.814C15.7007 70.8332 27.4886 70.0203 28.7162 69.9153C29.9407 70.0233 41.7287 70.8512 49.8217 63.841C58.7634 56.0958 59.7182 43.0502 51.5706 31.6484V31.6454Z"
                              fill="#D3D360"/>
                        <path d="M51.2886 23.9675L47.904 31.5707H58.5975V32.9933H47.3153L41.1837 46.8753H39.614L45.7456 32.9933H36.6218L30.4901 46.8753H28.9695L35.0521 32.9933H27.1055V31.5707H35.6898L38.9763 23.9675H27.1055V22.545H39.6631L45.0589 10.1836H46.6777L41.2818 22.545H50.3566L55.7525 10.1836H57.3712L51.9754 22.545H58.5975V23.9675H51.2886ZM49.7189 23.9675H40.5951L37.2595 31.5707H46.3343L49.7189 23.9675Z"
                              fill="#161616"/>
                        <path d="M51.9767 22.5451L57.3726 10.1833H55.7535L50.3582 22.5451H41.2833L46.6792 10.1833H45.0601L39.6641 22.5451H27.1066V23.9671H38.9777L35.6909 31.5708H27.1066V32.9935H35.0531L28.9707 46.8752H30.4913L36.6229 32.9935H45.7472L39.6155 46.8752H41.1847L47.3164 32.9935H58.599V31.5708H47.9056L51.2896 23.9671H58.599V22.5451H51.9767ZM49.7204 23.9671L46.3357 31.5708H37.2607L40.5962 23.9671H49.7204ZM39.4061 30.1692H45.4255L47.5627 25.3686H41.5125L39.4061 30.1692ZM60.0005 25.3686H52.1998L50.0633 30.1692H60.0005V34.395H48.2299L42.0983 48.2767H37.464L43.5956 34.395H37.5365L31.4042 48.2767H26.8267L32.9091 34.395H25.7051V30.1692H34.7698L36.8454 25.3686H25.7051V21.1436H38.7464L44.1424 8.78174H48.8198L43.4239 21.1436H49.4405L54.8365 8.78174H59.5132L54.118 21.1436H60.0005V25.3686Z"
                              fill="#161616"/>
                    </svg>
                    <?= $data['zagolovok_bloku_rozvdnih_stornok']; ?>
                </div>
                <div class="ed-home-chield__subtitle benefit-info__title h5">
                    <?= $data['pdzagolovok_bloku_rozvdnih_stornok']; ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="ed-home-chield__list">
                <?php
                if ($chield = $data['elementi_bloku_rozvdnih_stornok']) {
                    foreach ($chield as $item) { ?>
                        <div class="ed-home-chield__item">
                            <a href="<?= $item['posilannya_na_stornku']; ?>" class="ed-home-chield__item-title h5 button"><?= $item['nazva_elementu']; ?></a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="ed-home-chield__img">
                <div class="ed-home-chield__bg">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?= $data['zobrazhennya_bloku_rozvdnih_stornok']; ?>"
                         decoding="async" alt="icon">
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

    <section class="ed-home-about">
        <div class="container">
            <div class="about-teachers" id="ed-teachers">
                <div class="about-teachers-title h1"><?= $data['teachers_zagolovok']; ?></div>

                <?php if ($teachers_list = $data['teachers_vikladach']) : ?>
                    <div class="about-teachers-slider swiper">
                        <div class="swiper-wrapper">
                            <?php
                            foreach ($teachers_list as $item) {
                                get_template_part('parts/pt-cards/content', 'teacher-card-language', array(
                                    'id' => $item,
                                ));
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="about-reviews" id="ed-testimonials">
                <div class="about-reviews-title h1"><?= get_field('what_say_zagolovok', 5); ?></div>
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

    <section class="ed-home-faq">
        <div class="container">
            <div class="faq-title h1">
                <svg width="60" height="70" viewBox="0 0 60 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M51.5706 31.6454C43.42 20.2436 45.8237 15.4021 42.0863 6.18706C38.5794 -2.46402 29.8195 1.43857 28.7586 1.94851C27.7008 1.43557 18.947 -2.47302 15.4279 6.17206C11.6784 15.3811 14.076 20.2256 5.91021 31.6184C-2.25253 43.0082 -1.31592 56.0568 7.61672 63.814C15.7007 70.8332 27.4886 70.0203 28.7162 69.9153C29.9407 70.0233 41.7287 70.8512 49.8217 63.841C58.7634 56.0958 59.7182 43.0502 51.5706 31.6484V31.6454Z"
                          fill="#D3D360"/>
                    <path d="M51.2886 23.9675L47.904 31.5707H58.5975V32.9933H47.3153L41.1837 46.8753H39.614L45.7456 32.9933H36.6218L30.4901 46.8753H28.9695L35.0521 32.9933H27.1055V31.5707H35.6898L38.9763 23.9675H27.1055V22.545H39.6631L45.0589 10.1836H46.6777L41.2818 22.545H50.3566L55.7525 10.1836H57.3712L51.9754 22.545H58.5975V23.9675H51.2886ZM49.7189 23.9675H40.5951L37.2595 31.5707H46.3343L49.7189 23.9675Z"
                          fill="#161616"/>
                    <path d="M51.9767 22.5451L57.3726 10.1833H55.7535L50.3582 22.5451H41.2833L46.6792 10.1833H45.0601L39.6641 22.5451H27.1066V23.9671H38.9777L35.6909 31.5708H27.1066V32.9935H35.0531L28.9707 46.8752H30.4913L36.6229 32.9935H45.7472L39.6155 46.8752H41.1847L47.3164 32.9935H58.599V31.5708H47.9056L51.2896 23.9671H58.599V22.5451H51.9767ZM49.7204 23.9671L46.3357 31.5708H37.2607L40.5962 23.9671H49.7204ZM39.4061 30.1692H45.4255L47.5627 25.3686H41.5125L39.4061 30.1692ZM60.0005 25.3686H52.1998L50.0633 30.1692H60.0005V34.395H48.2299L42.0983 48.2767H37.464L43.5956 34.395H37.5365L31.4042 48.2767H26.8267L32.9091 34.395H25.7051V30.1692H34.7698L36.8454 25.3686H25.7051V21.1436H38.7464L44.1424 8.78174H48.8198L43.4239 21.1436H49.4405L54.8365 8.78174H59.5132L54.118 21.1436H60.0005V25.3686Z"
                          fill="#161616"/>
                </svg>
                <?= $data['zaglovok_bloku_faq']; ?>
            </div>
            <div class="faq-list">
                <?php
                $faqlist = $data['pitannya_vdpovd_bloku'];
                foreach ($faqlist as $item) { ?>
                    <div class="faq-item">
                        <div class="faq-item__header">
                            <?= $item['pitannya']; ?>
                            <svg width="33" height="18" viewBox="0 0 33 18" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <mask id="path-1-inside-1_1911_16460" fill="white">
                                    <path d="M0.838108 0.928805C0.683771 0.774468 0.433541 0.774467 0.279202 0.928806C0.124865 1.08314 0.124866 1.33337 0.279203 1.48771L16.208 17.4165L32.1368 1.48771C32.2912 1.33337 32.2912 1.08314 32.1368 0.928806C31.9825 0.774467 31.7322 0.774468 31.5779 0.928805L16.208 16.2987L0.838108 0.928805Z"/>
                                </mask>
                                <path d="M0.838108 0.928805C0.683771 0.774468 0.433541 0.774467 0.279202 0.928806C0.124865 1.08314 0.124866 1.33337 0.279203 1.48771L16.208 17.4165L32.1368 1.48771C32.2912 1.33337 32.2912 1.08314 32.1368 0.928806C31.9825 0.774467 31.7322 0.774468 31.5779 0.928805L16.208 16.2987L0.838108 0.928805Z"
                                      fill="#858523"/>
                                <path d="M16.208 17.4165L23.2791 24.4876L16.208 31.5587L9.13694 24.4876L16.208 17.4165ZM16.208 16.2987L23.2791 23.3698L16.208 30.4408L9.13694 23.3698L16.208 16.2987ZM0.838108 0.928805L-6.23296 7.99987C-2.48205 11.7508 3.59936 11.7508 7.35027 7.99987L0.279202 0.928806L-6.79187 -6.14226C-2.73229 -10.2018 3.8496 -10.2018 7.90918 -6.14226L0.838108 0.928805ZM0.279202 0.928806L7.35027 7.99987C11.1012 4.24897 11.1012 -1.83245 7.35027 -5.58336L0.279203 1.48771L-6.79187 8.55878C-10.8514 4.4992 -10.8514 -2.08268 -6.79187 -6.14226L0.279202 0.928806ZM0.279203 1.48771L7.35027 -5.58336L23.2791 10.3454L16.208 17.4165L9.13694 24.4876L-6.79187 8.55878L0.279203 1.48771ZM16.208 17.4165L9.13694 10.3454L25.0657 -5.58336L32.1368 1.48771L39.2079 8.55878L23.2791 24.4876L16.208 17.4165ZM32.1368 1.48771L25.0657 -5.58336C21.3148 -1.83245 21.3148 4.24897 25.0657 7.99987L32.1368 0.928806L39.2079 -6.14226C43.2675 -2.08268 43.2675 4.4992 39.2079 8.55878L32.1368 1.48771ZM32.1368 0.928806L25.0657 7.99987C28.8167 11.7508 34.8981 11.7508 38.649 7.99987L31.5779 0.928805L24.5068 -6.14226C28.5664 -10.2018 35.1483 -10.2018 39.2079 -6.14226L32.1368 0.928806ZM31.5779 0.928805L38.649 7.99987L23.2791 23.3698L16.208 16.2987L9.13694 9.22764L24.5068 -6.14226L31.5779 0.928805ZM16.208 16.2987L9.13694 23.3698L-6.23296 7.99987L0.838108 0.928805L7.90918 -6.14226L23.2791 9.22764L16.208 16.2987Z"
                                      fill="#858523" mask="url(#path-1-inside-1_1911_16460)"/>
                            </svg>
                        </div>
                        <div class="faq-item__content">
                            <?= $item['vdpovd']; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>

    <section class="ed-home-questions ed-home-questions-language">
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
                                    type="submit"><?php the_field('common_form_data_tekst_knopki_vdpravki_formi', 'options'); ?></button>
                        </div>
                        <div class="" style="margin-top: 20px">
                            <?php
                            $agreement_txt = get_field('common_form_data_tekst_poltiki', 'options');
                            $agreement_link = get_field('common_form_data_tekst_poltiki_link', 'options');
                            $agreement_link_txt = get_field('common_form_data_tekst_poltiki_2', 'options');
                            ?>

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

<?php
    if ($data['vibr_tipu_tegchekboks'] == "checkbox") {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.tabs').forEach(initTabsGroup);

                function initTabsGroup(groupEl) {
                    const boxes = [...groupEl.querySelectorAll('.tabs-header input[type="checkbox"][data-tab]')];
                    const contents = [...groupEl.querySelectorAll('.tabs-content')];
                    if (!boxes.length || !contents.length) return;

                    const showById = (rawId) => {
                        const id = String(rawId).replace(/^#/, '');
                        contents.forEach(el => el.classList.toggle('is-active', el.id === id));
                    };

                    const activate = (cb) => {
                        boxes.forEach(b => b.checked = (b === cb));
                        showById(cb.dataset.tab);
                    };

                    // Делегирование на всю группу (учтёт клик и по label, и по самому input)
                    groupEl.addEventListener('click', (e) => {
                        const cb = e.target.closest('input[type="checkbox"][data-tab]');
                        if (!cb || !groupEl.contains(cb)) return;
                        // Если клик снимает галку — снова включаем (радио-поведение)
                        if (!cb.checked) cb.checked = true;
                        activate(cb);
                    });

                    // Инициализация: если ничего не отмечено — отметить первый
                    activate(boxes.find(b => b.checked) || boxes[0]);
                }
            });
        </script>


        <?php
    }
?>
<?php get_footer(); ?>
