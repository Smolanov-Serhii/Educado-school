<?php
/**
 * Template Name: Тільки банер
 */
?>
<?php get_header(); ?>
<main class="ed-home еуіе">
    <?php
    $data = get_fields();
    ?>
    <section class="ed-home-banner ed-home-banner-static">
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title h1"><?= $data['section_1_zagolovok']; ?></h1>
                <div class="banner-subtitle h3">
                    <svg width="60" height="70" viewBox="0 0 60 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M51.5706 31.6454C43.42 20.2436 45.8237 15.4021 42.0863 6.18706C38.5794 -2.46402 29.8195 1.43857 28.7586 1.94851C27.7008 1.43557 18.947 -2.47302 15.4279 6.17206C11.6784 15.3811 14.076 20.2256 5.91021 31.6184C-2.25253 43.0082 -1.31592 56.0568 7.61672 63.814C15.7007 70.8332 27.4886 70.0203 28.7162 69.9153C29.9407 70.0233 41.7287 70.8512 49.8217 63.841C58.7634 56.0958 59.7182 43.0502 51.5706 31.6484V31.6454Z" fill="#D3D360"></path>
                        <path d="M51.2886 23.9675L47.904 31.5707H58.5975V32.9933H47.3153L41.1837 46.8753H39.614L45.7456 32.9933H36.6218L30.4901 46.8753H28.9695L35.0521 32.9933H27.1055V31.5707H35.6898L38.9763 23.9675H27.1055V22.545H39.6631L45.0589 10.1836H46.6777L41.2818 22.545H50.3566L55.7525 10.1836H57.3712L51.9754 22.545H58.5975V23.9675H51.2886ZM49.7189 23.9675H40.5951L37.2595 31.5707H46.3343L49.7189 23.9675Z" fill="#161616"></path>
                        <path d="M51.9767 22.5451L57.3726 10.1833H55.7535L50.3582 22.5451H41.2833L46.6792 10.1833H45.0601L39.6641 22.5451H27.1066V23.9671H38.9777L35.6909 31.5708H27.1066V32.9935H35.0531L28.9707 46.8752H30.4913L36.6229 32.9935H45.7472L39.6155 46.8752H41.1847L47.3164 32.9935H58.599V31.5708H47.9056L51.2896 23.9671H58.599V22.5451H51.9767ZM49.7204 23.9671L46.3357 31.5708H37.2607L40.5962 23.9671H49.7204ZM39.4061 30.1692H45.4255L47.5627 25.3686H41.5125L39.4061 30.1692ZM60.0005 25.3686H52.1998L50.0633 30.1692H60.0005V34.395H48.2299L42.0983 48.2767H37.464L43.5956 34.395H37.5365L31.4042 48.2767H26.8267L32.9091 34.395H25.7051V30.1692H34.7698L36.8454 25.3686H25.7051V21.1436H38.7464L44.1424 8.78174H48.8198L43.4239 21.1436H49.4405L54.8365 8.78174H59.5132L54.118 21.1436H60.0005V25.3686Z" fill="#161616"></path>
                    </svg>
                    <?= $data['section_1_pdzagolovok']; ?>
                </div>
                <div class="banner-languages__buttons">
                    <?php
                    if ($buttons = $data['perelk_knopok']) {
                        foreach ($buttons as $button) { ?>
                                <a href="<?= $button['posilannya_na_stornku']; ?>" class="button button-green"><?= $button['tekst_na_knopc']; ?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="banner-world">
                <div class="banner-world__image-static">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?= $data['kartinka_v_baner']; ?>" alt="icon">
                </div>
            </div>
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
