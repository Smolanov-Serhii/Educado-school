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
                    <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 31.9997C10.3908 32.0069 9.28706 31.8732 8.21093 31.6051C7.10776 31.3311 6.04606 30.9186 5.04023 30.3926C2.59415 29.1133 0.8655 27.105 0.250227 24.4108C-0.432343 21.4217 0.369195 18.2207 1.59614 15.4781L1.59794 15.4739L1.99811 14.5904C3.21183 11.9135 4.3174 9.42106 4.51028 6.4409C4.54032 5.97525 4.60701 5.51258 4.71517 5.05828C4.81731 4.62904 4.95611 4.20817 5.13757 3.8052C5.66511 2.6351 6.52974 1.68888 7.63351 1.0304C8.78054 0.347447 10.1204 0.00776084 11.4537 0H11.4844L11.4994 0.000596987H11.5144L11.5445 0C12.8784 0.00716385 14.2189 0.34685 15.3659 1.031C16.4697 1.68947 17.3349 2.6357 17.8618 3.8058C18.0433 4.20876 18.1827 4.62964 18.2848 5.05887C18.393 5.51258 18.4591 5.97585 18.4897 6.4415C18.6826 9.42166 19.7876 11.9141 21.0019 14.5916L21.4039 15.4787C22.6308 18.2207 23.4323 21.4217 22.7498 24.4108C22.1345 27.105 20.4064 29.1133 17.9604 30.3926C16.9545 30.9186 15.8928 31.3311 14.7897 31.6051C13.7135 31.8726 12.6104 32.0063 11.5006 31.9997H11.5Z" fill="#D3D360"/>
                        <path d="M15.8125 28.5938L20.7871 3H23.3184L18.3262 28.5938H15.8125ZM22.9668 28.5938L27.959 3H30.4727L25.4805 28.5938H22.9668ZM32.6172 12.7559H14.3359V10.3125H32.6172V12.7559ZM31.2988 21.3867H13V18.9609H31.2988V21.3867Z" fill="#161616"/>
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
<!--    <section class="ed-home-questions">-->
<!--        <div class="container">-->
<!--            <div class="questions-wrapper">-->
<!--                <div class="questions-form">-->
<!--                    <div class="questions-title h1">--><?php //= $data['questions_zagolovok']; ?><!--</div>-->
<!--                    <div class="questions-subtitle h3">--><?php //= $data['questions_pdzagolovok']; ?><!--</div>-->
<!---->
<!--                    <form class="form">-->
<!--                        <input type="hidden" name="action" value="ed_callback">-->
<!--                        <input type="hidden" name="url" value="--><?php //echo admin_url('admin-ajax.php'); ?><!--">-->
<!--                        --><?php //wp_nonce_field('ed_callback'); ?>
<!--                        <input type="hidden" name="title" value="Залишилися питання">-->
<!---->
<!--                        <div class="form-row">-->
<!--                            <input class="form-row__input required" type="text" name="name"-->
<!--                                   placeholder="--><?php //the_field('common_form_data_plejsholder_v_pol_mya', 'options'); ?><!--">-->
<!--                        </div>-->
<!---->
<!--                        <div class="form-row">-->
<!--                            <input class="form-row__input required-phone" type="text" name="phone"-->
<!--                                   placeholder="Введіть номер телефону">-->
<!--                        </div>-->
<!---->
<!--                        <div class="form-row">-->
<!--                            --><?php
//                                $agreement_txt = get_field('common_form_data_tekst_poltiki', 'options');
//                                $agreement_link = get_field('common_form_data_tekst_poltiki_link', 'options');
//                                $agreement_link_txt = get_field('common_form_data_tekst_poltiki_2', 'options');
//                            ?>
<!---->
<!--                            <div class="form-row__agreement">--><?php //= $agreement_txt; ?><!-- <a href="--><?php //= $agreement_link; ?><!--" target="_blank">--><?php //= $agreement_link_txt; ?><!--</a></div>-->
<!--                        </div>-->
<!---->
<!--                        <div class="form-row form-row--button">-->
<!--                            <button class="button"-->
<!--                                    type="submit">--><?php //the_field('common_form_data_tekst_knopki_vdpravki_formi', 'options'); ?><!--</button>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                </div>-->
<!---->
<!--                <div class="questions-trial">-->
<!--                    <div class="questions-trial__button callpopup" data-popup="trial">-->
<!--                        <span>--><?php //= $data['questions_tekst_knopki']; ?><!--</span>-->
<!---->
<!--                        <svg viewBox="0 0 188 198" xmlns="http://www.w3.org/2000/svg">-->
<!--                            <path d="M6.3786 174.642C-11.7504 89.8565 26.0552 40.2692 70.2724 13.4833C92.1599 0.422404 115.816 -2.23406 136.819 5.95669C158.265 14.3688 174.625 33.6281 181.921 58.6431C192.312 94.2839 185.237 133.688 163.792 161.359C145.442 184.825 118.469 197.222 88.4014 196.115C50.1536 193.901 28.4872 185.268 6.3786 174.642Z"-->
<!--                                  stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"/>-->
<!--                        </svg>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--        </div>-->
<!--    </section>-->
</main>
<?php get_footer(); ?>
