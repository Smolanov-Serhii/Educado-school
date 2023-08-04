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
    <div class="accordion<?= $add_class; ?>">
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
                            <svg>
                                <use xlink:href="#ed-svg-parts"></use>
                            </svg>
                        </div>

                        <div class="accordion-parts__title"><?= $price_info; ?></div>
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


