<?php
/**
 * Reviews block
 */

$args = array(
    'post_type' => 'reviews',
    'posts_per_page' => -1,
    'status' => 'publish',
    'meta_key' => 'data',
    'orderby' => 'meta_value',
    'meta_type' => 'DATE'
);

$reviews = get_posts($args);
?>

<?php if ($reviews) : ?>
    <div class="about-reviews-list">
        <?php foreach ($reviews as $review) {
            $id = $review->ID;
            $rating = get_field('rejting', $id);
            ?>
            <div class="review-card">
                <div class="review-card__content">
                    <div class="review-card__title">
                        <div class="review-card__title-name"><?= $review->post_title; ?></div>
                        <div class="review-card__title-date"><?php the_field('data', $id); ?></div>
                    </div>

                    <div class="review-card__course"><?php the_field('pdzagolovok', $id); ?></div>

                    <div class="review-card__rating">
                        <div class="review-card__rating-stars">
                            <?php
                            $i = 1;
                            while ($i <= $rating) { ?>
                                <svg>
                                    <use xlink:href="#ed-svg-star"></use>
                                </svg>
                                <?php
                                $i++;
                            }
                            ?>
                        </div>

                        <div class="review-card__rating-num"><?= $rating; ?></div>
                    </div>

                    <div class="review-card__text"><?php the_field('tekst', $id); ?></div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php endif; ?>
