<?php
/** Template Name: Магазин */
get_header();
$data = (array) get_fields();
$groups = educado_market_groups($data['perelk_propozicj'] ?? []);
$faqs = get_field('market_faq');
$form_copy = (array) get_field('market_form');
$arrow = '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$check = '<svg aria-hidden="true" viewBox="0 0 80 80" fill="none"><circle cx="40" cy="40" r="35" fill="currentColor"/><path d="m12 26 24 17L74 9 37 61Z" fill="#fff"/></svg>';
$leaf = '<svg aria-hidden="true" viewBox="0 0 36 36"><path d="M10.914 25.085c-4.953-4.953-4.953-12.932 0-17.885l18.069 17.885c-4.953 4.953-13.116 4.953-18.069 0Z" fill="currentColor"/></svg>';
?>
<main class="ed-market" data-market>
    <section class="market-intro">
        <div class="container">
            <h1><?php echo educado_market_heading($data['zagolovok_v_gapku'] ?? get_the_title()); ?></h1>
            <?php if ($groups) : ?>
            <div class="market-intro__navigation">
                <p><?php echo esc_html(get_field('nav_label') ?: 'Обирай курс за потребою:'); ?></p>
                <nav aria-label="Групи курсів" class="market-nav">
                    <?php foreach ($groups as $index => $group) : ?>
                        <a href="#<?php echo esc_attr($group['id']); ?>" <?php echo $index === 0 ? 'aria-current="location"' : ''; ?>><?php echo esc_html(wp_strip_all_tags($group['title'])); ?></a>
                    <?php endforeach; ?>
                </nav>
            </div>
            <?php endif; ?>
        </div>
        <svg class="market-intro__line" aria-hidden="true" viewBox="0 180 1920 260" preserveAspectRatio="none" fill="none"><path d="M-619 638-547 538C-477 438-337 243-189 193-40 144 118 241 272 273c154 31 303-4 456 13 153 18 309 90 460 81 151-9 298-99 451-81s312 145 467 189 304 10 451-81" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke"/></svg>
    </section>
    <div class="container market-catalog">
        <?php foreach ($groups as $group) : ?>
        <section class="market-group" id="<?php echo esc_attr($group['id']); ?>" aria-labelledby="<?php echo esc_attr($group['id'] . '-title'); ?>">
            <h2 id="<?php echo esc_attr($group['id'] . '-title'); ?>"><?php echo $check; ?><span><?php echo esc_html(wp_strip_all_tags($group['title'])); ?></span></h2>
            <div class="market-grid">
                <?php foreach ($group['cards'] as $index => $card) : $item = $card['item'];
                    $image = $item['zobrazhennya'] ?? '';
                    $image = is_array($image) ? ($image['url'] ?? '') : $image;
                    $image = function_exists('educado_krasprod_card_img') ? educado_krasprod_card_img($image) : $image;
                    $title = trim(wp_strip_all_tags($card['label'])) ?: wp_strip_all_tags($item['tematika'] ?? 'Курс');
                    $url = $item['posilannya_na_magazin'] ?? '';
                    $price = trim(wp_strip_all_tags($item['cna'] ?? ''));
                    $old_price = trim(wp_strip_all_tags($item['stara_cna'] ?? ''));
                    $price = $price !== '' && is_numeric($price) ? $price . ' грн' : $price;
                    $old_price = $old_price !== '' && is_numeric($old_price) ? $old_price . ' грн' : $old_price;
                    $price = str_replace('грн/місяць', 'грн/міс', $price);
                    $old_price = str_replace('грн/місяць', 'грн/міс', $old_price);
                ?>
                <article class="market-card" data-market-card>
                    <h3 class="market-card__title"><?php echo esc_html($title); ?></h3>
                    <?php $cover_tag = $url ? 'a' : 'div'; ?>
                    <<?php echo $cover_tag; ?> class="market-card__cover" <?php if ($url) : ?>href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr('Детальніше: ' . $title); ?>"<?php endif; ?>>
                        <span class="market-card__topic"><?php echo esc_html(wp_strip_all_tags(($item['tematika'] ?? '') ?: $title)); ?></span>
                        <?php if ($image) : ?><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(($item['tematika'] ?? '') ?: $title); ?>" width="600" height="600" loading="lazy" decoding="async"><?php endif; ?>
                    </<?php echo $cover_tag; ?>>
                    <div class="market-card__facts">
                        <?php foreach (['trivalst_v_hv', 'klkst_zanyat'] as $fact) : if (!empty($item[$fact])) : ?><span><?php echo $leaf; ?><?php echo esc_html(wp_strip_all_tags($item[$fact])); ?></span><?php endif; endforeach; ?>
                    </div>
                    <div class="market-card__bottom">
                        <div class="market-card__prices">
                            <?php if ($old_price !== '') : ?><del><?php echo esc_html($old_price); ?></del><?php endif; ?>
                            <?php if ($price !== '') : ?><strong><?php echo esc_html($price); ?></strong><?php endif; ?>
                            <?php if ($price === '' && $old_price === '') : ?><span class="market-card__price-request">Уточніть вартість</span><?php endif; ?>
                        </div>
                        <a class="market-card__link" href="<?php echo esc_url($url ?: '#market-contact'); ?>" <?php echo $url ? 'target="_blank" rel="noopener"' : ''; ?> aria-label="<?php echo esc_attr((get_field('details_label') ?: 'Детальніше') . ': ' . $title); ?>"><span><?php echo esc_html(get_field('details_label') ?: 'Детальніше'); ?></span><span class="market-card__arrow"><?php echo $arrow; ?></span></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endforeach; ?>
        <?php if (is_array($faqs) && $faqs) : ?>
        <section class="market-faq" aria-labelledby="market-faq-title">
            <h2 id="market-faq-title"><?php $faq_heading = get_field('faq_heading') ?: 'Часті питання'; echo $faq_heading === 'Часті питання' ? 'Часті <mark>питання</mark>' : esc_html($faq_heading); ?></h2>
            <div class="market-faq__items">
                <?php foreach ($faqs as $index => $faq) : if (empty($faq['question']) || empty($faq['answer'])) continue; ?>
                <details class="market-faq__item" <?php echo $index === 0 ? 'open' : ''; ?> data-market-faq>
                    <summary><?php echo esc_html($faq['question']); ?><span class="market-faq__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M12 5v14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span></summary>
                    <div class="market-faq__answer"><?php echo wp_kses_post($faq['answer']); ?></div>
                </details>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        <section class="market-contact" id="market-contact" aria-label="Заявка на навчання з вчителем">
            <div class="questions-form">
                <?php get_template_part('parts/content', 'questions-form', ['settings' => $form_copy, 'heading_tag' => 'h2']); ?>
            </div>
            <img class="market-contact__image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/market-teacher.svg?ver=' . educado_asset_version(get_template_directory() . '/assets/img/market-teacher.svg')); ?>" alt="" width="250" height="270" loading="lazy">
        </section>
    </div>
</main>
<?php get_footer(); ?>
