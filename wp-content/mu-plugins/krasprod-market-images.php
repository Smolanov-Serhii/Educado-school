<?php
/**
 * Plugin Name: Krasprod: зменшені картинки карток магазину курсів
 * Description: Шаблон template-market.php віддавав оригінал 1080×1350, а на екрані картка 392×490.
 *              Функція повертає версію 768×960 (medium_large), а якщо поруч є webp-копія від Robin Image
 *              Optimizer, то її. Без цього плагіна шаблон працює як раніше (є перевірка function_exists).
 *              Krasprod, 07.10.2026.
 */
if (!defined('ABSPATH')) {
    exit;
}

function educado_krasprod_card_img($value)
{
    $url = is_array($value) ? ($value['url'] ?? '') : (string) $value;
    if ($url === '') {
        return $url;
    }
    $id = attachment_url_to_postid($url);
    if (!$id) {
        return $url;
    }
    $src = wp_get_attachment_image_src($id, 'medium_large');
    if (!$src || (int) $src[1] < 600) {
        return $url;
    }
    $file = trailingslashit(dirname(get_attached_file($id))) . wp_basename($src[0]);
    if (file_exists($file . '.webp')) {
        return $src[0] . '.webp';
    }
    return file_exists($file) ? $src[0] : $url;
}
