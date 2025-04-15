<?php
/**
 * Change name from email
 */
add_filter('wp_mail_from_name', 'ed_change_wp_name_from');
function ed_change_wp_name_from($name): string
{
    return trim(get_option('blogname'));
}


/**
 * Form processing
 */
function ed_callback()
{
    // if (!wp_verify_nonce($_POST['_wpnonce'], 'ed_callback')) {
    //     echo 0;
    // } else {
    $to = trim(get_field('get_letter_from_site', 'options'));
    if (!$to) {
        $to = trim(get_option('admin_email'));
    }

    $type = '';
    if (isset($_REQUEST['title']) && !empty($_REQUEST['title'])) {
        $type = html_entity_decode(htmlspecialchars($_REQUEST['title']));
    }

    $site_name = trim(get_option('blogname'));
    $site_url = trim(get_option('siteurl'));
    $site_url_without_protocol = str_replace(array('http://', 'https://'), '', $site_url);
    $mes = '';
    $subject = "{$site_name}: {$type}";


    foreach ($_REQUEST as $key => $value) {
        if ($key != 'title' && $key != 'action' && $key != 'url' && $key != '_wpnonce' && $key != '_wp_http_referer' && $value) {
            $mes .= '<p>' . htmlspecialchars($key) . ': ' . html_entity_decode(htmlspecialchars($value)) . '</p>';
        }
    }

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $real_ip_adress = $_SERVER['REMOTE_ADDR'];
    }

    $cip = $real_ip_adress;
    $ch = curl_init('http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR'] . '?lang=ru');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    $res = json_decode($res, true);
    $ipadress = '<div>IP:'.$cip.'</div><br>';
    $location = '<div>Країна:'.$res['country'].'</div><br>';
    $utm_source = '';
    if (isset($_POST['utm_source'])) {
        $utm_source = '<div>utm_source:'.$_POST['utm_source'].'</div><br>';
    }
    $utm_medium = '';
    if (isset($_POST['utm_medium'])) {
        $utm_medium = '<div>utm_medium:'.$_POST['utm_medium'].'</div><br>';
    }
    $utm_campaign = '';
    if (isset($_POST['utm_campaign'])) {
        $utm_campaign = '<div>utm_campaign:'.$_POST['utm_campaign'].'</div><br>';
    }
    $utm_content = '';
    if (isset($_POST['utm_content'])) {
        $utm_content = '<div>utm_content:'.$_POST['utm_content'].'</div><br>';
    }
    $utm_term = '';
    if (isset($_POST['utm_term'])) {
        $utm_term = '<div>utm_term:'.$_POST['utm_term'].'</div><br>';
    }
    $message = "
                <html>
                    <head>
                        <title>'.$subject.'</title>
                    </head>
                    <body>
                        $ipadress
                        $location
                        $utm_source
                        $utm_medium
                        $utm_campaign
                        $utm_content$utm_content
                        $utm_term
                        $mes
                    </body>
                </html>";
    $email = "no-reply@{$site_url_without_protocol}";
    $headers = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: $email\r\n";

    $result = wp_mail($to, $subject, $message, $headers);
    echo $result;
    // }
    wp_die();
}

add_action('wp_ajax_ed_callback', 'ed_callback');
add_action('wp_ajax_nopriv_ed_callback', 'ed_callback');
