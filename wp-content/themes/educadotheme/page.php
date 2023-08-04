<?php get_header(); ?>

<main class="ed-page">
    <div class="container">
        <div class="ed-page-content">
            <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();

                        the_content();
                    }
                }
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>