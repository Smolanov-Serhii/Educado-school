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

<ul<?= $class_html; ?>>
    <?php if ($menu['tekst_courses']) : ?>
        <li>
            <a href="#ed-courses"><?= $menu['tekst_courses']; ?></a>
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
