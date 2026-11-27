<?php get_header(); ?>

<main class="ed-article" data-blog-post>
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $post_id = get_the_ID();
        $protected = post_password_required();
        $fields = !$protected && function_exists('get_fields') ? (get_fields($post_id) ?: []) : [];
        $title = trim(wp_strip_all_tags($fields['zagolovok_na_rozvodyashu'] ?? '')) ?: get_the_title();
        $blocks = is_array($fields['shablon'] ?? null) ? $fields['shablon'] : [];
        $cover_url = $fields['zobrazhennya_na_rozvodyashu'] ?? '';
        $cover_url = is_string($cover_url) ? $cover_url : '';
        $cover_banner = null;
        if (!has_post_thumbnail() && $cover_url !== '' && ($blocks[0]['obert_tip_shablonu'] ?? '') === 'template1'
            && ($blocks[0]['shablon_tekst_shablon_1']['zobrazhennya_pk1'] ?? '') === $cover_url) {
            $cover_banner = array_shift($blocks);
        }
        $faq = educado_blog_faq_rows($fields['educado_blog_faq'] ?? []);
        ?>
        <article <?php post_class('ed-article__inner'); ?>>
            <header class="ed-article__header">
                <p class="ed-article__byline">
                    <a href="<?= esc_url(home_url('/blog/')); ?>"><?= esc_html__('Блог', 'ed'); ?></a>
                    <span aria-hidden="true">·</span>
                    <time datetime="<?= esc_attr(get_the_date(DATE_W3C)); ?>"><?= esc_html(get_the_date('j F Y')); ?></time>
                </p>
                <h1 class="ed-article__title"><?= esc_html(wp_specialchars_decode($title, ENT_QUOTES)); ?></h1>
                <?php if (!$protected && ($cover_banner || has_post_thumbnail() || $cover_url !== '')) : ?>
                    <div class="ed-article__cover">
                        <?php if ($cover_banner) : ?>
                            <?php get_template_part('parts/pt-cards/content', 'template1', ['data' => $cover_banner]); ?>
                        <?php elseif (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', ['fetchpriority' => 'high']); ?>
                        <?php else : ?>
                            <?php
                            $cover_id = (int) get_field('zobrazhennya_na_rozvodyashu', $post_id, false);
                            $cover = wp_get_attachment_image($cover_id, 'large', false, ['fetchpriority' => 'high']);
                            echo $cover ?: '<img src="' . esc_url($cover_url) . '" alt="">';
                            ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php if ($protected || trim(get_post_field('post_content', $post_id, 'raw')) !== '') : ?>
                <div class="ed-article__rich">
                    <?php the_content(); ?>
                    <?php wp_link_pages(); ?>
                </div>
            <?php endif; ?>

            <?php foreach ($blocks as $block) : ?>
                <?php $layout = $block['obert_tip_shablonu'] ?? ''; ?>
                <?php if ($layout === 'template2') : ?>
                    <?php $text = educado_blog_remove_duplicate_title($block['shablon_tekst_shablon_2']['blok_tekstu'] ?? '', $title); ?>
                    <?php if (trim($text) !== '') : ?>
                        <div class="ed-article__rich"><?= $text; // Existing formatted ACF article content. ?></div>
                    <?php endif; ?>
                <?php elseif ($layout === 'template4') : ?>
                    <?php
                    $table = $block['shablon_table_4'] ?? [];
                    $table_html = wp_kses_post($table['content'] ?? '');
                    $table_tags = new WP_HTML_Tag_Processor($table_html);
                    ?>
                    <?php if ($table_tags->next_tag('TABLE')) : ?>
                        <div class="ed-article__rich">
                            <?php if (trim($table['title'] ?? '') !== '') : ?>
                                <h2><?= esc_html($table['title']); ?></h2>
                            <?php endif; ?>
                            <?= $table_html; ?>
                        </div>
                    <?php endif; ?>
                <?php elseif (in_array($layout, ['template1', 'template3'], true)) : ?>
                    <?php get_template_part('parts/pt-cards/content', $layout, ['data' => $block]); ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($faq) : ?>
                <section class="ed-article__faq" aria-labelledby="ed-blog-faq-title">
                    <h2 id="ed-blog-faq-title">
                        <span><?= esc_html__('Часті', 'ed'); ?></span>
                        <span class="ed-article__faq-accent"><?= esc_html__('питання', 'ed'); ?></span>
                    </h2>
                    <?php foreach ($faq as $index => $row) : ?>
                        <details name="ed-blog-faq" <?= $index === 0 ? 'open' : ''; ?>>
                            <summary>
                                <span><?= esc_html($row['question']); ?></span>
                                <span class="ed-article__faq-icon" aria-hidden="true"></span>
                            </summary>
                            <div class="ed-article__rich ed-article__answer"><?= $row['answer']; // Sanitized in educado_blog_faq_rows(). ?></div>
                        </details>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </article>
        <?php if (!$protected && function_exists('get_field')) : ?>
            <?php get_template_part('parts/content-blog-callback'); ?>
        <?php endif; ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
