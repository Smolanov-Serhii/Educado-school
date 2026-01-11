<?php
/**
 * Template 1
 */
?>

<?php
$common_content = $args['data'] ?? [];
if ($common_content){
    ?>
        <section class="ed-rekimg">
            <div class="container <?php if($common_content['shablon_tekst_shablon_1']['ti_banera'] == 'js-form'){ echo 'callpopup';}?>" <?php if($common_content['shablon_tekst_shablon_1']['ti_banera'] == 'js-form'){ echo 'data-popup="trial"';}?>>
            <?php
                if($common_content['shablon_tekst_shablon_1']['ti_banera'] != 'lnk') {
                    ?>

                    <?php
                } else {
                    ?>
                         <a href="<?php echo $common_content['shablon_tekst_shablon_1']['posilannya_na_stornku_lnk']?>" class="container <?php if($common_content['shablon_tekst_shablon_1']['ti_banera'] == 'js-form'){ echo 'js-form';}?>">
                    <?php
                }
            ?>

                <picture>
                    <!-- Desktop: от 1025px и выше -->
                    <source
                            media="(min-width: 1025px)"
                            srcset="<?php echo $common_content['shablon_tekst_shablon_1']['zobrazhennya_pk1']; ?>">

                    <!-- Tablet: от 501px до 1024px -->
                    <source
                            media="(min-width: 501px) and (max-width: 1024px)"
                            srcset="<?php echo $common_content['shablon_tekst_shablon_1']['zobrazhennya_tb1']; ?>">

                    <!-- Mobile: от 0px до 500px -->
                    <source
                            media="(max-width: 500px)"
                            srcset="<?php echo $common_content['shablon_tekst_shablon_1']['zobrazhennya_mob1']; ?>">

                    <!-- Fallback -->
                    <img
                            src="<?php echo $common_content['shablon_tekst_shablon_1']['zobrazhennya_pk1']; ?>"
                            loading="lazy"
                            alt="">
                </picture>
                    <?php
                        if($common_content['shablon_tekst_shablon_1']['ti_banera'] != 'lnk') {
                            ?>

                            <?php
                        } else {
                            ?>
                                </a>
                            <?php
                        }
                   ?>
            </div>
        </section>
    <?php
}
?>



