<?php
/**
 * Template Name: Програми (мови)
 */
?>
<?php get_header(); ?>
<main class="ed-home еуіе">
    <?php
    $data = get_fields();
    ?>
    <section class="ed-home-banner ed-home-banner-programs">
        <div class="programs-wrapper">
            <div class="container">
                <div class="banner-content">
                    <div class="banner-world__image-static">
                        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                             data-src="<?= $data['zobrazhennya_na_stornku']; ?>" alt="icon">
                    </div>
                    <h1 class="banner-title h1"><?= $data['shapka']; ?></h1>
                    <div class="banner-subtitle h3">
                        <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 31.9997C10.3908 32.0069 9.28706 31.8732 8.21093 31.6051C7.10776 31.3311 6.04606 30.9186 5.04023 30.3926C2.59415 29.1133 0.8655 27.105 0.250227 24.4108C-0.432343 21.4217 0.369195 18.2207 1.59614 15.4781L1.59794 15.4739L1.99811 14.5904C3.21183 11.9135 4.3174 9.42106 4.51028 6.4409C4.54032 5.97525 4.60701 5.51258 4.71517 5.05828C4.81731 4.62904 4.95611 4.20817 5.13757 3.8052C5.66511 2.6351 6.52974 1.68888 7.63351 1.0304C8.78054 0.347447 10.1204 0.00776084 11.4537 0H11.4844L11.4994 0.000596987H11.5144L11.5445 0C12.8784 0.00716385 14.2189 0.34685 15.3659 1.031C16.4697 1.68947 17.3349 2.6357 17.8618 3.8058C18.0433 4.20876 18.1827 4.62964 18.2848 5.05887C18.393 5.51258 18.4591 5.97585 18.4897 6.4415C18.6826 9.42166 19.7876 11.9141 21.0019 14.5916L21.4039 15.4787C22.6308 18.2207 23.4323 21.4217 22.7498 24.4108C22.1345 27.105 20.4064 29.1133 17.9604 30.3926C16.9545 30.9186 15.8928 31.3311 14.7897 31.6051C13.7135 31.8726 12.6104 32.0063 11.5006 31.9997H11.5Z" fill="#D3D360"/>
                            <path d="M15.8125 28.5938L20.7871 3H23.3184L18.3262 28.5938H15.8125ZM22.9668 28.5938L27.959 3H30.4727L25.4805 28.5938H22.9668ZM32.6172 12.7559H14.3359V10.3125H32.6172V12.7559ZM31.2988 21.3867H13V18.9609H31.2988V21.3867Z" fill="#161616"/>
                        </svg>
                        <?= $data['pdzagolovok_stornki']; ?>
                    </div>
                    <div class="banner-languages__buttons">
                        <?php
                        if ($buttons = $data['perelk_mov']) {
                            foreach ($buttons as $button) { ?>
                                <?php
                                $base_page = get_page_by_path('programy-odniyeyi-movy'); // <-- поменяй 'programs' на slug страницы "все программы"
                                $base_url  = $base_page ? get_permalink($base_page) : home_url('/programy-odniyeyi-movy/');
                                $lang_terms = $button['mova_dlya_fyltruvannya'] ?? [];
                                $lang_slug = '';
                                if (is_array($lang_terms) && !empty($lang_terms) && $lang_terms[0] instanceof WP_Term) {
                                    $lang_slug = $lang_terms[0]->slug; // anglijska
                                }
                                $url = $lang_slug ? add_query_arg(['language' => $lang_slug], $base_url) : $base_url;
                                echo '<!-- base_url: ' . esc_url($base_url) . ' | language: ' . esc_html($lang_slug) . ' | url: ' . esc_url($url) . ' -->';
                                ?>
                                <a href="<?= esc_url($url); ?>" class="button button-green h1">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $button['konka_na_knopku']; ?>" alt="icon">
                                    <?= $button['napis_na_knopc']; ?>
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
