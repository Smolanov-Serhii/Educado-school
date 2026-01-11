<?php
/**
 * Template 1
 */
?>

<?php
$common_content = $args['data'] ?? [];
if ($common_content){
    ?>
    <section class="ed-home-video" style="background-color: transparent; padding-top: 0">
        <div class="ed-home-video__container container">
            <div class="ed-home-videoblock">
                <div class="ed-home-videoblock__bg">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?php echo $common_content['shablon_vdeo_shablon_3']['zobrazhennya_pk3']; ?>"
                         decoding="async">
                </div>
                <div class="teacher-card__play" data-youtube="<?php echo $common_content['shablon_vdeo_shablon_3']['posilannya_na_vdeo_yutub']; ?>">
                    <svg>
                        <use xlink:href="#ed-svg-play"></use>
                    </svg>
                </div>
            </div>
        </div>
    </section>
    <?php
}
?>



