<?php
/**
 * Menu
 */

if (isset($args['class']) && !empty($args['class'])) {
    $class = $args['class'];
    $class_html = " class='$class'";
} else {
    $class_html = '';
}
$menu = get_field('header_menyu', 'options');
?>
<?php
$data = get_fields();
?>
<ul<?= $class_html; ?>>
    <?php if ($menu['tekst_courses']) : ?>
        <li class="has-submenu">
            <a href="#ed-courses"><?= $menu['tekst_courses']; ?></a>
            <ul class="subitem-list">
                <?php
                $args = array(
                    'post_type' => 'courses',
                    'relation' => 'OR',
                    'showposts' => "-1", //сколько показать статей
                    'orderby' => "menu_order", //сортировка по дате
                    'caller_get_posts' => 1);

                $my_query = new wp_query($args);
                if ($my_query->have_posts()) {
                    while ($my_query->have_posts()) {
                        $my_query->the_post();
                        $postpers_id = get_the_ID();
                        $title = get_field('tekst_v_punkt_menyu', $postpers_id);
                        $lnk = get_field('yakr_na_punkt_menyu', $postpers_id);
                        ?>
                            <li class="subitem-list__item"><a data-lnk="#<?= $lnk ?>" href="#<?= $lnk ?>"><?= $title ?></a></li>
                        <?php }
                }
                wp_reset_query(); ?>
                <li class="subitem-list__item"><a href="https://educado.school/#ed-home-clubs">Корпоративне навчання</a></li>
            </ul>
        </li>
    <?php endif; ?>

    <?php if ($menu['tekst_reviews']) : ?>
        <li>
            <a href="#ed-testimonials"><?= $menu['tekst_reviews']; ?></a>
        </li>
    <?php endif; ?>

    <?php if ($menu['tekst_teaches']) : ?>
        <li>
            <a href="#ed-teachers"><?= $menu['tekst_teaches']; ?></a>
        </li>
    <?php endif; ?>
</ul>
