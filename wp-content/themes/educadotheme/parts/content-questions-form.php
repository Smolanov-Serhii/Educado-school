<?php
/** Shared homepage/store callback form. The caller provides independent copy. */
$copy = $args['settings'] ?? [];
$tag = ($args['heading_tag'] ?? 'div') === 'h2' ? 'h2' : 'div';
$name_placeholder = ($copy['name_placeholder'] ?? '') ?: get_field('common_form_data_plejsholder_v_pol_mya', 'options');
$button_text = ($copy['button'] ?? '') ?: get_field('common_form_data_tekst_knopki_vdpravki_formi', 'options');
$agreement_text = ($copy['agreement_text'] ?? '') ?: get_field('common_form_data_tekst_poltiki', 'options');
$agreement_text = str_replace('{кнопка}', $button_text, (string) $agreement_text);
$agreement_link = get_field('common_form_data_tekst_poltiki_link', 'options');
$agreement_label = ($copy['agreement_label'] ?? '') ?: get_field('common_form_data_tekst_poltiki_2', 'options');
?>
<<?php echo $tag; ?> class="questions-title h1"><?php echo wp_kses_post($copy['heading'] ?? ''); ?></<?php echo $tag; ?>>
<div class="questions-subtitle h3"><?php echo wp_kses_post($copy['subtitle'] ?? ''); ?></div>
<form class="form">
    <input type="hidden" name="action" value="ed_callback">
    <input type="hidden" name="url" value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
    <?php wp_nonce_field('ed_callback'); ?>
    <input type="hidden" name="title" value="<?php echo esc_attr(($copy['lead_title'] ?? '') ?: 'Залишилися питання'); ?>">
    <div class="form-row"><input class="form-row__input required" type="text" name="name" autocomplete="name" aria-label="<?php echo esc_attr($name_placeholder ?: 'Ім’я'); ?>" placeholder="<?php echo esc_attr($name_placeholder); ?>"></div>
    <div class="form-row"><input class="form-row__input required-phone" type="text" name="phone" inputmode="tel" autocomplete="tel" aria-label="Номер телефону" placeholder="<?php echo esc_attr(($copy['phone_placeholder'] ?? '') ?: 'Введіть номер телефону'); ?>"></div>
    <div class="form-row"><div class="form-row__agreement"><?php echo wp_kses_post($agreement_text); ?> <a href="<?php echo esc_url($agreement_link); ?>" target="_blank" rel="noopener"><?php echo esc_html($agreement_label); ?></a></div></div>
    <div class="form-row form-row--button"><button class="button" type="submit"><?php echo esc_html($button_text); ?></button></div>
</form>
