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

            <ul class="ed-footer-menu">
                <li>
                    <a href="#ed-courses">Курси</a>
                    <ul class="subitem-list">

                    </ul>
                </li>

                <li>
                    <a href="#ed-testimonials">Відгуки</a>
                </li>

                <li>
                    <a href="#ed-teachers">Вчителі</a>
                </li>
                <li>
                    <a href="<?php echo get_permalink(338)?>">Оферта</a>
                </li>
            </ul>

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
    <div class="footer__chat">
        <div class="footer__chat-call">
            <script type="text/javascript">
                (function(d, w, s) {
                    var widgetHash = 'flwhv49cvqcvl465505i', gcw = d.createElement(s); gcw.type = 'text/javascript'; gcw.async = true;
                    gcw.src = '//widgets.binotel.com/getcall/widgets/'+ widgetHash +'.js';
                    var sn = d.getElementsByTagName(s)[0]; sn.parentNode.insertBefore(gcw, sn);
                })(document, window, 'script');
            </script>
        </div>
        <div class="footer__chat-text">
            <script type="text/javascript">
                (function(d, w, s) {
                    var widgetHash = 'A7LXciKvZVYwSXv1JX5d', bch = d.createElement(s); bch.type = 'text/javascript'; bch.async = true;
                    bch.src = '//widgets.binotel.com/chat/widgets/' + widgetHash + '.js';
                    var sn = d.getElementsByTagName(s)[0]; sn.parentNode.insertBefore(bch, sn);
                })(document, window, 'script');
            </script>
        </div>
    </div>
</footer>

<?php
/** Popups **/
get_template_part('parts/content', 'popups');
?>

<?php
/** Socials **/
//get_template_part('parts/content', 'socials');
?>


<?php
/** Sprite icons **/
get_template_part('parts/content', 'sprite-icons');
?>

<?php wp_footer(); ?>

</body>
</html>
