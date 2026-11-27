<?php
/** Group-only offer, independent of the per-lesson matrix. */
$group_number = function ($number) {
    return number_format_i18n((float) $number, floor((float) $number) == (float) $number ? 0 : 2);
};
?>
<div class="ed-pricing__group" data-group-only <?php echo $group_initial ? '' : 'hidden'; ?> aria-live="polite">
    <?php if ($group_model['has_levels']) : ?>
    <div class="ed-pricing__filters">
        <fieldset class="ed-pricing__options">
            <legend>Рівень групових занять</legend>
            <div class="ed-pricing__choices">
                <?php foreach ($group_model['offers'] as $index => $offer) : ?>
                <label class="ed-pricing__choice">
                    <input type="radio" name="<?php echo esc_attr($block_id . '-group-level'); ?>" value="<?php echo esc_attr($index); ?>" data-group-level <?php checked($index, 0); ?>>
                    <span><?php echo esc_html($offer['label']); ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </fieldset>
    </div>
    <?php endif; ?>
    <?php foreach ($group_model['offers'] as $index => $offer) :
        $previous = (float) ($offer['previous_price'] ?? 0);
        $total = (float) $offer['course_price'];
        $discount = $previous > $total ? round((1 - $total / $previous) * 100, 1) : 0;
    ?>
    <div data-group-level-panel="<?php echo esc_attr($index); ?>" <?php echo $index === 0 ? '' : 'hidden'; ?>>
    <ul class="ed-pricing__conditions">
        <?php foreach (['condition_lessons', 'condition_students', 'condition_schedule'] as $name) : ?>
            <li><?php echo esc_html($offer[$name]); ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="ed-pricing__cards ed-pricing__cards--group">
        <div class="ed-pricing__card">
            <div class="ed-pricing__card-top"><span><?php echo esc_html($offer['module_title']); ?></span></div>
            <p class="ed-pricing__price"><strong><?php echo esc_html($group_number($offer['module_price'])); ?></strong> <span>грн / модуль</span></p>
            <p class="ed-pricing__note"><?php echo esc_html($offer['module_note'] ?? ''); ?></p>
        </div>
        <div class="ed-pricing__card ed-pricing__card--best">
            <div class="ed-pricing__card-top"><span><?php echo esc_html($offer['course_title']); ?></span>
                <?php if ($discount > 0) : ?><span class="ed-pricing__discount">−<?php echo esc_html($group_number($discount)); ?>%</span><?php endif; ?>
            </div>
            <p class="ed-pricing__price"><strong><?php echo esc_html($group_number($total)); ?></strong> <span>грн</span>
                <?php if ($previous > $total) : ?><del><?php echo esc_html($group_number($previous)); ?></del><?php endif; ?>
            </p>
            <p class="ed-pricing__note"><?php echo esc_html($offer['course_note'] ?? ''); ?></p>
        </div>
    </div>
    </div>
    <?php endforeach; ?>
</div>
