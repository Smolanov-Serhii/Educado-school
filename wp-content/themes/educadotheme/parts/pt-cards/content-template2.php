<?php
/**
 * Template 1
 */
?>

<?php
$common_content = $args['data'] ?? [];
if ($common_content){
    ?>
        <section class="ed-blogtext">
            <div class="container">
                <div class="ed-blogtext__content">
                    <?php echo $common_content['shablon_tekst_shablon_2']['blok_tekstu']; ?>
                </div>
            </div>
        </section>
    <?php
}
?>



