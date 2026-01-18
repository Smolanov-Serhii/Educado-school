<?php
/**
 * Template Name: Шаблон програм однієї мови
 */

$post_id = get_the_ID();
get_header();

// ====== INPUT (GET) ======
$language = '';

if (!empty($_GET['language'])) {
    $language = sanitize_title((string) $_GET['language']);
} elseif (!empty($_GET['_sft_language'])) {
    $language = sanitize_title((string) $_GET['_sft_language']);
}

// ====== CURRENT LANGUAGE TERM + ACF ======
$language_term = null;
$term_id = null;

if ($language) {
    $language_term = get_term_by('slug', $language, 'language');
    if ($language_term && !is_wp_error($language_term)) {
        $term_id = 'term_' . $language_term->term_id;
    }
}

// ACF fields of taxonomy "language"
$zagolovok_stornki = $term_id ? get_field('zagolovok_stornki', $term_id) : '';
$pdzagolovok_na_stornku = $term_id ? get_field('pdzagolovok_na_stornku', $term_id) : '';
// Pagination: on pages it can be 'paged' or 'page'
$paged = (int) get_query_var('paged');
if ($paged < 1) {
    $paged = (int) get_query_var('page');
}
if ($paged < 1) {
    $paged = 1;
}

// ====== QUERY ======
$args = [
        'post_type'           => 'programs',
        'post_status'         => 'publish',
        'posts_per_page'      => 12,
        'paged'               => $paged,
        'ignore_sticky_posts' => 1,

    // Search & Filter integration
        'search_filter_id'    => 1229,
];

// Filter by language taxonomy via GET (?language=anglijska)
if ($language !== '') {
    $args['tax_query'] = [
            [
                    'taxonomy' => 'language',
                    'field'    => 'slug',
                    'terms'    => $language,
            ]
    ];
}

$programs_q = new WP_Query($args);
?>

<main class="main">
    <?php
    // Optional banner (delete if not used in your theme)
    get_template_part('template-parts/content', 'banner-image');
    ?>

    <div class="programs">
        <div class="main-container">
            <div class="programs__container container">
                <div class="programs__title h1">
                    <?php
                    if (!empty($zagolovok_stornki)) {
                        echo $zagolovok_stornki;
                    } else {
                        echo "Виберіть мову";
                    }
                    ?>
                </div>
                <div class="programs__subtitle h4">
                    <?php
                    if (!empty($pdzagolovok_na_stornku)) {
                        echo wp_kses_post($pdzagolovok_na_stornku);
                    } else {
                        echo "Оберіть курс за рівнем та напрямом";
                    }
                    ?>
                </div>
                <aside class="programs__sidebar <?php if ($language){ echo 'has-language'; }?>">
                    <?php echo do_shortcode('[searchandfilter id="1229"]'); ?>
                </aside>

                <div class="programs__main">
                    <div class="programs__result" id="result">



                        <?php if ($programs_q->have_posts()): ?>
                            <?php while ($programs_q->have_posts()): $programs_q->the_post(); ?>
                                <?php
                                $language = '';

                                $terms = get_the_terms(get_the_ID(), 'language');
                                $termslevel = get_the_terms(get_the_ID(), 'r_ven_');
                                if (!is_wp_error($termslevel) && !empty($termslevel)) {
                                    $level = $termslevel[0]->name;
                                }
                                if (!is_wp_error($terms) && !empty($terms)) {
                                    $language = $terms[0]->name;
                                }
                                $databutton = 'Сторінка програми, мова ' . $language . ', рівень ' . $level . ' - ' . 'програма ' . get_field('nazva_programi');
                                ?>
                                <?php
                                $data = get_fields();
                                ?>
                                <div class="programs__item">
                                    <h3 class="programs__item-title">
                                        <?php the_field('nazva_programi'); ?>
                                    </h3>
                                    <div class="programs__item-content">
                                        <?php
                                        if ($progitems = $data['perelk_blokv_opisv']) {
                                            foreach ($progitems as $progitem) { ?>
                                                <div class="programs__item-block">
                                                    <div class="programs__item-wrapper">
                                                        <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 31.9997C10.3908 32.0069 9.28706 31.8732 8.21093 31.6051C7.10776 31.3311 6.04606 30.9186 5.04023 30.3926C2.59415 29.1133 0.8655 27.105 0.250227 24.4108C-0.432343 21.4217 0.369195 18.2207 1.59614 15.4781L1.59794 15.4739L1.99811 14.5904C3.21183 11.9135 4.3174 9.42106 4.51028 6.4409C4.54032 5.97525 4.60701 5.51258 4.71517 5.05828C4.81731 4.62904 4.95611 4.20817 5.13757 3.8052C5.66511 2.6351 6.52974 1.68888 7.63351 1.0304C8.78054 0.347447 10.1204 0.00776084 11.4537 0H11.4844L11.4994 0.000596987H11.5144L11.5445 0C12.8784 0.00716385 14.2189 0.34685 15.3659 1.031C16.4697 1.68947 17.3349 2.6357 17.8618 3.8058C18.0433 4.20876 18.1827 4.62964 18.2848 5.05887C18.393 5.51258 18.4591 5.97585 18.4897 6.4415C18.6826 9.42166 19.7876 11.9141 21.0019 14.5916L21.4039 15.4787C22.6308 18.2207 23.4323 21.4217 22.7498 24.4108C22.1345 27.105 20.4064 29.1133 17.9604 30.3926C16.9545 30.9186 15.8928 31.3311 14.7897 31.6051C13.7135 31.8726 12.6104 32.0063 11.5006 31.9997H11.5Z" fill="#D3D360"></path>
                                                            <path d="M15.8125 28.5938L20.7871 3H23.3184L18.3262 28.5938H15.8125ZM22.9668 28.5938L27.959 3H30.4727L25.4805 28.5938H22.9668ZM32.6172 12.7559H14.3359V10.3125H32.6172V12.7559ZM31.2988 21.3867H13V18.9609H31.2988V21.3867Z" fill="#161616"></path>
                                                        </svg>
                                                        <?= $progitem['kontent_bloku_opisu']; ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="programs__item-btn">
                                        <div class="button callpopup" data-popup="trial" data-item="<?= $databutton ?>">Залишити заявку</div>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="pagination">
                                <?php
                                echo paginate_links([
                                        'total'   => (int) $programs_q->max_num_pages,
                                        'current' => $paged,
                                        'format'  => '?paged=%#%',
                                    // preserve GET params (language + S&F fields already in querystring)
                                        'add_args' => array_filter([
                                                'language' => $language ?: null,
                                        ]),
                                ]);
                                ?>
                            </div>

                            <?php wp_reset_postdata(); ?>
                        <?php else: ?>
                            <p>Нічого не знайдено</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
