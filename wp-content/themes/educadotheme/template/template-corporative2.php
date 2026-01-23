<?php
/**
 * Template Name: Шаблон корпоративу + прайси
 */
$paged = (int) get_query_var('paged');
if ($paged < 1) {
    $paged = (int) get_query_var('page');
}
if ($paged < 1) {
    $paged = 1;
}

// ====== QUERY ======
$args = [
        'post_type'           => 'price',
        'post_status'         => 'publish',
        'posts_per_page'      => 12,
        'paged'               => $paged,
        'ignore_sticky_posts' => 1,

    // Search & Filter integration
        'search_filter_id'    => 1389,
];

$programs_q = new WP_Query($args);
?>
<?php get_header(); ?>
<main class="ed-home еуіе">
    <?php
    $data = get_fields();
    ?>
    <section class="ed-home-banner ed-home-banner-language ed-home-banner-corporative">
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title h1"><?= $data['section_1_zagolovok']; ?></h1>
                <div class="banner-subtitle h3"><?= $data['section_1_pdzagolovok']; ?></div>
                <div class="button callpopup" data-popup="trial"><?= $data['napis_na_knopc_first']; ?></div>
            </div>
            <div class="banner-world">
                <div class="banner-world__image">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?= $data['kartinka_v_pershij_blok']; ?>" alt="<?= $data['section_1_zagolovok']; ?>">
                </div>
            </div>
        </div>
    </section>
    <?php if ($sec_languages = $data['lang_movi']) : ?>
        <section class="ed-home-languages ed-home-languages-corporative">
            <div class="languages-ticker">
                <?php
                    if($data['zagolovok_bloku_movi']){
                        ?>
                            <div class="languages-ticker__container container">
                                <div class="look-title">
                                    <span><?= $data['zagolovok_bloku_movi']; ?></span>
                                </div>
                            </div>
                        <?php
                    }

                ?>
                <div class="languages-ticker__wrapper">
                    <div class="languages-ticker__line">
                        <?php foreach ($sec_languages as $item) { ?>
                            <div class="language">
                                <div class="language__image">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['konka']; ?>"
                                         class="language__image-normal" alt="language-icon">

                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['konka_hover']; ?>"
                                         class="language__image-active" alt="language-icon">
                                </div>

                                <div class="technology__title"><?= $item['mova']; ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="languages-ticker languages-ticker--reverse">
                <div class="languages-ticker__wrapper">
                    <div class="languages-ticker__line">
                        <?php
                        $reversed_languages = array_reverse($sec_languages);
                        foreach ($reversed_languages as $item) { ?>
                            <div class="language">
                                <div class="language__image">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['konka']; ?>"
                                         class="language__image-normal" alt="language-icon">

                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['konka_hover']; ?>"
                                         class="language__image-active" alt="language-icon">
                                </div>

                                <div class="technology__title"><?= $item['mova']; ?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <section class="ed-home-about ed-home-proposition">
        <div class="container">
            <div class="proposition-title h1"><?= $data['zagolovok_bloku_sho_proponumo'];?></div>
            <div class="proposition-list">
                <?php
                if ($sec_2_proposition = $data['elementi_bloku_sho_proponumo']) {
                    foreach ($sec_2_proposition as $item) { ?>
                        <div class="proposition-item">
                            <div class="proposition-item__title">
                                <?= $item['zagolovok']; ?>
                            </div>
                            <div class="proposition-item__desc">
                                <?= $item['opis']; ?>
                            </div>
                            <a href="<?= $item['posilannya_na_knopku']; ?>" class="proposition-item__lnk">
                                Детальніше
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1000" zoomAndPan="magnify" viewBox="0 0 750 749.999995" height="1000" preserveAspectRatio="xMidYMid meet" version="1.0"><path fill="#d3d360" d="M 373.898438 126.632812 C 246.625 126.632812 129.492188 184.660156 52.542969 285.800781 C 40.539062 301.554688 40.539062 323.554688 52.53125 339.289062 C 129.492188 440.453125 246.625 498.476562 373.898438 498.476562 C 501.171875 498.476562 618.304688 440.453125 695.253906 339.308594 C 707.253906 323.554688 707.253906 301.578125 695.265625 285.824219 C 618.304688 184.65625 501.171875 126.632812 373.898438 126.632812 Z M 660.210938 312.621094 C 591.648438 402.742188 487.289062 454.433594 373.898438 454.433594 C 260.507812 454.433594 156.148438 402.742188 87.585938 312.492188 C 156.148438 222.371094 260.507812 170.679688 373.898438 170.679688 C 487.289062 170.679688 591.648438 222.371094 660.210938 312.492188 C 660.210938 312.5 660.210938 312.609375 660.210938 312.621094 Z M 660.210938 312.621094 " fill-opacity="1" fill-rule="nonzero"/><path fill="#d3d360" d="M 373.898438 183.636719 C 302.808594 183.636719 244.980469 241.46875 244.980469 312.554688 C 244.980469 383.644531 302.808594 441.472656 373.898438 441.472656 C 444.988281 441.472656 502.816406 383.644531 502.816406 312.554688 C 502.816406 241.46875 444.988281 183.636719 373.898438 183.636719 Z M 373.898438 397.429688 C 327.101562 397.429688 289.023438 359.351562 289.023438 312.554688 C 289.023438 265.757812 327.101562 227.679688 373.898438 227.679688 C 420.695312 227.679688 458.773438 265.757812 458.773438 312.554688 C 458.773438 359.351562 420.695312 397.429688 373.898438 397.429688 Z M 373.898438 397.429688 " fill-opacity="1" fill-rule="nonzero"/></svg>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <section class="ed-home-start">
        <div class="start-lines">
            <svg viewBox="0 0 1920 627" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M-216.31 2.08907C-26.895 -8.2502 112.585 97.1772 270.874 181.262C494.364 299.991 751.319 346.774 1002.72 321.832C1147.71 307.451 1276.08 257.222 1411.38 206.508C1639.12 121.15 1875.45 -1.17742 2125.31 21.0514"
                      stroke="#2D2D0B" stroke-miterlimit="10" stroke-dasharray="8 8"/>
                <path d="M-258.481 626.211C-85.6046 506.417 90.4827 390.429 276.992 292.753C464.898 194.343 659.185 124.251 873.042 180.059C1004.88 214.468 1122.44 285.583 1243.26 346.082C1368.28 408.685 1501.08 459.593 1638 488.894C1912.78 547.677 2206.06 532.189 2479.28 472.216"
                      stroke="#2D2D0B" stroke-miterlimit="10" stroke-dasharray="8 8"/>
                <path d="M2263.75 286.649C2172.49 253.112 2068.48 256.517 1972.62 254.496C1551 245.596 1097.27 283.271 699.502 430.402C562.845 480.963 419.697 525.49 272.3 520.05C57.654 512.133 -137.477 400.159 -322.616 291.23"
                      stroke="#2D2D0B" stroke-miterlimit="10" stroke-dasharray="8 8"/>
            </svg>
        </div>

        <div class="start-content">
            <div class="container">
                <div class="start-title h1"><?= $data['how_start_zagolovok']; ?></div>

                <div class="start-steps">
                    <div class="start-step">
                        <?php
                        $how_start_card_1 = $data['how_start_kartka_1'];
                        ?>
                        <div class="start-step__icon">
                            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                 data-src="<?= $how_start_card_1['konka']; ?>"
                                 decoding="async" alt="icon">
                        </div>

                        <div class="start-step__pretitle h4"><?= $how_start_card_1['tekst_1']; ?></div>
                        <div class="start-step__title h3"><?= $how_start_card_1['tekst_2']; ?></div>

                        <div class="button callpopup"
                             data-popup="callback"><?= $how_start_card_1['tekst_knopki']; ?></div>
                    </div>

                    <div class="start-step">
                        <?php
                        $how_start_card_2 = $data['how_start_kartka_2'];
                        ?>
                        <div class="start-step__icon">
                            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                 data-src="<?= $how_start_card_2['konka']; ?>"
                                 decoding="async" alt="icon">
                        </div>

                        <div class="start-step__pretitle h4"><?= $how_start_card_2['tekst_1']; ?></div>
                        <div class="start-step__title h3"><?= $how_start_card_2['tekst_2']; ?></div>
                    </div>

                    <div class="start-step">
                        <?php
                        $how_start_card_3 = $data['how_start_kartka_3'];
                        ?>
                        <div class="start-step__title h3"><?= $how_start_card_3['zagolovok']; ?></div>

                        <div class="start-step__list h5">
                            <?php
                            if ($how_start_card_3['spisok']) {
                                foreach ($how_start_card_3['spisok'] as $item) { ?>
                                    <div class="start-step__list-item">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.89478 10.374L7.89478 16.374L19.8948 4.37402" stroke="#161616"
                                                  stroke-width="2" stroke-linecap="round"/>
                                        </svg>

                                        <svg>
                                            <use xlink:href="#ed-svg-check2"></use>
                                        </svg>
                                        <span><?= $item['tekst']; ?></span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>

                        <div class="start-step__title h3"><?= $how_start_card_3['tekst']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ed-home-courses ed-home-courses-platform">
        <div class="ed-home-courses__bg">
            <svg width="1920" height="400" viewBox="0 0 1920 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_77_749)">
                    <path d="M1296.76 308C1296.76 308 1481.68 205.075 1331.22 145.668C1288.04 128.617 1196.31 62.9294 1221.22 31.7064C1280 -41.97 1717.95 40.4063 1935 41.0586" stroke="#161616" stroke-width="2"/>
                    <g clip-path="url(#clip1_77_749)">
                        <path d="M1280.07 293.246C1281.05 280.501 1281.94 267.094 1280.64 253.733M1147.15 374.986C1154.71 382.279 1163.05 388.535 1175.57 392.788C1206.43 403.284 1229.58 399.063 1249.33 384.248C1270.39 368.453 1276.8 343.5 1278.96 308.614C1279.12 306.121 1279.3 303.612 1279.49 301.08M1121.94 345.315C1122.41 346.506 1122.93 347.677 1123.51 348.829C1126.98 355.807 1132.15 361.905 1138.12 366.883C1139.37 367.925 1140.98 369.495 1142.65 371.065M1166.6 259.989C1164.69 261.524 1162.66 262.916 1160.5 264.127C1156.29 266.482 1151.77 268.21 1147.69 270.798C1143.77 273.287 1140.1 276.288 1136.82 279.578C1124.93 291.51 1118.04 308.019 1118.17 324.89C1118.2 329.359 1118.69 333.844 1119.71 338.191M1191.33 227.368C1187.13 233.679 1183.06 240.353 1178.74 246.582C1176.49 249.82 1173.79 253.059 1171.05 255.871M1279.61 245.852C1278.62 239.9 1277.13 233.908 1274.98 227.9C1274.28 225.668 1273.41 223.467 1272.33 221.313C1270.54 217.759 1268.13 214.54 1265.33 211.731C1262.04 208.564 1258.28 205.945 1254.21 203.878C1250.45 201.961 1246 201.278 1241.85 200.789C1232.95 199.74 1223.59 200.722 1215.29 204.217C1210.75 206.13 1206.94 208.808 1203.55 211.996" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1262.56 233.052C1260.14 224.819 1255.22 217.668 1245.94 213.226C1245.94 213.226 1232.51 208.958 1218.34 215.735" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1194.26 241.6C1184.86 256.561 1178.4 268.249 1162.65 275.034C1143.09 283.459 1125.43 307.372 1133.04 336.179C1140.65 364.986 1189.55 386.997 1230.85 362.813C1261.83 341.342 1260.19 296.776 1262.1 277.483C1262.42 274.186 1262.91 270.494 1263.37 266.577" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1165.48 337.84C1165.07 337.22 1151.49 326.109 1158.08 302.236C1164.66 278.363 1195.53 273.63 1213.02 281.448C1230.51 289.27 1234.42 310.054 1227.83 324.255C1221.25 338.455 1207.25 345.192 1192.85 345.658C1173.72 346.277 1165.48 337.84 1165.48 337.84Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1173.49 237.745C1173.78 235.521 1171.11 234.98 1169.59 235.955C1169.02 235.454 1168.44 235.02 1167.98 234.677C1166.77 233.777 1163.87 232.168 1163.64 234.961C1163.56 235.86 1164.11 236.4 1164.73 236.897C1164.58 236.882 1164.43 236.866 1164.27 236.862C1163.11 236.842 1162.44 238.384 1163.31 239.193C1163.41 239.292 1163.53 239.386 1163.63 239.485C1163.4 239.517 1163.16 239.556 1162.92 239.603C1161.88 239.801 1161.5 241.323 1162.28 242.01C1163.32 242.917 1164.43 243.583 1165.71 244.017C1165.84 245.331 1165.99 246.66 1166.14 248.005C1165.89 252.755 1166.23 257.551 1166.59 262.289C1166.83 265.354 1171.68 265.322 1171.36 262.289C1171.19 257.95 1171 253.551 1170.38 249.24C1170.17 247.422 1169.94 245.607 1169.71 243.793C1169.7 243.666 1169.67 243.544 1169.63 243.43C1171.42 242.029 1173.21 239.852 1173.49 237.753V237.745Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1319.04 278.899C1318.06 278.264 1316.91 277.905 1315.71 277.708C1316.56 277.357 1317.39 276.939 1318.17 276.406C1319.08 275.783 1318.86 273.992 1317.54 274.111C1314.41 274.395 1311.33 275.136 1308.48 276.379C1308.59 275.925 1308.64 275.464 1308.72 274.99C1308.89 274.008 1309.26 273.263 1309.77 272.414C1310.5 271.18 1311.23 270.123 1310.96 268.659C1310.77 267.665 1309.39 267.401 1308.74 268.075C1306.5 270.395 1305.07 272.967 1303.61 275.811C1302.6 277.759 1301.03 279.31 1299.35 280.722C1297.18 281.479 1294.76 283.203 1292.85 284.426C1290.54 285.901 1288.72 287.116 1286.48 288.694C1284.34 290.205 1282.21 291.727 1280.06 293.238C1277.81 294.828 1275.27 296.346 1273.5 298.484C1273.12 298.942 1272.98 299.34 1272.9 299.983C1272.58 301.754 1274.7 303.904 1276.88 302.839C1279.11 301.75 1280.96 300.07 1282.85 298.492C1284.86 296.812 1286.9 295.155 1288.95 293.522C1290.66 292.165 1292.38 290.82 1294.09 289.467C1294.55 289.159 1295.02 288.852 1295.48 288.548C1297.5 287.238 1299.73 286.126 1301.62 284.619C1303.33 283.806 1305.09 283.219 1307.13 283.136C1310.05 283.014 1312.89 283.992 1315.78 284.28C1316.99 284.402 1317.5 282.473 1316.41 281.964C1315.6 281.586 1314.77 281.258 1313.92 280.962C1315.41 280.99 1316.92 281.081 1318.42 281.187C1319.7 281.278 1320 279.515 1319.04 278.899Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1130.43 348.505C1129.81 345.768 1128.9 342.837 1126.83 340.821C1124.73 338.774 1121.81 338.131 1118.96 338.384C1114.21 338.81 1110.11 341.243 1106.91 344.616C1106.29 343.46 1105.56 342.372 1104.7 341.397C1102.95 339.421 1099.84 338.036 1097.51 339.957C1094.9 342.107 1097.46 344.545 1099.36 345.941C1099.36 345.945 1099.37 345.949 1099.37 345.953C1100.27 347.121 1101.2 348.269 1102.17 349.377C1103.39 350.777 1104.86 352.884 1107.01 352.154C1107.54 351.973 1107.96 351.661 1108.26 351.263C1111.21 348.884 1112.99 345.784 1117.05 344.943C1120.8 344.17 1124.78 345.941 1125.9 349.752C1126.76 352.659 1131.11 351.456 1130.43 348.505Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1162.58 377.861C1161.96 375.124 1161.04 372.193 1158.97 370.177C1156.87 368.13 1153.96 367.487 1151.11 367.74C1146.35 368.166 1142.25 370.599 1139.06 373.972C1138.44 372.816 1137.7 371.728 1136.85 370.753C1135.1 368.777 1131.98 367.392 1129.65 369.313C1127.04 371.463 1129.61 373.901 1131.5 375.297C1131.51 375.301 1131.52 375.305 1131.52 375.309C1132.42 376.477 1133.35 377.625 1134.32 378.733C1135.54 380.133 1137.01 382.24 1139.15 381.51C1139.68 381.329 1140.11 381.017 1140.4 380.619C1143.36 378.24 1145.13 375.14 1149.2 374.299C1152.94 373.526 1156.93 375.297 1158.05 379.108C1158.91 382.015 1163.26 380.812 1162.58 377.861Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1285.54 255.137C1284.93 252.132 1282.54 249.299 1280.61 247.015C1279.3 245.477 1277.75 243.158 1275.77 242.246C1275 240.018 1272.59 238.815 1270.53 237.541C1267.73 235.809 1264.85 234.188 1261.85 232.839C1258.88 231.505 1255.78 230.476 1252.59 229.841C1249.74 229.273 1245.56 228.508 1242.91 230.097C1242.69 230.231 1242.5 230.401 1242.34 230.594C1241.33 229.49 1239.85 228.894 1238.46 228.409C1238.22 228.322 1237.74 228.192 1237.16 228.058C1236.78 227.821 1236.47 227.64 1236.27 227.557C1234.92 226.981 1233.43 226.417 1231.93 226.551C1231.95 226.303 1231.92 226.054 1231.85 225.802C1230.97 222.843 1227.37 220.587 1224.88 219.084C1222.1 217.4 1219.12 216.066 1216.03 215.049C1212.9 214.019 1209.68 213.27 1206.45 212.658C1204.11 212.216 1201.57 211.502 1199.51 212.54C1197.58 213.936 1196.2 216.264 1195.8 216.978C1195.82 216.978 1193.54 221.186 1192.7 223.522C1191.12 227.912 1189.13 232.46 1191.08 237.079C1192.81 241.146 1196.36 244.306 1200.19 246.341C1204.12 248.424 1208.78 249.741 1213.24 249.792C1217.83 249.848 1221.75 247.891 1224.74 244.448C1226.27 242.684 1227.42 240.598 1228.38 238.483C1229.36 236.314 1230.46 233.9 1230.99 231.533C1231.12 231.47 1231.25 231.391 1231.37 231.285C1231.79 230.91 1232.07 230.791 1232.53 230.827C1233.45 230.906 1234.38 231.407 1235.3 231.592C1235.45 231.624 1235.68 231.667 1235.96 231.718C1236.17 231.86 1236.34 231.971 1236.46 232.042C1237.26 232.523 1238.28 232.78 1239.02 233.348C1239.38 233.632 1239.5 233.904 1239.56 234.468C1239.58 234.63 1239.62 234.775 1239.67 234.91C1238.47 237.016 1237.65 239.537 1236.91 241.805C1236.19 244.014 1235.63 246.329 1235.57 248.664C1235.45 253.224 1237.02 257.311 1240.44 260.368C1243.77 263.338 1248.1 265.516 1252.4 266.628C1256.6 267.713 1261.35 267.78 1265.37 265.942C1269.93 263.855 1271.53 259.153 1273.33 254.849C1274.28 252.558 1275.29 250.218 1275.42 247.887C1275.69 248.159 1275.96 248.428 1276.23 248.704C1277.05 249.552 1277.83 250.428 1278.61 251.307C1280.11 253.007 1281.62 255.114 1283.54 256.36C1284.41 256.924 1285.76 256.293 1285.53 255.153L1285.54 255.137ZM1225.52 234.275C1224.79 236.223 1223.99 238.172 1222.88 239.935C1221.04 242.818 1218.31 245.264 1214.75 245.422C1211.34 245.572 1207.67 244.854 1204.5 243.667C1201.48 242.538 1198.61 240.708 1196.65 238.109C1194.18 234.839 1194.11 231.438 1195.48 227.691C1196.14 225.876 1196.81 224.046 1197.54 222.255C1198.15 220.752 1198.93 219.068 1200.59 218.544C1201.05 218.402 1201.51 218.307 1201.98 218.248C1202.51 218.2 1203.03 218.185 1203.56 218.196C1206.32 218.315 1209.14 219.435 1211.73 219.92C1213.99 220.685 1216.21 221.616 1218.34 222.642C1220.21 223.545 1222.04 224.547 1223.78 225.675C1224.96 226.437 1226.55 227.387 1226.92 228.847C1227.34 230.503 1226.1 232.752 1225.53 234.271L1225.52 234.275ZM1270.62 250.641C1269.95 252.451 1269.21 254.254 1268.47 256.041C1266.95 259.725 1264.6 262.186 1260.58 262.936C1257.38 263.531 1254.03 262.944 1251.04 261.741C1247.9 260.474 1244.7 258.526 1242.29 256.12C1239.77 253.603 1239.4 249.95 1240 246.586C1240.36 244.53 1241.09 242.554 1241.86 240.621C1242.47 239.111 1243.07 236.614 1244.49 235.675C1245.75 234.85 1247.56 235.221 1248.95 235.454C1251 235.801 1253.02 236.294 1255.02 236.894C1257.28 237.572 1259.54 238.385 1261.73 239.347C1263.96 240.732 1266.8 241.817 1268.92 243.588C1269.31 243.931 1269.69 244.302 1270.05 244.688C1270.35 245.043 1270.63 245.426 1270.87 245.84C1271.75 247.347 1271.18 249.118 1270.62 250.641H1270.62Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1232.09 261.468C1226.78 266.289 1217.09 261.76 1216.68 254.837" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                    </g>
                    <path d="M-1 232L40 181.478C80 131.152 160 32.3684 240 10.6093C320 -10.3488 400 45.7175 480 67.4766C560 88.4348 640 75.0856 720 88.8352C800 103.119 880 145.836 960 145.836C1040 145.836 1120 103.119 1200 117.402C1280 131.152 1360 203.237 1440 231.27C1520 259.304 1600 245.954 1680 202.837C1760 160.52 1840 88.4347 1880 53.3265L1920 17.6844" stroke="#161616" stroke-width="2"/>
                </g>
                <defs>
                    <clipPath id="clip0_77_749">
                        <rect width="1920" height="400" fill="white"/>
                    </clipPath>
                    <clipPath id="clip1_77_749">
                        <rect width="226" height="201" fill="white" transform="translate(1095 199)"/>
                    </clipPath>
                </defs>
            </svg>
        </div>
        <div class="ed-home-whyus">
            <div class="container">
                <div class="ed-home-courses-language__title h1">
                    <?= $data['zagolovok_yak_prohodit_navchannya']; ?>
                </div>
                <div class="ed-home-courses-language__col">
                    <div class="ed-home-courses-language__left">
                        <?= $data['lvj_punkt']; ?>
                    </div>
                    <div class="ed-home-courses-language__right">
                        <?= $data['pravij_punkt']; ?>
                    </div>

                </div>
                <?php if ($perel = get_field('punkuti_yak_prohodit_navchannya')) : ?>
                    <div class="whyus-list">
                        <?php foreach ($perel as $item) : ?>
                            <div class="whyus-list__item">
                                <div class="feature-title"><?= $item['tekst_punktu']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <section class="ed-home-look ed-home-look-corporativenew">
        <div class="container">
            <div class="ed-home-week__title h1">
                <div class="look-title__row">
                    <span style="text-align: center"><?= $data['zagolovok_metodiki_blok']; ?></span>
                </div>
            </div>
            <div class="corporativenew__list">
                <?php if ($metod_list = $data['punkti_metodiki']) : ?>
                    <?php
                    foreach ($metod_list as $item) {
                        ?>
                        <div class="corporativenew__item">
                            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="40" cy="40" r="35" fill="#D3D360"/>
                                <path d="M36.984 56.7846C35.8011 56.7846 34.6632 56.2168 33.8632 55.2248L8.71516 24.0772C7.72801 22.8518 7.76691 21.0367 8.80634 19.8539C9.68408 18.8546 11.0262 18.6418 12.1447 19.3262L35.3501 33.5269C35.4777 33.6047 35.6795 33.6849 35.947 33.4795L73.618 4.5615C74.7037 3.72874 76.1297 3.83086 77.0864 4.81072C78.0809 5.82948 78.4274 7.72963 77.2858 9.14714L40.1047 55.2248C39.3047 56.2168 38.1668 56.7846 36.984 56.7858V56.7846Z" fill="white"/>
                            </svg>

                            <div class="corporativenew__item-title">
                                <?= $item['zagolovok']; ?>
                            </div>
                            <div class="corporativenew__item-img">
                                <img src="<?= $item['kartinka']; ?>">
                            </div>
                            <div class="corporativenew__item-desc">
                                <?= $item['tekst']; ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                <?php endif; ?>
            </div>
        </div>

        <div class="ed-home-look__bg">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/look.svg"
                 decoding="async" alt="icon">
        </div>
    </section>
    <div class="ed-home-price">
        <div class="container">
            <div class="start-title h1 ed-home-price__title">
                <?= $data['zagolovok_bloku_prajsv']; ?>
            </div>
            <div class="ed-home-price__content">
                <aside class="programs__sidebar">
                    <?php echo do_shortcode('[searchandfilter id="1389"]'); ?>
                </aside>
                <div class="programs__main">
                    <div class="programs__result" id="result">
                        <?php if ($programs_q->have_posts()): ?>
                            <?php while ($programs_q->have_posts()): $programs_q->the_post(); ?>
                                <?php
                                $datain = get_fields();
                                ?>
                                <div class="programs__item">
                                    <div class="programs__item-inner">
                                        <div class="programs__item-top">
                                            <div class="programs__item-title">
                                                <?php the_field('tip_navchann'); ?>
                                            </div>
                                            <div class="programs__item-price">
                                                <?php the_field('cna'); ?>
                                            </div>
                                            <div class="programs__item-counr">
                                                <?php the_field('odinicya_vimru'); ?>
                                            </div>
                                            <div class="programs__item-content">
                                                <?php the_field('perednya_storona'); ?>
                                            </div>
                                            <div class="programs__item-button button-turn">
                                                <svg width="71" height="71" viewBox="0 0 71 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <mask id="mask0_2107_7772" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="71" height="71">
                                                        <path d="M70.835 70.835L4.31697e-07 70.835L6.62429e-06 4.31696e-07L70.835 6.62428e-06L70.835 70.835Z" fill="white"/>
                                                    </mask>
                                                    <g mask="url(#mask0_2107_7772)">
                                                        <mask id="mask1_2107_7772" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="71" height="71">
                                                            <path d="M0.000259399 70.8369L70.8364 70.8369L70.8364 0.000753873L0.000265592 0.000747681L0.000259399 70.8369Z" fill="white"/>
                                                        </mask>
                                                        <g mask="url(#mask1_2107_7772)">
                                                            <mask id="mask2_2107_7772" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="71" height="71">
                                                                <path d="M70.835 70.835L0.00240141 70.835L0.0024076 4.31906e-07L70.835 6.62428e-06L70.835 70.835Z" fill="white"/>
                                                            </mask>
                                                            <g mask="url(#mask2_2107_7772)">
                                                                <path d="M35.4185 70.835C15.9004 70.835 0.0024028 54.9369 0.00240451 35.4185C0.00240621 15.9 15.9004 0.0024028 35.4185 0.00240451C54.9369 0.00240621 70.835 15.9 70.835 35.4185C70.835 54.9369 54.9369 70.835 35.4185 70.835ZM35.4185 3.15041C17.6316 3.15041 3.15041 17.6316 3.15041 35.4185C3.15041 53.2053 17.6316 67.6865 35.4185 67.6865C53.2053 67.6866 67.6865 53.2053 67.6865 35.4185C67.6866 17.6316 53.2053 3.15041 35.4185 3.15041ZM55.6451 34.6317C55.6451 34.5527 55.5665 34.5527 55.5665 34.4741C55.4879 34.3955 55.4089 34.3169 55.3303 34.2379L41.1639 20.0714C40.9277 19.8352 40.5343 19.678 40.1409 19.678C39.7471 19.678 39.3537 19.8352 39.0389 20.15C38.4093 20.7796 38.4093 21.7241 39.0389 22.3541L50.5293 33.8445L16.53 33.8445C15.6642 33.8445 14.9556 34.5527 14.9556 35.4185C14.9556 36.2843 15.6642 36.9925 16.53 36.9925L50.5293 36.9925L39.0389 48.4833C38.4093 49.1129 38.4093 50.0573 39.0389 50.6869C39.6685 51.3165 40.6129 51.3165 41.2425 50.6869L55.4089 36.5205C55.4879 36.4415 55.5665 36.3629 55.6451 36.2843C55.6451 36.2843 55.7237 36.2057 55.7237 36.1267C55.7237 36.0481 55.8027 36.0481 55.8027 35.9695C55.8027 35.8909 55.8027 35.8909 55.8813 35.8119C55.8813 35.7333 55.8813 35.7333 55.9599 35.6547C56.0385 35.4185 56.0385 35.2613 55.9599 35.0251C55.9599 34.9465 55.9599 34.9465 55.8813 34.8675C55.8813 34.7889 55.8813 34.7889 55.8027 34.7103C55.7237 34.7889 55.7237 34.7103 55.6451 34.6317Z" fill="#818000"/>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="programs__item-back">
                                            <div class="programs__item-content">
                                                <?php the_field('zvorotnya_storona'); ?>
                                            </div>
                                            <div class="programs__item-button button-back">
                                                <svg width="71" height="71" viewBox="0 0 71 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <mask id="mask0_2107_7772" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="71" height="71">
                                                        <path d="M0 0L70.835 0L70.835 70.835L0 70.835L0 0Z" fill="white"/>
                                                    </mask>
                                                    <g mask="url(#mask0_2107_7772)">
                                                        <mask id="mask1_2107_7772" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="-1" y="-1" width="72" height="72">
                                                            <path d="M70.8347 -0.00195313L-0.00146484 -0.00195312L-0.00146484 70.8342L70.8347 70.8342L70.8347 -0.00195313Z" fill="white"/>
                                                        </mask>
                                                        <g mask="url(#mask1_2107_7772)">
                                                            <mask id="mask2_2107_7772" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="71" height="71">
                                                                <path d="M0 0L70.8326 0L70.8326 70.835L0 70.835L0 0Z" fill="white"/>
                                                            </mask>
                                                            <g mask="url(#mask2_2107_7772)">
                                                                <path d="M35.4165 0C54.9345 0 70.8326 15.898 70.8326 35.4165C70.8326 54.9349 54.9345 70.8326 35.4165 70.8326C15.898 70.8326 0 54.9349 0 35.4165C0 15.898 15.898 0 35.4165 0ZM35.4165 67.6845C53.2033 67.6845 67.6845 53.2033 67.6845 35.4165C67.6845 17.6296 53.2033 3.14841 35.4165 3.14841C17.6296 3.14841 3.14841 17.6296 3.14841 35.4165C3.14841 53.2033 17.6296 67.6845 35.4165 67.6845ZM15.1898 36.2033C15.1898 36.2823 15.2684 36.2823 15.2684 36.3609C15.347 36.4395 15.426 36.5181 15.5046 36.5971L29.6711 50.7635C29.9073 50.9997 30.3007 51.1569 30.6941 51.1569C31.0879 51.1569 31.4813 50.9997 31.7961 50.6849C32.4257 50.0553 32.4257 49.1109 31.7961 48.4809L20.3056 36.9905L54.3049 36.9905C55.1707 36.9905 55.8793 36.2823 55.8793 35.4165C55.8793 34.5507 55.1707 33.8425 54.3049 33.8425L20.3056 33.8425L31.7961 22.3516C32.4257 21.722 32.4257 20.7776 31.7961 20.148C31.1665 19.5184 30.2221 19.5184 29.5925 20.148L15.426 34.3145C15.347 34.3935 15.2684 34.4721 15.1898 34.5507C15.1898 34.5507 15.1112 34.6293 15.1112 34.7083C15.1112 34.7869 15.0322 34.7869 15.0322 34.8655C15.0322 34.9441 15.0322 34.9441 14.9536 35.0231C14.9536 35.1017 14.9536 35.1017 14.875 35.1803C14.7964 35.4165 14.7964 35.5737 14.875 35.8099C14.875 35.8885 14.875 35.8885 14.9536 35.9675C14.9536 36.0461 14.9536 36.0461 15.0322 36.1247C15.1112 36.0461 15.1112 36.1247 15.1898 36.2033Z" fill="#818000"/>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="pagination">
                                <?php
                                echo paginate_links([
                                        'total'   => (int) $programs_q->max_num_pages,
                                        'current' => $paged,
                                        'format'  => '?paged=%#%',
                                    // preserve GET params (language + S&F fields already in querystring)
                                        'add_args' => array_filter([
                                                'language' => $language ?: null,
                                        ]),
                                ]);
                                ?>
                            </div>

                            <?php wp_reset_postdata(); ?>
                        <?php else: ?>
                            <p>Нічого не знайдено</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="ed-home-video">
        <div class="ed-home-video__container container">
            <div class="ed-home-week__title h1">
                <?= $data['zagolovok_bloku_vdeo']; ?>
            </div>
            <div class="ed-home-videoblock">
                <div class="ed-home-videoblock__bg">
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-src="<?= $data['kartinka_bloku_vdeo']; ?>"
                         decoding="async" alt="<?= $data['zagolovok_bloku_vdeo']; ?>">
                </div>
                <div class="teacher-card__play" data-youtube="<?= $data['posilannya_na_vdeo']; ?>">
                    <svg>
                        <use xlink:href="#ed-svg-play"></use>
                    </svg>
                </div>
            </div>
            <?php
                if ($data['posilannya_na_knopku_bloku_vdeo']){
                    ?>
                    <div class="ed-home-videoblock__btn">
                        <a href="<?= $data['posilannya_na_knopku_bloku_vdeo']; ?>" class="button"
                        ><?= $data['napis_na_knopc_bloku_vdeo']; ?></a>
                    </div>
                    <?php
                }
            ?>

        </div>
    </section>
    <section class="ed-home-about">
        <div class="container">
            <div class="about-teachers" id="ed-teachers">
                <div class="about-teachers-title h1"><?= $data['teachers_zagolovok']; ?></div>

                <?php if ($teachers_list = $data['teachers_vikladach']) : ?>
                    <div class="about-teachers-slider swiper">
                        <div class="swiper-wrapper">
                            <?php
                            foreach ($teachers_list as $item) {
                                get_template_part('parts/pt-cards/content', 'teacher-card', array(
                                    'id' => $item,
                                ));
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="about-bg-left">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/about-left.svg"
                 decoding="async" alt="icon">
        </div>

        <div class="about-bg-right">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/about-right.svg"
                 decoding="async" alt="icon">
        </div>
    </section>
    <section class="ed-home-faq">
        <div class="container">
            <div class="faq-title h1">
                <svg width="60" height="70" viewBox="0 0 60 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M51.5706 31.6454C43.42 20.2436 45.8237 15.4021 42.0863 6.18706C38.5794 -2.46402 29.8195 1.43857 28.7586 1.94851C27.7008 1.43557 18.947 -2.47302 15.4279 6.17206C11.6784 15.3811 14.076 20.2256 5.91021 31.6184C-2.25253 43.0082 -1.31592 56.0568 7.61672 63.814C15.7007 70.8332 27.4886 70.0203 28.7162 69.9153C29.9407 70.0233 41.7287 70.8512 49.8217 63.841C58.7634 56.0958 59.7182 43.0502 51.5706 31.6484V31.6454Z"
                          fill="#D3D360"/>
                    <path d="M51.2886 23.9675L47.904 31.5707H58.5975V32.9933H47.3153L41.1837 46.8753H39.614L45.7456 32.9933H36.6218L30.4901 46.8753H28.9695L35.0521 32.9933H27.1055V31.5707H35.6898L38.9763 23.9675H27.1055V22.545H39.6631L45.0589 10.1836H46.6777L41.2818 22.545H50.3566L55.7525 10.1836H57.3712L51.9754 22.545H58.5975V23.9675H51.2886ZM49.7189 23.9675H40.5951L37.2595 31.5707H46.3343L49.7189 23.9675Z"
                          fill="#161616"/>
                    <path d="M51.9767 22.5451L57.3726 10.1833H55.7535L50.3582 22.5451H41.2833L46.6792 10.1833H45.0601L39.6641 22.5451H27.1066V23.9671H38.9777L35.6909 31.5708H27.1066V32.9935H35.0531L28.9707 46.8752H30.4913L36.6229 32.9935H45.7472L39.6155 46.8752H41.1847L47.3164 32.9935H58.599V31.5708H47.9056L51.2896 23.9671H58.599V22.5451H51.9767ZM49.7204 23.9671L46.3357 31.5708H37.2607L40.5962 23.9671H49.7204ZM39.4061 30.1692H45.4255L47.5627 25.3686H41.5125L39.4061 30.1692ZM60.0005 25.3686H52.1998L50.0633 30.1692H60.0005V34.395H48.2299L42.0983 48.2767H37.464L43.5956 34.395H37.5365L31.4042 48.2767H26.8267L32.9091 34.395H25.7051V30.1692H34.7698L36.8454 25.3686H25.7051V21.1436H38.7464L44.1424 8.78174H48.8198L43.4239 21.1436H49.4405L54.8365 8.78174H59.5132L54.118 21.1436H60.0005V25.3686Z"
                          fill="#161616"/>
                </svg>
                <?= $data['zaglovok_bloku_faq']; ?>
            </div>
            <div class="faq-list">
                <?php
                $faqlist = $data['pitannya_vdpovd_bloku'];
                foreach ($faqlist as $item) { ?>
                    <div class="faq-item">
                        <div class="faq-item__header">
                            <?= $item['pitannya']; ?>
                            <svg width="33" height="18" viewBox="0 0 33 18" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <mask id="path-1-inside-1_1911_16460" fill="white">
                                    <path d="M0.838108 0.928805C0.683771 0.774468 0.433541 0.774467 0.279202 0.928806C0.124865 1.08314 0.124866 1.33337 0.279203 1.48771L16.208 17.4165L32.1368 1.48771C32.2912 1.33337 32.2912 1.08314 32.1368 0.928806C31.9825 0.774467 31.7322 0.774468 31.5779 0.928805L16.208 16.2987L0.838108 0.928805Z"/>
                                </mask>
                                <path d="M0.838108 0.928805C0.683771 0.774468 0.433541 0.774467 0.279202 0.928806C0.124865 1.08314 0.124866 1.33337 0.279203 1.48771L16.208 17.4165L32.1368 1.48771C32.2912 1.33337 32.2912 1.08314 32.1368 0.928806C31.9825 0.774467 31.7322 0.774468 31.5779 0.928805L16.208 16.2987L0.838108 0.928805Z"
                                      fill="#858523"/>
                                <path d="M16.208 17.4165L23.2791 24.4876L16.208 31.5587L9.13694 24.4876L16.208 17.4165ZM16.208 16.2987L23.2791 23.3698L16.208 30.4408L9.13694 23.3698L16.208 16.2987ZM0.838108 0.928805L-6.23296 7.99987C-2.48205 11.7508 3.59936 11.7508 7.35027 7.99987L0.279202 0.928806L-6.79187 -6.14226C-2.73229 -10.2018 3.8496 -10.2018 7.90918 -6.14226L0.838108 0.928805ZM0.279202 0.928806L7.35027 7.99987C11.1012 4.24897 11.1012 -1.83245 7.35027 -5.58336L0.279203 1.48771L-6.79187 8.55878C-10.8514 4.4992 -10.8514 -2.08268 -6.79187 -6.14226L0.279202 0.928806ZM0.279203 1.48771L7.35027 -5.58336L23.2791 10.3454L16.208 17.4165L9.13694 24.4876L-6.79187 8.55878L0.279203 1.48771ZM16.208 17.4165L9.13694 10.3454L25.0657 -5.58336L32.1368 1.48771L39.2079 8.55878L23.2791 24.4876L16.208 17.4165ZM32.1368 1.48771L25.0657 -5.58336C21.3148 -1.83245 21.3148 4.24897 25.0657 7.99987L32.1368 0.928806L39.2079 -6.14226C43.2675 -2.08268 43.2675 4.4992 39.2079 8.55878L32.1368 1.48771ZM32.1368 0.928806L25.0657 7.99987C28.8167 11.7508 34.8981 11.7508 38.649 7.99987L31.5779 0.928805L24.5068 -6.14226C28.5664 -10.2018 35.1483 -10.2018 39.2079 -6.14226L32.1368 0.928806ZM31.5779 0.928805L38.649 7.99987L23.2791 23.3698L16.208 16.2987L9.13694 9.22764L24.5068 -6.14226L31.5779 0.928805ZM16.208 16.2987L9.13694 23.3698L-6.23296 7.99987L0.838108 0.928805L7.90918 -6.14226L23.2791 9.22764L16.208 16.2987Z"
                                      fill="#858523" mask="url(#path-1-inside-1_1911_16460)"/>
                            </svg>
                        </div>
                        <div class="faq-item__content">
                            <?= $item['vdpovd']; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
    <section class="ed-home-about">
        <div class="about-bg-left">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/about-left.svg"
                 decoding="async" alt="icon">
        </div>

        <div class="about-bg-right">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php echo get_template_directory_uri(); ?>/assets/img/about-right.svg"
                 decoding="async" alt="icon">
        </div>
        <section class="ed-home-look ed-home-look-corporative ed-home-trust">
            <div class="container">
                <div class="look-title">
                    <div class="look-title__row ed-home-trust-title h1">
                        <span><?= $data['zagolovok_bloku_nam_dovryayut']; ?></span>
                    </div>
                </div>
                <div class="ed-home-trust__list swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        if ($trusts = $data['elementi_bloku_nam_dovryayut']) {
                            foreach ($trusts as $item) { ?>
                                <div class="ed-home-trust__item swiper-slide">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-src="<?= $item['logotip_elementu']; ?>"
                                         decoding="async" alt="icon">
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="ed-home-trust__nav">
                        <div class="ed-home-trust__prev">
                            <svg width="69" height="24" viewBox="0 0 69 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.57422 12.0249L20.5742 0.478023L20.5742 10.0249L68.918 10.0249L68.918 14.0249L20.5742 14.0249L20.5742 23.5718L0.57422 12.0249Z" fill="white"/>
                            </svg>
                        </div>
                        <div class="ed-home-trust__next">
                            <svg width="69" height="24" viewBox="0 0 69 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M68.8633 12.0249L48.8633 23.5718V14.0249H0.519531V10.0249H48.8633V0.478027L68.8633 12.0249Z" fill="white"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <section class="ed-home-questions ed-home-questions-corporative">
        <div class="container">
            <div class="questions-wrapper">
                <div class="questions-form">
                    <div class="questions-title h1"><?= $data['questions_zagolovok']; ?></div>
                    <div class="questions-subtitle h3"><?= $data['questions_pdzagolovok']; ?></div>

                    <form class="form">
                        <input type="hidden" name="action" value="ed_callback">
                        <input type="hidden" name="url" value="<?php echo admin_url('admin-ajax.php'); ?>">
                        <?php wp_nonce_field('ed_callback'); ?>
                        <input type="hidden" name="title" value="Залишилися питання">

                        <div class="form-row">
                            <input class="form-row__input required" type="text" name="name"
                                   placeholder="<?php the_field('common_form_data_plejsholder_v_pol_mya', 'options'); ?>">
                        </div>

                        <div class="form-row">
                            <input class="form-row__input required-phone" type="text" name="phone"
                                   placeholder="Введіть номер телефону">
                        </div>
                        <div class="form-row">
                            <div class="form-select">
                                <Label>
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <rect width="70" height="70" fill="white"/>
                                        <g clip-path="url(#clip0_776_7291)">
                                            <path d="M20.7633 52.8418C19.5292 54.01 19.19 56.0638 19.8966 57.8962C20.3865 59.1727 21.2815 60.1148 22.3743 60.8968C24.5082 62.423 26.9388 63.1766 29.4919 63.577C36.4917 64.6793 43.1711 63.7183 49.3277 60.1195C57.378 55.409 62.2486 48.4045 63.7277 39.2002C65.6731 27.0566 59.4035 14.8517 48.4563 9.11905C44.7633 7.18775 40.8489 6.10434 36.6942 5.86882C27.9986 5.37893 20.5514 8.30414 14.3759 14.3289C9.80674 18.785 7.12647 24.3057 6.16082 30.613C5.0303 37.9755 6.67426 44.7209 10.8902 50.854C11.7192 52.0646 12.5341 53.2846 13.3537 54.4999C13.415 54.5894 13.4621 54.6883 13.5139 54.7825C12.5671 55.3808 11.4319 55.2583 10.5086 54.3869C9.86797 53.7792 9.26032 53.1292 8.7139 52.4367C5.74158 48.6542 3.98928 44.3535 3.33923 39.5912C2.1993 31.2301 3.9516 23.5143 8.81282 16.6276C13.9049 9.40168 20.8811 4.88903 29.652 3.44291C45.4651 0.842724 60.5246 9.87273 65.3481 25.1065C69.1872 37.2265 66.4362 48.0607 57.7265 57.2979C52.8889 62.4324 46.7888 65.3576 39.8691 66.4646C34.6404 67.2983 29.4306 67.0439 24.3951 65.1786C22.5109 64.4767 20.768 63.5205 19.3266 62.0838C16.3826 59.1445 15.9681 55.3054 18.3374 51.8903C18.898 51.0895 18.9027 50.5667 18.3704 49.7847C17.3577 48.3009 17.4613 46.6098 17.7628 44.9329C18.7472 39.4122 20.7775 34.2401 23.1751 29.2093C25.926 23.4342 29.5531 18.2291 33.6983 13.3679C34.7158 12.1715 36.0206 11.5167 37.5562 11.366C38.8281 11.2388 40.1093 11.1822 41.3859 11.154C42.7707 11.121 43.2606 11.6345 43.3407 13.0382C43.4491 14.8564 43.0298 16.5946 42.4881 18.2951C42.1207 19.4539 41.6355 20.5844 41.1032 21.6725C40.4767 22.9491 39.3886 23.6085 37.9566 23.7404C36.4681 23.877 35.3093 24.6637 34.5415 25.8978C33.4487 27.6501 32.3935 29.4307 31.4609 31.2725C30.2361 33.6843 29.1245 36.1573 28.4932 38.8046C28.3755 39.2992 28.2719 39.7985 28.22 40.2978C28.107 41.3529 28.4461 42.2668 29.1998 43.011C29.8876 43.6893 30.0995 44.4807 29.7745 45.3851C28.6534 48.5176 26.8917 51.2073 24.202 53.2234C22.949 54.1608 22.4356 54.1325 21.1967 53.1668C21.0507 53.0538 20.9047 52.9454 20.7586 52.8418H20.7633Z"
                                                  fill="#858523"/>
                                            <path d="M41.1973 24.2774C43.9199 20.7587 44.8762 16.7595 44.9704 12.374C46.2234 13.09 47.6035 13.4763 48.5645 14.4985C48.979 14.9412 49.2616 15.6525 49.304 16.2602C49.5254 19.2231 48.7105 21.9033 46.8687 24.268C46.2469 25.0688 45.4461 25.3891 44.4852 25.1677C43.4065 24.9228 42.3466 24.5883 41.1973 24.2727V24.2774Z"
                                                  fill="#858523"/>
                                            <path d="M31.3381 44.9375C32.2614 45.7242 33.1611 46.4072 33.9619 47.1891C34.6214 47.8345 34.6873 48.7294 34.4706 49.5726C33.7735 52.2953 32.2049 54.4621 29.9957 56.1579C28.9923 56.9304 27.8477 57.0199 26.6983 56.4452C25.8975 56.0448 25.1156 55.6115 24.2441 55.1451C27.8053 52.5591 30.2453 49.2806 31.3428 44.9375H31.3381Z"
                                                  fill="#858523"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_776_7291">
                                                <rect width="64.0438" height="63.9213" fill="white"
                                                      transform="translate(3 3)"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <input type="checkbox" name="Call" value="Звонок">
                                    <span>
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 6L7 14L17 1" stroke="white" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </Label>
                                <Label>
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="34.9998" cy="34.9998" r="31.1111" stroke="#32ACDF"
                                                stroke-width="2"/>
                                        <path d="M47.9251 23.333L13.6113 35.4857L23.4152 40.3468L39.8613 32.083L30.1391 43.7497L43.0231 52.4997L47.9251 23.333Z"
                                              stroke="#32ACDF" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                    </svg>
                                    <input type="checkbox" name="Telegram" value="Telegram">
                                    <span>
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 6L7 14L17 1" stroke="white" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </Label>
                                <Label>
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M46.7045 43.395C47.325 41.5334 47.325 40.0855 47.1182 39.6718C46.9113 39.2581 46.4976 39.2581 45.6703 38.8445C44.8429 38.4308 41.1198 36.5692 40.4992 36.3624C39.8787 36.1555 39.0513 36.1555 38.6377 36.9829C37.8103 38.0171 37.1898 39.0513 36.5693 39.6718C36.1556 40.0855 35.3282 40.2923 34.7077 39.8787C33.8803 39.465 31.3982 38.6376 28.5025 36.1555C26.2272 34.0871 24.7793 31.605 24.1588 30.7777C23.7451 29.9503 24.1588 29.5366 24.5725 29.1229C24.9862 28.7092 25.3998 28.2956 25.8135 27.8819C26.2272 27.4682 26.434 27.2614 26.6409 26.6408C26.8477 26.2271 26.6409 25.6066 26.434 25.1929C26.2272 24.7793 24.7793 21.0561 24.1588 19.6082C23.7451 18.574 23.3314 18.574 22.5041 18.574C22.2972 18.3672 21.8835 18.3672 21.6767 18.3672C20.6425 18.3672 19.6083 18.574 18.9878 19.4014C18.1604 20.2288 16.2988 22.0903 16.2988 25.8135C16.2988 29.5366 18.9878 33.2597 19.4014 33.6734C19.8151 34.0871 24.7793 41.9471 32.4324 45.2565C38.4308 47.7386 40.2924 47.5318 41.5334 47.3249C43.6019 46.7044 46.0839 45.2565 46.7045 43.395Z"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M34.9148 3.88867C17.747 3.88867 3.88867 7.81865 3.88867 30.778C3.88867 45.6706 9.68022 52.4963 18.3676 55.599V64.7C18.3676 65.941 19.8154 66.5615 20.8496 65.7342L28.9165 57.6674C30.778 57.6674 32.8464 57.6674 34.9148 57.6674C52.0827 57.6674 65.941 53.7374 65.941 30.778C65.941 7.81865 52.0827 3.88867 34.9148 3.88867Z"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M33.8809 24.9863C36.5698 24.9863 38.6382 27.0547 38.6382 29.7437"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M33.8809 12.1621C43.6024 12.1621 51.4624 20.0221 51.4624 29.7436"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M33.8809 18.5742C40.0861 18.5742 45.0503 23.5384 45.0503 29.7436"
                                              stroke="#7B519B" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <input type="checkbox" name="Viber" value="Viber">
                                    <span>
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 6L7 14L17 1" stroke="white" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </Label>
                                <Label>
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M34.871 3.88867C18.9007 3.88867 5.83398 16.9553 5.83398 32.9257C5.83398 39.9776 8.53028 46.6146 12.6784 51.5924L7.90806 66.1109L23.0488 59.4739C26.5747 61.1331 30.7229 61.9628 34.871 61.9628C50.8414 61.9628 63.9081 48.8961 63.9081 32.9257C63.9081 16.9553 50.8414 3.88867 34.871 3.88867Z"
                                              stroke="#5CCF67" stroke-width="2" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.8405 43.5035C51.4627 41.6369 51.4627 40.185 51.2553 39.7702C51.0479 39.3554 50.633 39.3554 49.8034 38.9406C48.9738 38.5257 45.2405 36.6591 44.6182 36.4517C43.996 36.2443 43.1664 36.2443 42.7516 37.0739C41.9219 38.1109 41.2997 39.148 40.6775 39.7702C40.2627 40.185 39.433 40.3924 38.8108 39.9776C37.9812 39.5628 35.4923 38.7332 32.5886 36.2443C30.3071 34.1702 28.8553 31.6813 28.233 30.8517C27.8182 30.022 28.233 29.6072 28.6479 29.1924C29.0627 28.7776 29.4775 28.3628 29.8923 27.948C30.3071 27.5332 30.5145 27.3257 30.7219 26.7035C30.9293 26.2887 30.7219 25.6665 30.5145 25.2517C30.3071 24.8369 28.8553 21.1035 28.233 19.6517C27.8182 18.4072 27.4034 18.4072 26.5738 18.4072C26.3664 18.4072 25.9516 18.4072 25.7442 18.4072C24.7071 18.4072 23.6701 18.6146 23.0479 19.4443C22.2182 20.2739 20.3516 22.1406 20.3516 25.8739C20.3516 29.6072 23.0479 33.3406 23.4627 33.7554C23.8775 34.1702 28.8553 42.0517 36.5293 45.3702C42.5442 47.8591 44.4108 47.6517 45.6553 47.4443C47.7293 46.822 50.2182 45.3702 50.8405 43.5035Z"
                                              stroke="#5CCF67" stroke-width="2"/>
                                    </svg>
                                    <input type="checkbox" name="Whatsap" value="Whatsap">
                                    <span>
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 6L7 14L17 1" stroke="white" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </Label>
                                <!--                                <div>Оберіть месенджер</div>-->
                            </div>
                        </div>
                        <div class="form-row form-row--button">
                            <button class="button"
                                    type="submit"><?= $data['questions_tekst_knopki']; ?></button>
                        </div>
                        <div class="" style="margin-top: 20px">
                            <?php
                            $agreement_txt = get_field('common_form_data_tekst_poltiki', 'options');
                            $agreement_link = get_field('common_form_data_tekst_poltiki_link', 'options');
                            $agreement_link_txt = get_field('common_form_data_tekst_poltiki_2', 'options');
                            ?>

                            <div class="form-row__agreement"><?= $agreement_txt; ?> <a href="<?= $agreement_link; ?>"
                                                                                       target="_blank"><?= $agreement_link_txt; ?></a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="questions-trial">
                    <div class="questions-trial__button" data-popup="trial">
                        <svg viewBox="0 0 188 198" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.3786 174.642C-11.7504 89.8565 26.0552 40.2692 70.2724 13.4833C92.1599 0.422404 115.816 -2.23406 136.819 5.95669C158.265 14.3688 174.625 33.6281 181.921 58.6431C192.312 94.2839 185.237 133.688 163.792 161.359C145.442 184.825 118.469 197.222 88.4014 196.115C50.1536 193.901 28.4872 185.268 6.3786 174.642Z"
                                  stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>

<?php
    ?>
        <script>
            document.addEventListener('click', function (e) {
                const turnBtn = e.target.closest('.button-turn');
                if (turnBtn) {
                    e.preventDefault();
                    const card = turnBtn.closest('.programs__item');
                    if (card) card.classList.add('is-flipped');
                    return;
                }

                const backBtn = e.target.closest('.button-back');
                if (backBtn) {
                    e.preventDefault();
                    const card = backBtn.closest('.programs__item');
                    if (card) card.classList.remove('is-flipped');
                }
            });
        </script>
    <?php
    if ($data['vibr_tipu_tegchekboks'] == "checkbox") {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                document.querySelectorAll('.tabs').forEach(initTabsGroup);

                function initTabsGroup(groupEl) {
                    const boxes = [...groupEl.querySelectorAll('.tabs-header input[type="checkbox"][data-tab]')];
                    const contents = [...groupEl.querySelectorAll('.tabs-content')];
                    if (!boxes.length || !contents.length) return;

                    const showById = (rawId) => {
                        const id = String(rawId).replace(/^#/, '');
                        contents.forEach(el => el.classList.toggle('is-active', el.id === id));
                    };

                    const activate = (cb) => {
                        boxes.forEach(b => b.checked = (b === cb));
                        showById(cb.dataset.tab);
                    };

                    // Делегирование на всю группу (учтёт клик и по label, и по самому input)
                    groupEl.addEventListener('click', (e) => {
                        const cb = e.target.closest('input[type="checkbox"][data-tab]');
                        if (!cb || !groupEl.contains(cb)) return;
                        // Если клик снимает галку — снова включаем (радио-поведение)
                        if (!cb.checked) cb.checked = true;
                        activate(cb);
                    });

                    // Инициализация: если ничего не отмечено — отметить первый
                    activate(boxes.find(b => b.checked) || boxes[0]);
                }
            });
        </script>


        <?php
    }
?>
<?php get_footer(); ?>
