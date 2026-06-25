<?php
/**
 * Reviews block
 */

$reviews = function_exists('educado_reviews_items') ? educado_reviews_items() : [];
?>

<?php if ($reviews) : ?>
    <div class="about-reviews-list">
        <?php foreach ($reviews as $review) {
            $rating = (int) ($review['rating'] ?? 0);
            ?>
            <div class="review-card">
                <div class="review-card__content">
                    <div class="review-card__title">
                        <div class="review-card__title-name"><?= esc_html($review['title'] ?? ''); ?></div>
                        <div class="review-card__title-date"><?= esc_html($review['date'] ?? ''); ?></div>
                    </div>

                    <div class="review-card__course"><?= esc_html($review['subtitle'] ?? ''); ?></div>

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

                        <div class="review-card__rating-num"><?= esc_html($rating); ?></div>
                    </div>

                    <div class="review-card__text"><?= wp_kses_post($review['text'] ?? ''); ?></div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php endif; ?>
