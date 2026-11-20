<div class="ed-pricing__payments">
    <div class="ed-pricing__payment ed-pricing__payment--school"><strong>0%</strong><span><?php echo esc_html(($settings['school_installments'] ?? '') ?: 'Розтермінування від школи'); ?></span></div>
    <div class="ed-pricing__payment ed-pricing__payment--bank"><span><?php echo esc_html(($settings['bank_installments'] ?? '') ?: 'Або «Оплата частинами» від банку'); ?></span>
        <?php if (!empty($settings['banks'])) : ?>
            <?php echo wp_get_attachment_image((int) $settings['banks'], 'medium', false, ['alt' => 'Банки-партнери розтермінування']); ?>
        <?php else : ?>
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/pricing-banks.png'); ?>" width="410" height="130" alt="Банки-партнери розтермінування">
        <?php endif; ?>
    </div>
</div>
