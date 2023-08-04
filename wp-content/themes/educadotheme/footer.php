<footer class="ed-footer">
    <div class="container">
        <div class="ed-footer-bar">
            <?php
            $logo = get_field('footer_logo', 'options');
            ?>

            <?php if (is_front_page()) { ?>
                <div class="ed-footer-logo">
                    <img src="<?= $logo['url']; ?>" alt="<?= $logo['alt']; ?>">
                </div>
            <?php } else { ?>
                <a href="<?php echo home_url()?>" class="ed-header-logo">
                    <img src="<?= $logo['url']; ?>" alt="<?= $logo['alt']; ?>">
                </a>
            <?php } ?>

            <?php
            /** Menu **/
            get_template_part('parts/content', 'menu', array(
                'class' => 'ed-footer-menu',
            ));
            ?>

            <div class="ed-footer-contacts">
                <?php
                $email = get_field('header_email', 'options');
                ?>
                <a class="ed-footer-contacts__link" href="mailto:<?= $email; ?>"><?= $email; ?></a>

                <?php
                /** Social links **/
                get_template_part('parts/content', 'social-links');
                ?>
            </div>

            <div class="ed-footer-button button-speak callpopup" data-popup="callback">
                <svg>
                    <use xlink:href="#ed-svg-speak"></use>
                </svg>
            </div>
        </div>

         <div class="ed-footer-bottom">
            <div class="ed-footer-copyright">© <?= date('Y'); ?> Educado</div>
            
            <a href="http://codevision.agency" target="_blank" class="ed-footer-developer">
                <svg><use xlink:href="#ed-svg-codevision"></use></svg>
            </a>
        </div>
    </div>
</footer>

<?php
/** Popups **/
get_template_part('parts/content', 'popups');
?>

<?php
/** Socials **/
if ( current_user_can( 'administrator' ) ) {
    get_template_part('parts/content', 'socials');
}
?>


<?php
/** Sprite icons **/
get_template_part('parts/content', 'sprite-icons');
?>

<?php wp_footer(); ?>

</body>
</html>
