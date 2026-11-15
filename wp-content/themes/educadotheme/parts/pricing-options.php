<?php /** Internal partial. $model, $dimension, $index, $selected and $block_id come from the pricing block. */ ?>
<fieldset class="ed-pricing__options ed-pricing__options--<?php echo esc_attr($dimension); ?>" data-dimension="<?php echo esc_attr($dimension); ?>">
    <legend><?php echo esc_html($legend); ?></legend>
    <div class="ed-pricing__choices">
        <?php foreach ($model['options'][$dimension] as $id => $label) : ?>
            <label class="ed-pricing__choice">
                <input type="radio" name="<?php echo esc_attr($block_id . '-' . $dimension); ?>" value="<?php echo esc_attr($id); ?>" <?php checked($selected[$index], $id); ?>>
                <span><?php echo esc_html($label); ?></span>
            </label>
        <?php endforeach; ?>
    </div>
</fieldset>
