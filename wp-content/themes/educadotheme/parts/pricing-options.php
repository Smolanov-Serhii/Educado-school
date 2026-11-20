<?php
/** Internal partial. The pricing block supplies the active dimensions and selection. */
if (empty($model['options'][$dimension])) {
    return;
}
$pill = in_array($dimension, ['course', 'format'], true) ? ' ed-pricing__options--pill' : '';
$choice = $selected[$dimension] ?? ($selected[$index ?? -1] ?? null);
?>
<fieldset class="ed-pricing__options ed-pricing__options--<?php echo esc_attr($dimension . $pill); ?>" data-dimension="<?php echo esc_attr($dimension); ?>">
    <legend><?php echo esc_html($legend); ?></legend>
    <div class="ed-pricing__choices">
        <?php foreach ($model['options'][$dimension] as $id => $label) : ?>
            <?php
            $available = false;
            $axis = array_search($dimension, array_keys($model['options']), true);
            foreach (array_keys($model['prices']) as $key) {
                $values = explode('|', $key);
                if (($values[$axis] ?? null) === $id) {
                    $available = true;
                    break;
                }
            }
            if ($dimension === 'format' && !empty($group_model['ready']) && $id === $group_model['format_ref']) $available = true;
            ?>
            <label class="ed-pricing__choice<?php echo $available ? '' : ' ed-pricing__choice--disabled'; ?>">
                <input type="radio" name="<?php echo esc_attr($block_id . '-' . $dimension); ?>" value="<?php echo esc_attr($id); ?>" <?php checked($choice, $id); ?> <?php disabled(!$available); ?> aria-disabled="<?php echo $available ? 'false' : 'true'; ?>">
                <span><?php echo esc_html($label); ?></span>
            </label>
        <?php endforeach; ?>
    </div>
</fieldset>
