<?php

get_header();

// ====== CONFIG ======
$post_type = 'programs';
$tax_map = [
    // GET параметр => taxonomy slug
        'language' => 'language',
    // добавишь потом другие фильтры:
    // 'level'  => 'level',
    // 'format' => 'format',
];

// ====== READ FILTERS FROM GET ======
$tax_query = ['relation' => 'AND'];

foreach ($tax_map as $param => $taxonomy) {
    if (!isset($_GET[$param]) || $_GET[$param] === '' || $_GET[$param] === null) {
        continue;
    }

    $value = $_GET[$param];

    // поддержка мультивыбора: ?language[]=anglijska&language[]=nimetska
    if (is_array($value)) {
        $terms = array_values(array_filter(array_map('sanitize_title', $value)));
        if ($terms) {
            $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $terms,
                    'operator' => 'IN',
            ];
        }
    } else {
        $term = sanitize_title((string)$value);
        if ($term !== '') {
            $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $term,
            ];
        }
    }
}

// ====== QUERY ======
$paged = max(1, (int) get_query_var('paged'));

$args = [
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'paged'          => $paged,
];

if (count($tax_query) > 1) {
    $args['tax_query'] = $tax_query;
}

$q = new WP_Query($args);
?>

<main class="programs-page">
    <div class="container">

        <?php
        // Если хочешь сохранить контент страницы (ACF/блоки/редактор) — оставь:
        if (have_posts()) {
            while (have_posts()) { the_post();
                the_content();
            }
            wp_reset_postdata();
        }
        ?>

        <section class="programs-list">
            <?php if ($q->have_posts()): ?>
                <?php while ($q->have_posts()): $q->the_post(); ?>
                    <article class="program-card">
                        <a href="<?php the_permalink(); ?>">
                            <h3><?php the_title(); ?></h3>
                        </a>
                    </article>
                <?php endwhile; ?>

                <div class="pagination">
                    <?php
                    echo paginate_links([
                            'total'   => $q->max_num_pages,
                            'current' => $paged,
                    ]);
                    ?>
                </div>

                <?php wp_reset_postdata(); ?>
            <?php else: ?>
                <p>Ничего не найдено.</p>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php get_footer(); ?>
