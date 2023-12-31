<?php
/**
 * Social links
 */

$soc_links = get_field('header_soc_merezh', 'options');
?>
<div class="ed-socials">
    <?php if ($soc_links['instagram']) : ?>
        <a href="<?= $soc_links['instagram']; ?>" class="ed-socials__link" target="_blank">
            <svg>
                <use xlink:href="#ed-svg-instagram"></use>
            </svg>
        </a>
    <?php endif; ?>

    <?php if ($soc_links['facebook']) : ?>
        <a href="<?= $soc_links['facebook']; ?>" class="ed-socials__link" target="_blank">
            <svg>
                <use xlink:href="#ed-svg-facebook"></use>
            </svg>
        </a>
    <?php endif; ?>

    <?php if ($soc_links['telegram']) : ?>
        <a href="<?= $soc_links['telegram']; ?>" class="ed-socials__link" target="_blank">
            <svg>
                <use xlink:href="#ed-svg-telegram"></use>
            </svg>
        </a>
    <?php endif; ?>

    <?php if ($soc_links['linkedin']) : ?>
        <a href="<?= $soc_links['linkedin']; ?>" class="ed-socials__link" target="_blank">
            <svg>
                <use xlink:href="#ed-svg-linked"></use>
            </svg>
        </a>
    <?php endif; ?>
</div>
