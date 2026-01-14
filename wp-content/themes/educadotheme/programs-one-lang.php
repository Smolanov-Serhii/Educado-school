<?php
/**
 * Template Name: Шаблон програм однієї мови
 */

$post_id = get_the_ID();
get_header();

// ====== INPUT (GET) ======
$language = isset($_GET['language']) ? sanitize_title((string) $_GET['language']) : '';

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
                        echo esc_html($zagolovok_stornki);
                    } elseif ($language_term) {
                        echo esc_html($language_term->name);
                    }
                    ?>
                </div>
                <div class="programs__subtitle h3">
                    <?php
                    if (!empty($pdzagolovok_na_stornku)) {
                        echo wp_kses_post($pdzagolovok_na_stornku);
                    }
                    ?>
                </div>
                <aside class="programs__sidebar">
                    <div class="programs__sidebar-close">
                        <svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 1L1 20M21 20L1 1" stroke="black" stroke-width="2"/>
                        </svg>
                    </div>

                    <?php echo do_shortcode('[searchandfilter id="1229"]'); ?>
                </aside>

                <div class="programs__main">
                    <div class="programs__main-header">
                        <div class="filter">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1106_14204)">
                                    <path d="M14.1 14.1L20.5 7.7V4.5H4.5V7.7L10.9 14.1V20.5L14.1 17.3V14.1Z" fill="white"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1106_14204">
                                        <rect width="16" height="16" fill="white" transform="translate(4.5 4.5)"/>
                                    </clipPath>
                                </defs>
                            </svg>
                            Фільтр
                        </div>
                    </div>

                    <div class="programs__result" id="result">

                        <?php
                        // Page content (keep if you want editor/ACF blocks above list)
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                the_content();
                            }
                            wp_reset_postdata();
                        }
                        ?>

                        <?php if ($programs_q->have_posts()): ?>
                            <?php while ($programs_q->have_posts()): $programs_q->the_post(); ?>
                                <a href="<?php the_permalink(); ?>" class="programs__item">
                                    <div class="programs__item-image">
                                        <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
                                    </div>

                                    <h3 class="programs__item-title">
                                        <?php the_title(); ?>
                                    </h3>
                                </a>
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
