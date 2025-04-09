<?php
/**
 * Course card
 */
?>

<?php
$item_id = $args['id'] ?? '';
$common_content = $args['data'] ?? [];
$count = $args['count'] ?? 0;
$counter = $args['counter'] ?? 0;

if ($item_id) {
    $add_class = '';
    if ($counter === $count) {
        $add_class = ' active';
    }
    $title = get_the_title($item_id);
    $add_title = get_field('dodatkovij_zagolovok', $item_id);
    ?>
    <div class="accordion<?= $add_class; ?>" id="accordion-<?= $counter; ?>">
        <div class="accordion-body">
            <div class="accordion-title h2">
                <?php if ($add_title) : ?>
                    <span><?= $add_title; ?></span>
                <?php endif; ?>
                <svg>
                    <use xlink:href="#ed-svg-half1"></use>
                </svg>
                <span><?= $title; ?></span>
            </div>

            <?php if ($tags = get_field('heshtegi', $item_id)) : ?>
                <div class="accordion-tags h5">
                    <?php foreach ($tags as $item) : ?>
                        <div class="accordion-tags__item">
                            <svg>
                                <use xlink:href="#ed-svg-tag"></use>
                            </svg>
                            <span><?= $item['tekst']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="accordion-block">
                <?php the_field('opis', $item_id); ?>
            </div>

            <?php if ($list = get_field('spisok', $item_id)) : ?>
                <div class="accordion-block">
                    <div class="h4"><?= $common_content['zagolovok_bloku_z_spiskom']; ?></div>

                    <div class="accordion-steps">
                        <?php foreach ($list as $item) : ?>
                            <div class="step">
                                <div class="step__icon">
                                    <svg>
                                        <use xlink:href="#ed-svg-arrow-right"></use>
                                    </svg>
                                </div>

                                <div class="step__title"><?= $item['tekst']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($price = get_field('cni', $item_id)) : ?>
                <div class="accordion-block">
                    <div class="h4"><?= $common_content['zagolovok_bloku_z_cnami']; ?></div>

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
            <?php endif; ?>

            <?php if ($price_info = get_field('tekst_pd_blokom_z_cnami', $item_id)) : ?>
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
            <?php endif; ?>

            <div class="accordion-image">
                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                     data-src="<?php the_field('konka', $item_id); ?>"
                     decoding="async" alt="icon">
            </div>

            <form class="form accordion-form">
                <input type="hidden" name="action" value="ed_callback">
                <input type="hidden" name="url" value="<?php echo admin_url('admin-ajax.php'); ?>">
                <?php wp_nonce_field('ed_callback'); ?>
                <input type="hidden" name="title" value="<?= $add_title . ' - ' . $title; ?>">

                <div class="form-row">
                    <input class="form-row__input required-phone" type="text" name="phone"
                           placeholder="Введіть номер телефону">
                </div>

                <div class="form-row form-row--button">
                    <button class="button" type="submit"><?= $common_content['tekst_knopki_vdpravki_formi']; ?></button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>


