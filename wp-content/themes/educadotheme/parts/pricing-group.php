<?php
/** Group-only offer, independent of the per-lesson matrix. */
$group_number = function ($number) {
    return number_format_i18n((float) $number, floor((float) $number) == (float) $number ? 0 : 2);
};
$previous = (float) ($group_settings['previous_price'] ?? 0);
$total = (float) $group_settings['course_price'];
$discount = $previous > $total ? round((1 - $total / $previous) * 100, 1) : 0;
?>
<div class="ed-pricing__group" data-group-only <?php echo $group_initial ? '' : 'hidden'; ?> aria-live="polite">
    <ul class="ed-pricing__conditions">
        <?php foreach (['condition_lessons', 'condition_students', 'condition_schedule'] as $name) : ?>
            <li><?php echo esc_html($group_settings[$name]); ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="ed-pricing__cards ed-pricing__cards--group">
        <div class="ed-pricing__card">
            <div class="ed-pricing__card-top"><span><?php echo esc_html($group_settings['module_title']); ?></span></div>
            <p class="ed-pricing__price"><strong><?php echo esc_html($group_number($group_settings['module_price'])); ?></strong> <span>грн / модуль</span></p>
            <p class="ed-pricing__note"><?php echo esc_html($group_settings['module_note'] ?? ''); ?></p>
        </div>
        <div class="ed-pricing__card ed-pricing__card--best">
            <div class="ed-pricing__card-top"><span><?php echo esc_html($group_settings['course_title']); ?></span>
                <?php if ($discount > 0) : ?><span class="ed-pricing__discount">−<?php echo esc_html($group_number($discount)); ?>%</span><?php endif; ?>
            </div>
            <p class="ed-pricing__price"><strong><?php echo esc_html($group_number($total)); ?></strong> <span>грн</span>
                <?php if ($previous > $total) : ?><del><?php echo esc_html($group_number($previous)); ?></del><?php endif; ?>
            </p>
            <p class="ed-pricing__note"><?php echo esc_html($group_settings['course_note'] ?? ''); ?></p>
        </div>
    </div>
</div>
