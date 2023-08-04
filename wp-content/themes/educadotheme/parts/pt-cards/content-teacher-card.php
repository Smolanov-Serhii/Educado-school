<?php
/**
 * Teacher card
 */
?>

<?php
$item_id = $args['id'] ?? '';

if ($item_id) {
    $teacher_name = get_the_title($item_id);
    $teacher_lang = get_field('mova', $item_id);
    $teacher_photo = wp_get_attachment_image_src(get_post_thumbnail_id($item_id), 'medium_large') ?? '';
    $teacher_video_url = get_field('posilannya_na_yutub', $item_id);
    $matches = array();
    $pattern = '/watch\?v=([^\&\?\/]+)/';
    preg_match($pattern, $teacher_video_url, $matches);
    $video_id = $matches[1];
    ?>
    <div class="swiper-slide">
        <div class="teacher-card">
            <div class="teacher-card__wrapper">
                <div class="teacher-card__course h5"><?= $teacher_lang; ?></div>
                <div class="teacher-card__name h2"><?= $teacher_name; ?></div>

                <div class="teacher-card__play" data-youtube="<?= $video_id; ?>">
                    <svg>
                        <use xlink:href="#ed-svg-play"></use>
                    </svg>
                </div>

                <div class="teacher-card__logo">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?php echo get_template_directory_uri(); ?>/assets/img/educado.svg"
                         decoding="async" alt="icon">
                </div>

                <div class="teacher-card__image">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?= $teacher_photo[0]; ?>"
                         decoding="async"
                         alt="<?= $teacher_name . ' - ' . $teacher_lang; ?>">
                </div>
            </div>
        </div>
    </div>
<?php } ?>


