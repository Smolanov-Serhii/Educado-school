<?php
/**
 * Popups
 */

$placeholder_name = get_field('common_form_data_plejsholder_v_pol_mya', 'options');
$agreement_txt = get_field('common_form_data_tekst_poltiki', 'options');
$agreement_link = get_field('common_form_data_tekst_poltiki_link', 'options');
$agreement_link_txt = get_field('common_form_data_tekst_poltiki_2', 'options');
$send_btn_txt = get_field('common_form_data_tekst_knopki_vdpravki_formi', 'options');
?>
<div class="popup" id="callback">
    <div class="popup-body">
        <div class="popup-close">
            <span></span>
            <span></span>
        </div>

        <div class="popup-head">
            <div class="popup-head__title h2"><?php the_field('popup_request_zagolovok', 'options'); ?></div>
            <div class="popup-head__subtitle"><?php the_field('popup_request_pdzagolovok', 'options'); ?></div>
        </div>

        <form class="form">
            <input type="hidden" name="action" value="ed_callback">
            <input type="hidden" name="url" value="<?php echo admin_url('admin-ajax.php'); ?>">
            <?php wp_nonce_field('ed_callback'); ?>
            <input type="hidden" name="title" value="Зворотній зв`язок">

            <div class="form-row">
                <input class="form-row__input required" type="text" name="name" placeholder="<?= $placeholder_name; ?>">
            </div>

            <div class="form-row form-row--title">Зручний спосіб комунікації (один або декілька):</div>

            <div class="form-row">
                <div class="form-row__icon">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="24" r="21.3333" stroke="#32ACDF" stroke-width="2"/>
                        <path d="M32.8627 16L9.33331 24.3333L16.056 27.6666L27.3333 22L20.6666 30L29.5014 36L32.8627 16Z" stroke="#32ACDF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <input class="form-row__input required-phone" type="text" name="telegram" placeholder="Введіть номер телефону">
            </div>     
            
            
            <div class="form-row">
                <div class="form-row__icon">
                    <svg><use xlink:href="#ed-svg-viber"></use></svg>
                </div>

                <input class="form-row__input required-phone" type="text" name="viber" placeholder="Введіть номер телефону">
            </div>     


            <div class="form-row">
                <div class="form-row__icon">
                    <svg><use xlink:href="#ed-svg-whatsapp"></use></svg>
                </div>

                <input class="form-row__input required-phone" type="text" name="whatsapp" placeholder="Введіть номер телефону">
            </div>     


            <div class="form-row">
                <div class="form-row__agreement"><?= $agreement_txt; ?> <a href="<?= $agreement_link; ?>" target="_blank"><?= $agreement_link_txt; ?></a></div>
            </div>

            <div class="form-row form-row--button">
                <button class="button" type="submit"><?= $send_btn_txt; ?> </button>
            </div>
        </form>
    </div>
    <div class="popup-bg"></div>
</div>


<div class="popup" id="trial">
    <div class="popup-body">
        <div class="popup-close">
            <span></span>
            <span></span>
        </div>

        <div class="popup-head">
            <div class="popup-head__title h2"><?php the_field('popup_test_zagolovok', 'options'); ?></div>
            <div class="popup-head__subtitle"><?php the_field('popup_test_pdzagolovok', 'options'); ?></div>
        </div>

        <form class="form">
            <input type="hidden" name="action" value="ed_callback">
            <input type="hidden" name="url" value="<?php echo admin_url('admin-ajax.php'); ?>">
            <?php wp_nonce_field('ed_callback'); ?>
            <input type="hidden" name="title" value="Записатися на пробний урок">

            <div class="form-row">
                <input class="form-row__input required" type="text" name="name" placeholder="<?= $placeholder_name; ?>">
            </div>

             <div class="form-row form-row--title">Зручний спосіб комунікації (один або декілька):</div>

            <div class="form-row">
                <div class="form-row__icon">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="24" r="21.3333" stroke="#32ACDF" stroke-width="2"/>
                        <path d="M32.8627 16L9.33331 24.3333L16.056 27.6666L27.3333 22L20.6666 30L29.5014 36L32.8627 16Z" stroke="#32ACDF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <input class="form-row__input required-phone" type="text" name="telegram" placeholder="Введіть номер телефону">
            </div>     
            
            
            <div class="form-row">
                <div class="form-row__icon">
                    <svg><use xlink:href="#ed-svg-viber"></use></svg>
                </div>

                <input class="form-row__input required-phone" type="text" name="viber" placeholder="Введіть номер телефону">
            </div>     


            <div class="form-row">
                <div class="form-row__icon">
                    <svg><use xlink:href="#ed-svg-whatsapp"></use></svg>
                </div>

                <input class="form-row__input required-phone" type="text" name="whatsapp" placeholder="Введіть номер телефону">
            </div>     


            <div class="form-row">
                <div class="form-row__agreement"><?= $agreement_txt; ?> <a href="<?= $agreement_link; ?>" target="_blank"><?= $agreement_link_txt; ?></a></div>
            </div>

            <div class="form-row form-row--button">
                <button class="button" type="submit"><?= $send_btn_txt; ?></button>
            </div>
        </form>
    </div>
    <div class="popup-bg"></div>
</div>


<div class="popup" id="youtube">
    <div class="popup-body">
        <div class="popup-close">
            <span></span>
            <span></span>
        </div>

        <div class="popup-iframe"></div>
    </div>
    <div class="popup-bg"></div>
</div>


<div class="popup" id="success-popup">
    <div class="popup-body">
        <div class="popup-close">
            <span></span>
            <span></span>
        </div>

        <div class="popup-icon">
            <svg><use xlink:href="#ed-svg-success"></use></svg>    
        </div>

        <div class="popup-head">
            <div class="popup-head__title h2"><?php the_field('popup_thanks_zagolovok', 'options'); ?></div>
            <div class="popup-head__subtitle"><?php the_field('popup_thanks_pdzagolovok', 'options'); ?></div>
            <div class="button popup-head__close">Повернутися на сайт</div>
        </div>
    </div>
    <div class="popup-bg"></div>
</div>
