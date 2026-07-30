<?php
/**
 * Template Name: Шаблон вакансії
 */

// Окрема адреса для заявок із цієї сторінки.
$vacancies_recipient_email = 'educadorecruitment@gmail.com';

if (
    isset($_SERVER['REQUEST_METHOD'])
    && 'POST' === strtoupper(sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])))
    && isset($_POST['vacancies_form_action'])
    && 'submit' === sanitize_key(wp_unslash($_POST['vacancies_form_action']))
) {
    $nonce = isset($_POST['vacancies_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['vacancies_nonce']))
        : '';

    if (!wp_verify_nonce($nonce, 'submit_vacancies_form')) {
        wp_send_json_error(
            ['message' => 'Сесію форми завершено. Оновіть сторінку та спробуйте ще раз.'],
            403
        );
    }

    // Поле-пастка для автоматичних спам-ботів.
    $website = isset($_POST['company_website'])
        ? sanitize_text_field(wp_unslash($_POST['company_website']))
        : '';

    if ('' !== $website) {
        wp_send_json_success(['message' => 'Дякуємо! Заявку прийнято.']);
    }

    $candidate_name = isset($_POST['candidate_name'])
        ? sanitize_text_field(wp_unslash($_POST['candidate_name']))
        : '';
    $candidate_phone = isset($_POST['phone'])
        ? sanitize_text_field(wp_unslash($_POST['phone']))
        : '';
    $candidate_telegram = isset($_POST['telegram'])
        ? sanitize_text_field(wp_unslash($_POST['telegram']))
        : '';
    $vacancy_name = isset($_POST['vacancy'])
        ? sanitize_text_field(wp_unslash($_POST['vacancy']))
        : 'Загальна заявка';

    $communication_fields = [
        'Call' => 'Телефонний дзвінок',
        'Telegram' => 'Telegram',
        'Viber' => 'Viber',
        'Whatsap' => 'WhatsApp',
    ];
    $selected_communication = [];

    foreach ($communication_fields as $field_name => $communication_label) {
        if (isset($_POST[$field_name])) {
            $selected_communication[] = $communication_label;
        }
    }

    $phone_digits = preg_replace('/\D+/', '', $candidate_phone);

    if (strlen(trim($candidate_name)) < 2) {
        wp_send_json_error(['message' => 'Вкажіть, будь ласка, ваше ім’я.'], 422);
    }

    if (strlen($phone_digits) < 7) {
        wp_send_json_error(['message' => 'Вкажіть коректний номер телефону.'], 422);
    }

    if (!$selected_communication) {
        $selected_communication[] = 'Не обрано';
    }

    $tracking_keys = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'utm_id',
        'utm_source_platform',
        'utm_creative_format',
        'utm_marketing_tactic',
        'gclid',
    ];
    $tracking_data = [];

    foreach ($tracking_keys as $tracking_key) {
        if (!empty($_POST[$tracking_key])) {
            $tracking_data[$tracking_key] = sanitize_text_field(
                wp_unslash($_POST[$tracking_key])
            );
        }
    }

    $visitor_ip = '';

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded_ips = explode(',', wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
        $visitor_ip = sanitize_text_field(trim((string) ($forwarded_ips[0] ?? '')));
    }

    if (!$visitor_ip && !empty($_SERVER['REMOTE_ADDR'])) {
        $visitor_ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    }

    $visitor_country = '';

    if ($visitor_ip) {
        $country_response = wp_remote_get(
            'http://ip-api.com/json/' . rawurlencode($visitor_ip) . '?lang=ru',
            ['timeout' => 2]
        );

        if (!is_wp_error($country_response)) {
            $country_data = json_decode(wp_remote_retrieve_body($country_response), true);

            if (is_array($country_data) && !empty($country_data['country'])) {
                $visitor_country = sanitize_text_field($country_data['country']);
            }
        }
    }

    $recipient = is_email($vacancies_recipient_email)
        ? $vacancies_recipient_email
        : get_option('admin_email');

    if (!is_email($recipient)) {
        wp_send_json_error(['message' => 'Не налаштовано адресу для отримання заявок.'], 500);
    }

    $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
    $mail_subject = sprintf(
        '[%s] Нова заявка на вакансію: %s',
        $site_name,
        $vacancy_name ?: 'Загальна заявка'
    );
    $tracking_html = '';

    foreach ($tracking_data as $tracking_key => $tracking_value) {
        $tracking_html .= '<p><strong>' . esc_html($tracking_key) . ':</strong> '
            . esc_html($tracking_value) . '</p>';
    }

    if ($tracking_html) {
        $tracking_html = '<hr><h3>Аналітика</h3>' . $tracking_html;
    }

    $mail_body = '<h2>Нова заявка зі сторінки вакансій</h2>'
        . '<p><strong>Ім’я:</strong> ' . esc_html($candidate_name) . '</p>'
        . '<p><strong>Телефон:</strong> ' . esc_html($candidate_phone) . '</p>'
        . '<p><strong>Telegram:</strong> ' . esc_html($candidate_telegram ?: 'не вказано') . '</p>'
        . '<p><strong>Зручний зв’язок:</strong> ' . esc_html(implode(', ', $selected_communication)) . '</p>'
        . '<p><strong>Вакансія:</strong> ' . esc_html($vacancy_name ?: 'Загальна заявка') . '</p>'
        . ($visitor_ip ? '<p><strong>IP:</strong> ' . esc_html($visitor_ip) . '</p>' : '')
        . ($visitor_country ? '<p><strong>Країна:</strong> ' . esc_html($visitor_country) . '</p>' : '')
        . $tracking_html
        . '<p><strong>Сторінка:</strong> Вакансії</p>'
        . '<p><strong>URL:</strong> <a href="' . esc_url(get_permalink()) . '">'
        . esc_html(get_permalink()) . '</a></p>';

    $mail_sent = wp_mail(
        $recipient,
        $mail_subject,
        $mail_body,
        ['Content-Type: text/html; charset=UTF-8']
    );

    if (!$mail_sent) {
        wp_send_json_error(
            ['message' => 'Не вдалося відправити заявку. Спробуйте ще раз трохи пізніше.'],
            500
        );
    }

    wp_send_json_success(['message' => 'Дякуємо! Наш HR зв’яжеться з вами.']);
}

get_header();
?>
<main class="ed-home vacancies">
    <?php
    $data = get_fields();
    ?>
    <section class="ed-home-banner ed-home-banner-vacancies">
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title h1"><?= $data['zagolovok_baneru']; ?></h1>
                <div class="banner-subtitle h3"><?= $data['pdzagolovok_baneru']; ?></div>
                <div class="banner-desc"><?= $data['punkti_baneru']; ?></div>
                <button
                    type="button"
                    class="button vacancies-form callpopup"
                    data-popup="trial"
                    data-vacancy="Заявка зі сторінки вакансії"
                    aria-haspopup="dialog"
                >
                    Заповнити анкету
                </button>
            </div>
            <?php
            $banner_desktop_image = !empty($data['zobrazhennya_banera_pk'])
                ? $data['zobrazhennya_banera_pk']
                : '';
            $banner_mobile_image = !empty($data['zobrazhennya_banera_mobajl'])
                ? $data['zobrazhennya_banera_mobajl']
                : '';
            $banner_desktop_image = $banner_desktop_image ?: $banner_mobile_image;
            $banner_mobile_image = $banner_mobile_image ?: $banner_desktop_image;
            ?>
            <?php if ($banner_desktop_image || $banner_mobile_image) { ?>
                <div class="banner-world">
                    <div class="banner-world__responsive-image banner-world__responsive-image--desktop">
                        <img
                            src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                            data-src="<?= esc_url($banner_desktop_image); ?>"
                            alt="<?= esc_attr($data['zagolovok_baneru']); ?>"
                        >
                    </div>
                    <div class="banner-world__responsive-image banner-world__responsive-image--mobile">
                        <img
                            src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                            data-src="<?= esc_url($banner_mobile_image); ?>"
                            alt="<?= esc_attr($data['zagolovok_baneru']); ?>"
                        >
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <section class="page-vacancy-2">
        <div class="page-vacancy-2__block">
            <div class="container">
                <div class="page-vacancy-2__title h1">
                    <?= $data['zagolovok_blo2']; ?>
                </div>
                <div class="page-vacancy-2__desc">
                    <?= $data['pdzagolovok_blok_2']; ?>
                </div>
                <div class="page-vacancy-2__content">
                    <?= $data['blok_opisu_bloku_2']; ?>
                </div>
            </div>
        </div>
        <div class="page-vacancy-2__bg">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?= $data['zobrazhennya_bloku_2']; ?>"
                 class="language__image-active" alt="<?= $data['zagolovok_blo2']; ?>">
        </div>
    </section>
    <section class="page-vacancy-3">
        <div class="page-vacancy-3_block">
            <div class="container">
                <div class="page-vacancy-3__title h1">
                    <?= $data['zagolovok_bloku_garyach_vakans']; ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/vacancies/fire.svg" width="" height="" alt="" />
                </div>
                <?php if ($perel = get_field('garyach_vakans_perelk_punktv')) : ?>
                    <div class="page-vacancy-3__list">
                        <?php foreach ($perel as $item) : ?>
                            <?php
                                if ($item['posilannya_na_vkansyu_yaksho']){
                                    ?>
                                    <a href="<?php $item['posilannya_na_vkansyu_yaksho'] ?>" class="page-vacancy-3__item">
                                        <div class="page-vacancy-3__item-title"><?= $item['nazva_vakans']; ?></div>
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <div class="page-vacancy-3__item">
                                        <div class="page-vacancy-3__item-title"><?= $item['nazva_vakans']; ?></div>
                                    </div>
                                    <?php
                                }
                            ?>

                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <section class="ed-home-courses ed-home-courses-platform">
        <div class="ed-home-courses__bg">
            <svg width="1871" height="352" viewBox="0 0 1871 352" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_2030_7797" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="1871" height="352">
                    <rect x="0.557617" y="0.936523" width="1869.54" height="350.123" fill="#D9D9D9"/>
                </mask>
                <g mask="url(#mask0_2030_7797)">
                    <path d="M1518.9 213.873C1518.9 213.873 1735.25 93.4543 1559.22 23.9492C1508.7 3.9996 1401.38 -72.8526 1430.52 -109.383C1499.3 -195.582 2011.68 -99.204 2265.63 -98.4408" stroke="#161616" stroke-width="2"/>
                    <g clip-path="url(#clip0_2030_7797)">
                        <path d="M1499.37 196.61C1500.53 181.699 1501.57 166.012 1500.04 150.381M1343.86 292.243C1352.71 300.776 1362.47 308.096 1377.11 313.071C1413.22 325.352 1440.3 320.414 1463.41 303.079C1488.05 284.601 1495.55 255.406 1498.08 214.59C1498.26 211.674 1498.47 208.739 1498.7 205.776M1314.37 257.529C1314.92 258.923 1315.53 260.293 1316.2 261.641C1320.26 269.805 1326.32 276.94 1333.3 282.764C1334.76 283.982 1336.64 285.819 1338.59 287.656M1366.61 157.701C1364.38 159.496 1362.01 161.125 1359.48 162.542C1354.56 165.297 1349.27 167.318 1344.49 170.346C1339.9 173.258 1335.62 176.77 1331.78 180.619C1317.86 194.58 1309.8 213.894 1309.96 233.632C1310 238.861 1310.56 244.108 1311.76 249.194M1395.56 119.534C1390.64 126.918 1385.88 134.727 1380.82 142.014C1378.19 145.803 1375.04 149.592 1371.82 152.883M1498.83 141.16C1497.68 134.196 1495.94 127.186 1493.42 120.157C1492.6 117.545 1491.59 114.97 1490.31 112.45C1488.22 108.292 1485.41 104.526 1482.12 101.24C1478.28 97.5342 1473.88 94.4698 1469.12 92.0515C1464.72 89.8086 1459.52 89.0102 1454.66 88.4379C1444.25 87.2103 1433.29 88.3595 1423.59 92.4484C1418.28 94.6867 1413.81 97.8203 1409.85 101.549" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1478.89 126.184C1476.06 116.553 1470.31 108.186 1459.45 102.989C1459.45 102.989 1443.73 97.9957 1427.16 105.924" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1398.98 136.186C1387.98 153.69 1380.43 167.365 1362 175.303C1339.11 185.16 1318.45 213.137 1327.35 246.841C1336.25 280.544 1393.47 306.296 1441.79 278.001C1478.03 252.882 1476.12 200.741 1478.35 178.169C1478.73 174.31 1479.3 169.991 1479.84 165.408" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1365.31 248.784C1364.83 248.059 1348.94 235.059 1356.65 207.128C1364.35 179.198 1400.46 173.66 1420.92 182.807C1441.39 191.959 1445.96 216.275 1438.26 232.89C1430.55 249.504 1414.18 257.386 1397.33 257.931C1374.94 258.655 1365.31 248.784 1365.31 248.784Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1374.68 131.676C1375.02 129.073 1371.89 128.441 1370.12 129.581C1369.45 128.994 1368.77 128.487 1368.23 128.085C1366.81 127.033 1363.42 125.15 1363.15 128.418C1363.07 129.47 1363.7 130.102 1364.43 130.684C1364.25 130.665 1364.08 130.647 1363.9 130.642C1362.54 130.619 1361.76 132.423 1362.77 133.369C1362.89 133.485 1363.02 133.596 1363.15 133.711C1362.87 133.748 1362.6 133.794 1362.31 133.849C1361.1 134.08 1360.65 135.862 1361.57 136.665C1362.78 137.726 1364.08 138.506 1365.57 139.014C1365.72 140.551 1365.9 142.106 1366.07 143.68C1365.79 149.236 1366.19 154.848 1366.61 160.391C1366.88 163.977 1372.56 163.94 1372.19 160.391C1371.99 155.314 1371.76 150.168 1371.04 145.124C1370.8 142.996 1370.53 140.874 1370.26 138.751C1370.24 138.603 1370.21 138.46 1370.17 138.326C1372.25 136.688 1374.35 134.14 1374.68 131.685V131.676Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1544.97 179.825C1543.82 179.082 1542.48 178.662 1541.07 178.431C1542.06 178.02 1543.03 177.531 1543.95 176.908C1545.02 176.179 1544.76 174.084 1543.22 174.222C1539.55 174.554 1535.95 175.422 1532.62 176.876C1532.74 176.345 1532.8 175.805 1532.89 175.251C1533.09 174.102 1533.53 173.23 1534.12 172.238C1534.98 170.793 1535.84 169.556 1535.51 167.844C1535.3 166.681 1533.68 166.372 1532.92 167.161C1530.3 169.875 1528.62 172.884 1526.91 176.211C1525.74 178.491 1523.9 180.305 1521.93 181.957C1519.39 182.843 1516.57 184.86 1514.33 186.29C1511.63 188.016 1509.5 189.438 1506.88 191.284C1504.37 193.051 1501.87 194.833 1499.37 196.6C1496.73 198.46 1493.76 200.237 1491.69 202.738C1491.24 203.274 1491.09 203.74 1490.99 204.492C1490.61 206.564 1493.09 209.079 1495.64 207.833C1498.25 206.56 1500.42 204.594 1502.63 202.748C1504.98 200.782 1507.36 198.843 1509.77 196.933C1511.77 195.345 1513.77 193.771 1515.78 192.188C1516.32 191.828 1516.86 191.468 1517.41 191.113C1519.76 189.581 1522.38 188.279 1524.59 186.517C1526.59 185.566 1528.65 184.878 1531.04 184.781C1534.45 184.638 1537.77 185.783 1541.16 186.12C1542.57 186.263 1543.17 184.006 1541.89 183.411C1540.94 182.968 1539.97 182.584 1538.98 182.238C1540.73 182.271 1542.48 182.377 1544.24 182.501C1545.75 182.608 1546.09 180.545 1544.97 179.825Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1324.31 261.262C1323.58 258.06 1322.51 254.631 1320.08 252.272C1317.63 249.877 1314.21 249.125 1310.88 249.42C1305.32 249.919 1300.52 252.766 1296.79 256.712C1296.06 255.36 1295.2 254.086 1294.2 252.946C1292.15 250.634 1288.51 249.014 1285.78 251.262C1282.73 253.777 1285.73 256.629 1287.95 258.263C1287.95 258.267 1287.96 258.272 1287.96 258.277C1289.02 259.643 1290.1 260.986 1291.24 262.282C1292.67 263.921 1294.38 266.385 1296.9 265.531C1297.52 265.319 1298.01 264.955 1298.36 264.488C1301.82 261.706 1303.89 258.078 1308.65 257.095C1313.03 256.191 1317.69 258.263 1319 262.721C1320.01 266.122 1325.09 264.715 1324.31 261.262Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1361.92 295.608C1361.19 292.405 1360.12 288.976 1357.7 286.618C1355.24 284.223 1351.83 283.471 1348.49 283.766C1342.93 284.264 1338.13 287.112 1334.4 291.058C1333.67 289.706 1332.81 288.432 1331.81 287.292C1329.76 284.98 1326.12 283.36 1323.39 285.607C1320.34 288.123 1323.34 290.975 1325.56 292.608C1325.56 292.613 1325.57 292.618 1325.57 292.622C1326.63 293.988 1327.71 295.331 1328.85 296.628C1330.28 298.266 1331.99 300.731 1334.51 299.877C1335.13 299.665 1335.62 299.3 1335.97 298.834C1339.43 296.051 1341.5 292.424 1346.26 291.441C1350.64 290.536 1355.3 292.608 1356.62 297.067C1357.62 300.468 1362.71 299.06 1361.92 295.608Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1505.78 152.024C1505.07 148.508 1502.27 145.194 1500.01 142.522C1498.48 140.722 1496.66 138.009 1494.35 136.942C1493.44 134.335 1490.62 132.927 1488.22 131.437C1484.94 129.411 1481.57 127.514 1478.05 125.936C1474.58 124.376 1470.96 123.171 1467.22 122.428C1463.89 121.764 1459 120.868 1455.9 122.728C1455.64 122.885 1455.42 123.083 1455.24 123.31C1454.05 122.017 1452.32 121.32 1450.7 120.753C1450.41 120.651 1449.85 120.499 1449.17 120.342C1448.73 120.065 1448.36 119.853 1448.13 119.756C1446.55 119.082 1444.8 118.422 1443.06 118.579C1443.07 118.288 1443.04 117.998 1442.96 117.702C1441.93 114.241 1437.72 111.601 1434.81 109.843C1431.55 107.872 1428.06 106.312 1424.45 105.122C1420.79 103.917 1417.02 103.04 1413.24 102.325C1410.51 101.808 1407.53 100.973 1405.13 102.186C1402.86 103.82 1401.25 106.543 1400.78 107.378C1400.8 107.378 1398.14 112.303 1397.16 115.035C1395.31 120.171 1392.98 125.492 1395.27 130.897C1397.28 135.655 1401.44 139.351 1405.92 141.733C1410.52 144.17 1415.97 145.711 1421.19 145.771C1426.56 145.836 1431.14 143.547 1434.64 139.518C1436.43 137.455 1437.77 135.013 1438.9 132.54C1440.05 130.001 1441.33 127.177 1441.95 124.408C1442.1 124.334 1442.25 124.242 1442.4 124.117C1442.89 123.679 1443.21 123.54 1443.76 123.582C1444.84 123.674 1445.93 124.26 1447 124.477C1447.18 124.514 1447.44 124.565 1447.77 124.625C1448.01 124.791 1448.21 124.92 1448.35 125.003C1449.28 125.566 1450.48 125.866 1451.34 126.531C1451.77 126.863 1451.91 127.182 1451.98 127.842C1452 128.031 1452.05 128.202 1452.11 128.358C1450.71 130.823 1449.74 133.772 1448.88 136.426C1448.04 139.01 1447.38 141.719 1447.31 144.451C1447.17 149.786 1449.01 154.567 1453.01 158.144C1456.9 161.619 1461.97 164.167 1467 165.468C1471.92 166.737 1477.48 166.816 1482.18 164.665C1487.51 162.224 1489.38 156.723 1491.49 151.688C1492.6 149.006 1493.78 146.269 1493.93 143.542C1494.25 143.86 1494.57 144.174 1494.88 144.497C1495.84 145.49 1496.76 146.514 1497.67 147.543C1499.43 149.532 1501.19 151.997 1503.43 153.455C1504.45 154.115 1506.04 153.377 1505.76 152.043L1505.78 152.024ZM1435.56 127.615C1434.7 129.895 1433.77 132.175 1432.46 134.238C1430.32 137.612 1427.11 140.473 1422.95 140.658C1418.97 140.833 1414.67 139.993 1410.96 138.604C1407.42 137.284 1404.07 135.143 1401.78 132.101C1398.89 128.275 1398.81 124.297 1400.41 119.913C1401.18 117.79 1401.96 115.649 1402.81 113.553C1403.53 111.795 1404.44 109.824 1406.39 109.211C1406.93 109.044 1407.47 108.934 1408.01 108.864C1408.63 108.809 1409.25 108.791 1409.86 108.804C1413.09 108.943 1416.39 110.254 1419.42 110.821C1422.07 111.717 1424.66 112.806 1427.15 114.006C1429.34 115.062 1431.48 116.235 1433.52 117.555C1434.9 118.445 1436.76 119.558 1437.2 121.265C1437.69 123.203 1436.23 125.834 1435.56 127.611L1435.56 127.615ZM1488.32 146.763C1487.53 148.882 1486.67 150.991 1485.81 153.081C1484.03 157.392 1481.28 160.272 1476.57 161.148C1472.83 161.845 1468.91 161.158 1465.41 159.75C1461.73 158.269 1458 155.989 1455.18 153.174C1452.23 150.229 1451.79 145.956 1452.49 142.019C1452.92 139.615 1453.77 137.302 1454.67 135.041C1455.38 133.273 1456.08 130.352 1457.75 129.254C1459.22 128.289 1461.34 128.723 1462.96 128.995C1465.36 129.401 1467.73 129.978 1470.06 130.68C1472.71 131.474 1475.36 132.424 1477.92 133.55C1480.53 135.17 1483.86 136.439 1486.33 138.512C1486.79 138.913 1487.23 139.347 1487.65 139.799C1488.01 140.215 1488.33 140.662 1488.61 141.147C1489.64 142.91 1488.98 144.982 1488.32 146.763H1488.32Z" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                        <path d="M1443.24 159.431C1437.02 165.07 1425.69 159.772 1425.22 151.673" stroke="#161616" stroke-width="2" stroke-miterlimit="10"/>
                    </g>
                    <path d="M0.557617 124.954L48.5265 65.8449C95.3255 6.96455 188.923 -108.61 282.521 -134.067C376.119 -158.588 469.717 -92.9915 563.315 -67.5339C656.913 -43.0135 750.511 -58.6316 844.108 -42.5449C937.706 -25.8335 1031.3 24.1445 1124.9 24.1445C1218.5 24.1445 1312.1 -25.8335 1405.7 -9.12213C1499.29 6.96454 1592.89 91.3025 1686.49 124.101C1780.09 156.899 1873.68 141.28 1967.28 90.8339C2060.88 41.3244 2154.48 -43.0135 2201.28 -84.0892L2248.08 -125.79" stroke="#161616" stroke-width="2"/>
                </g>
                <defs>
                    <clipPath id="clip0_2030_7797">
                        <rect width="264.414" height="235.165" fill="white" transform="translate(1282.85 86.3447)"/>
                    </clipPath>
                </defs>
            </svg>
        </div>
        <div class="ed-home-whyus">
            <div class="container">
                <div class="ed-home-courses-language__title h1">
                    <?= $data['zagolovok_nad_perelkom']; ?>
                </div>
                <?php if ($perel = get_field('perelk_punktv')) : ?>
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
        <div class="container">
            <div class="ed-home-courses-language__title h1">
                <?= $data['zagolovok_nad_akardeonom']; ?>
            </div>
            <?php if ($accardiontab = $data['papki_s_perelkom']) : ?>
                <div class="ed-accordions" id="ed-courses">
                    <?php
                    $item_id = $args['id'] ?? '';
                    $count = count($accardiontab);
                    $counter = $data['papki_s_perelkom'] ?? 0;
                    $add_class = '';
                    $i = 1;
                    foreach ($accardiontab as $item) {
                        if ($i === $count) {
                            $add_class = ' active';
                        } else {
                            $add_class = '';
                        }
                        ?>
                        <div class="accordion<?= $add_class; ?>" id="accordion-<?= $i; ?>">
                            <div class="accordion-body">
                                <div class="accordion-title h2">
                                    <svg width="43" height="28" viewBox="0 0 43 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.46094 10.0148L15.9492 24.5139L40.2247 2.25977" stroke="#161616" stroke-width="4" stroke-linecap="round"/>
                                    </svg>
                                    <span><?= $item['zagolovok_vkladki'] ?></span>
                                </div>

                                <div class="accordion-block">
                                    <?= $item['opis_vkladki'] ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <section class="page-vacancy-4">
        <div class="page-vacancy-4__abs">
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?= $data['zobrazhennya_sprava']; ?>"
                 class="language__image-active" alt="<?= $data['zaggolovok_bloku_5_krokv_do_dealno_roboti']; ?>">
        </div>
        <div class="page-vacancy-4__block">
            <div class="container">
                <div class="page-vacancy-4__title h1">
                    <?= $data['zaggolovok_bloku_5_krokv_do_dealno_roboti']; ?>
                </div>
            </div>
        </div>
        <?php
        $steps_desktop_image = !empty($data['zobrazhennya_krokv_pk'])
            ? $data['zobrazhennya_krokv_pk']
            : '';
        $steps_mobile_image = !empty($data['zobrazhennya_krokv_mob'])
            ? $data['zobrazhennya_krokv_mob']
            : '';

        // На production desktop.svg завантажено, але ACF-поле для нього порожнє.
        if (!$steps_desktop_image && $steps_mobile_image) {
            $steps_desktop_candidate = preg_replace(
                '/mobile(\.[a-z0-9]+)$/i',
                'desktop$1',
                $steps_mobile_image
            );
            $uploads = wp_get_upload_dir();

            if (
                $steps_desktop_candidate !== $steps_mobile_image
                && !empty($uploads['baseurl'])
                && !empty($uploads['basedir'])
                && 0 === strpos($steps_desktop_candidate, $uploads['baseurl'])
            ) {
                $steps_desktop_path = wp_normalize_path(
                    $uploads['basedir'] . substr(
                        $steps_desktop_candidate,
                        strlen($uploads['baseurl'])
                    )
                );

                if (is_readable($steps_desktop_path)) {
                    $steps_desktop_image = $steps_desktop_candidate;
                }
            }
        }

        $steps_default_image = $steps_mobile_image ?: $steps_desktop_image;
        ?>
        <div class="page-vacancy-4__bg">
            <?php if ($steps_default_image) { ?>
                <picture>
                    <?php if ($steps_desktop_image) { ?>
                        <source
                            srcset="<?= esc_url($steps_desktop_image); ?>"
                            media="(min-width: 500px)"
                        >
                    <?php } ?>
                    <img
                        src="<?= esc_url($steps_default_image); ?>"
                        alt="<?= esc_attr($data['zaggolovok_bloku_5_krokv_do_dealno_roboti']); ?>"
                    >
                </picture>
            <?php } ?>
        </div>
    </section>

    <section class="page-vacancy-6">
        <div class="page-vacancy-6__block">
            <div class="container">
                <div class="page-vacancy-6__title h1">
                    <?= $data['zagolovok_referalna_programa']; ?>
                </div>
                <div class="page-vacancy-6__desc">
                    <?= $data['kontent_referalna_programa']; ?>
                </div>
                <div class="page-vacancy-6__bg">
                    <img src="<?= $data['zobrazhennya_referalna_programa']; ?>" alt="<?= $data['zagolovok_referalna_programa']; ?>" />
                </div>
            </div>
        </div>
    </section>
    <section class="page-vacancy-5">
        <div class="page-vacancy-5__block">
            <div class="container">
                <div class="page-vacancy-5__title h1">
                    <?= $data['zagolovok_kursi_dlya_vchitelv']; ?>
                </div>
                <div class="page-vacancy-5__desc">
                    <?= $data['kontent_kursi_dlya_vchitelv']; ?>
                    <div class="button callpopup" data-popup="callback">Детальніше </div>
                </div>
                <div class="page-vacancy-5__bg">
                    <img src="<?= $data['zobrazhennya_kursi_dlya_vchitelv']; ?>" alt="<?= $data['zagolovok_kursi_dlya_vchitelv']; ?>" />
                </div>
            </div>
        </div>
    </section>
    <section class="ed-home-faq block-faq">
        <div class="container">
            <div class="faq-title h1">
                <svg width="60" height="72" viewBox="0 0 60 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M51.2562 33.2392C43.1056 21.8374 45.5092 16.9959 41.7719 7.78089C38.2649 -0.870194 28.545 0.0272379 28.545 0.0272379C28.545 0.0272379 18.6325 -0.879194 15.1134 7.76589C11.3639 16.9749 13.7615 21.8194 5.59576 33.2122C-2.56698 44.602 -1.63037 57.6506 7.30227 65.4078C15.3862 72.4271 27.1741 71.6141 28.4017 71.5091C29.6263 71.6171 41.4142 72.445 49.5072 65.4348C58.449 57.6896 59.4038 44.644 51.2562 33.2422V33.2392Z" fill="#D3D360"/>
                    <path d="M50.9742 25.5613L47.5895 33.1645H58.2831V34.587H47.0009L40.8693 48.469H39.2996L45.4312 34.587H36.3073L30.1757 48.469H28.655L34.7376 34.587H26.791V33.1645H35.3753L38.6619 25.5613H26.791V24.1387H39.3486L44.7444 11.7773H46.3632L40.9674 24.1387H50.0422L55.438 11.7773H57.0568L51.6609 24.1387H58.2831V25.5613H50.9742ZM49.4045 25.5613H40.2806L36.945 33.1645H46.0198L49.4045 25.5613Z" fill="#161616"/>
                    <path d="M51.6622 24.1388L57.0582 11.777H55.4391L50.0438 24.1388H40.9688L46.3648 11.777H44.7457L39.3497 24.1388H26.7921V25.5609H38.6633L35.3764 33.1645H26.7921V34.5872H34.7386L28.6563 48.469H30.1769L36.3085 34.5872H45.4327L39.3011 48.469H40.8703L47.0019 34.5872H58.2845V33.1645H47.5911L50.9751 25.5609H58.2845V24.1388H51.6622ZM49.406 25.5609L46.0212 33.1645H36.9463L40.2817 25.5609H49.406ZM39.0917 31.763H45.1111L47.2483 26.9624H41.1981L39.0917 31.763ZM59.686 26.9624H51.8853L49.7488 31.763H59.686V35.9888H47.9155L41.7839 49.8705H37.1495L43.2812 35.9888H37.2221L31.0898 49.8705H26.5122L32.5946 35.9888H25.3906V31.763H34.4553L36.5309 26.9624H25.3906V22.7373H38.432L43.828 10.3755H48.5054L43.1094 22.7373H49.1261L54.522 10.3755H59.1988L53.8035 22.7373H59.686V26.9624Z" fill="#161616"/>
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
</main>

<?php get_footer(); ?>
