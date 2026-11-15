<?php
/** Pricing v2 replaces the old block only through the guarded template entry. */
$settings = (array) get_field('pricing_v2');
$model = educado_pricing_model($settings);
$selected = [];
foreach ($model['options'] as $dimension => $options) {
    $selected[] = key($options);
}
if ($model['prices']) {
    $selected = explode('|', key($model['prices']));
}
$initial = $model['prices'][implode('|', $selected)] ?? null;
$block_id = 'ed-pricing-' . get_the_ID();
$heading = $settings['heading'] ?? 'Ціна';
if (educado_has_language_headings()) {
    $heading = get_field('zagolovok_bloku_z_spiskom') ?: $heading;
}
?>
<div class="container">
    <div class="ed-pricing" id="<?php echo esc_attr($block_id); ?>" data-ed-pricing>
        <div class="ed-pricing__header">
            <h3 class="ed-pricing__title"><?php echo esc_html($heading); ?></h3>
            <?php $dimension = 'course'; $index = 0; $legend = 'Тип курсу'; include __DIR__ . '/pricing-options.php'; ?>
        </div>
        <div class="ed-pricing__filters">
            <?php $dimension = 'duration'; $index = 1; $legend = 'Тривалість уроку'; include __DIR__ . '/pricing-options.php'; ?>
            <?php $dimension = 'level'; $index = 2; $legend = 'Рівень'; include __DIR__ . '/pricing-options.php'; ?>
        </div>
        <div class="ed-pricing__result" aria-live="polite" aria-atomic="true">
            <p class="ed-pricing__empty" <?php echo $initial ? 'hidden' : ''; ?>>Ця комбінація поки недоступна. Залиште номер телефону — менеджер допоможе підібрати навчання.</p>
            <div class="ed-pricing__cards" <?php echo $initial ? '' : 'hidden'; ?>>
                <?php foreach ([8 => '8 уроків', 24 => '24 уроки', 48 => '48 уроків'] as $size => $title) : ?>
                    <div class="ed-pricing__card <?php echo $size === 48 ? 'ed-pricing__card--best' : ''; ?>">
                        <div class="ed-pricing__card-top">
                            <span><?php echo esc_html($title); ?></span>
                            <?php if ($size === 48 && !empty($settings['badge'])) : ?><span class="ed-pricing__badge">★ <?php echo esc_html($settings['badge']); ?></span><?php endif; ?>
                        </div>
                        <p class="ed-pricing__price"><strong data-price="<?php echo esc_attr($size); ?>"><?php echo $initial ? esc_html(number_format_i18n((float) $initial[$size], floor((float) $initial[$size]) == (float) $initial[$size] ? 0 : 2)) : '—'; ?></strong> <span>грн / урок</span></p>
                        <p class="ed-pricing__note"><?php echo esc_html($settings['note_' . $size] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (!empty($settings['installments'])) : ?>
            <div class="ed-pricing__installments">
                <?php if (!empty($settings['banks'])) : ?>
                    <?php echo wp_get_attachment_image((int) $settings['banks'], 'medium', false, ['alt' => 'Банки-партнери розтермінування']); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/pricing-banks.svg'); ?>" width="140" height="42" alt="Банки-партнери розтермінування">
                <?php endif; ?>
                <p><?php echo esc_html($settings['installments']); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($settings['bonus'])) : ?><p class="ed-pricing__bonus"><?php echo esc_html($settings['bonus']); ?></p><?php endif; ?>
        <form class="form ed-pricing__form">
            <input type="hidden" name="action" value="ed_callback">
            <input type="hidden" name="url" value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
            <?php wp_nonce_field('ed_callback'); ?>
            <input type="hidden" name="title" value="<?php echo esc_attr(get_the_title()); ?>">
            <div class="form-row"><input class="form-row__input required-phone" type="text" inputmode="tel" autocomplete="tel" name="phone" aria-label="Номер телефону" placeholder="Ваш номер телефону"></div>
            <div class="form-row form-row--button"><button class="button" type="submit"><?php echo esc_html(($settings['button'] ?? '') ?: 'Хочу на безкоштовний урок'); ?></button></div>
        </form>
        <script type="application/json" class="ed-pricing__data"><?php echo wp_json_encode($model['prices'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
    </div>
</div>
