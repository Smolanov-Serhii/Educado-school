<?php
/**
 * The header for theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
    <title><?php wp_title()?></title>

    <!-- Meta Pixel Code --> 
    <script> 
    !function(f,b,e,v,n,t,s) 
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod? 
    n.callMethod.apply(n,arguments):n.queue.push(arguments)}; 
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; 
    n.queue=[];t=b.createElement(e);t.async=!0; 
    t.src=v;s=b.getElementsByTagName(e)[0]; 
    s.parentNode.insertBefore(t,s)}(window, document,'script', 
    'https://connect.facebook.net/en_US/fbevents.js'); 
    fbq('init', '794161271856671'); 
    fbq('track', 'PageView');
    fbq('track', 'Contact');
    </script> 
    <noscript><img height="1" width="1" style="display:none" 
    src="https://www.facebook.com/tr?id=794161271856671&ev=PageView&noscript=1" 
    /></noscript> 
    <!-- End Meta Pixel Code -->
    
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-P3BNBJ4');</script>
<!-- End Google Tag Manager -->
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P3BNBJ4"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<?php $educado_cookie = $_COOKIE['educado'] ?? ''; ?>
<?php if (!$educado_cookie || $educado_cookie != 1) { ?>
    <div class="ed-preloader" id="ed-preloader">
        <div class="ed-preloader-wrapper">
            <svg class="ed-preloader-logo" viewBox="0 0 119 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="path1"
                      d="M14.5807 87.067C12.9765 85.2899 11.0265 84.3869 8.7931 84.3869C6.5597 84.3869 4.65771 85.1506 3.13996 86.6588C1.62221 88.1669 0.848915 89.9776 0.848915 92.0429C0.848915 94.1082 1.62222 95.9382 3.15438 97.4895C4.68173 99.0409 6.50683 99.8286 8.57212 99.8286H8.86035C11.0553 99.8286 12.9477 98.9688 14.4895 97.2782L14.5375 97.2254V94.9391L14.2061 95.429C13.5625 96.38 12.7411 97.1533 11.7565 97.7201C10.7767 98.2868 9.65282 98.575 8.41364 98.575C7.17447 98.575 5.97852 98.0707 4.69132 97.0765L14.7008 87.1967L14.5855 87.067H14.5807ZM3.75475 96.1591C2.74612 94.9535 2.23218 93.5367 2.23218 91.9517C2.23218 90.3667 2.87577 88.8249 4.14377 87.5569C5.41176 86.2889 6.88627 85.6453 8.5193 85.6453C10.1523 85.6453 11.526 86.1688 12.7747 87.2015L3.75475 96.1591Z"
                      fill="#161616"/>
                <path class="path2"
                      d="M30.6275 87.7106C30.0416 86.7884 29.2298 86.0248 28.1972 85.434C26.9484 84.7183 25.6372 84.3581 24.2876 84.3581C22.1406 84.3581 20.2866 85.1218 18.7785 86.6299C17.2704 88.1381 16.5067 89.9728 16.5067 92.0765C16.5067 94.1803 17.2704 96.015 18.7785 97.5232C20.2866 99.0313 22.1406 99.795 24.2876 99.795C25.5892 99.795 26.8764 99.4396 28.1156 98.7335C29.153 98.1427 29.9983 97.3695 30.6275 96.4281V99.4444H32.0108V75.1219H30.6275V87.7106ZM28.7784 96.6298C27.5104 97.8978 25.983 98.5414 24.2395 98.5414C22.496 98.5414 20.9879 97.8978 19.7535 96.6298C18.5144 95.3618 17.89 93.8344 17.89 92.091C17.89 90.3475 18.524 88.8153 19.7728 87.5377C21.0215 86.2601 22.5297 85.6117 24.2587 85.6117C25.9878 85.6117 27.5104 86.2649 28.7784 87.5569C30.0464 88.8489 30.69 90.3811 30.69 92.1102C30.69 93.8393 30.0464 95.3618 28.7784 96.6298Z"
                      fill="#161616"/>
                <path class="path3"
                      d="M47.049 93.2581C47.049 94.6702 46.5015 95.919 45.4256 96.9612C44.3449 98.0083 43.0817 98.5414 41.6696 98.5414C40.2575 98.5414 39.0376 98.0131 38.0481 96.966C37.0539 95.919 36.5496 94.6702 36.5496 93.2581V84.7088H35.1663V93.2581C35.1663 95.0688 35.7955 96.625 37.0395 97.8882C38.2835 99.1514 39.8397 99.795 41.6744 99.795C44.1 99.795 45.9059 98.8728 47.0538 97.0429V99.4396H48.437V84.7039H47.0538V93.2533L47.049 93.2581Z"
                      fill="#161616"/>
                <path class="path4"
                      d="M58.6963 84.4157C56.4821 84.4157 54.5945 85.1794 53.072 86.6876C51.5542 88.1957 50.7809 90.0112 50.7809 92.0861C50.7809 94.161 51.5398 95.991 53.0384 97.5327C54.5369 99.0745 56.3524 99.8574 58.4417 99.8574H58.7299C60.9681 99.8574 62.8701 99.0121 64.3927 97.3358L64.4407 97.283V94.9535L64.1092 95.453C63.4704 96.4232 62.6443 97.2013 61.6645 97.7585C60.6847 98.3156 59.6472 98.599 58.5762 98.599C56.8663 98.599 55.3534 97.9554 54.0854 96.6874C52.8174 95.4194 52.1738 93.8873 52.1738 92.1342C52.1738 90.3811 52.8174 88.8489 54.0854 87.5809C55.3534 86.3129 56.8663 85.6693 58.5762 85.6693C59.6472 85.6693 60.6847 85.9527 61.6645 86.5098C62.6491 87.067 63.4705 87.8355 64.1141 88.7865L64.4455 89.2764V86.9853L64.3975 86.9325C62.8365 85.2611 60.9201 84.4157 58.7059 84.4157H58.6963Z"
                      fill="#161616"/>
                <path class="path5"
                      d="M80.1177 87.725C79.5893 86.8172 78.8065 86.0584 77.7834 85.4628C76.5154 84.7279 75.1657 84.3533 73.7777 84.3533C71.65 84.3533 69.8008 85.117 68.2831 86.6251C66.7653 88.1333 65.992 89.968 65.992 92.0717C65.992 94.1754 66.7605 96.0102 68.2831 97.5183C69.8008 99.0265 71.65 99.7901 73.7777 99.7901C75.1657 99.7901 76.4866 99.4251 77.7018 98.7095C78.7104 98.1139 79.5173 97.331 80.1129 96.3752V99.4395H81.4961V84.7039H80.1129V87.725H80.1177ZM78.3166 96.6298C77.0774 97.8978 75.5692 98.5414 73.8305 98.5414C72.0918 98.5414 70.5597 97.8978 69.2917 96.6298C68.0237 95.3618 67.3801 93.8392 67.3801 92.1102C67.3801 90.3811 68.0237 88.8441 69.2917 87.5569C70.5597 86.2697 72.0823 85.6117 73.8113 85.6117C75.5404 85.6117 77.0533 86.2601 78.2973 87.5377C79.5461 88.8201 80.1801 90.3523 80.1801 92.0909C80.1801 93.8296 79.5509 95.3618 78.3166 96.6298Z"
                      fill="#161616"/>
                <path class="path6"
                      d="M98.297 87.7106C97.711 86.7884 96.8993 86.0248 95.8667 85.434C94.6179 84.7183 93.3067 84.3581 91.957 84.3581C89.8101 84.3581 87.9561 85.1218 86.448 86.6299C84.9398 88.1381 84.1762 89.9728 84.1762 92.0765C84.1762 94.1803 84.9398 96.015 86.448 97.5232C87.9561 99.0313 89.8101 99.795 91.957 99.795C93.2586 99.795 94.5459 99.4396 95.7851 98.7335C96.8225 98.1427 97.6678 97.3695 98.297 96.4281V99.4444H99.6803V75.1219H98.297V87.7106ZM96.4479 96.6298C95.1799 97.8978 93.6525 98.5414 91.909 98.5414C90.1655 98.5414 88.6574 97.8978 87.423 96.6298C86.1838 95.3618 85.5594 93.8344 85.5594 92.091C85.5594 90.3475 86.1935 88.8153 87.4422 87.5377C88.691 86.2601 90.1991 85.6117 91.9282 85.6117C93.6573 85.6117 95.1799 86.2649 96.4479 87.5569C97.7159 88.8489 98.3595 90.3811 98.3595 92.1102C98.3595 93.8393 97.7159 95.3618 96.4479 96.6298Z"
                      fill="#161616"/>
                <path class="path7"
                      d="M115.645 86.6251C114.137 85.117 112.288 84.3533 110.151 84.3533C108.013 84.3533 106.16 85.117 104.642 86.6251C103.124 88.1333 102.351 89.944 102.351 92.0093C102.351 94.0746 103.11 95.9045 104.603 97.4559C106.102 99.0073 107.917 99.795 110.007 99.795H110.295C112.403 99.795 114.219 99.0121 115.698 97.4703C117.173 95.9285 117.922 94.0986 117.922 92.0285C117.922 89.9584 117.158 88.1381 115.65 86.6299L115.645 86.6251ZM114.67 96.625C113.431 97.893 111.923 98.5366 110.184 98.5366C108.446 98.5366 106.914 97.893 105.646 96.625C104.378 95.357 103.734 93.8344 103.734 92.1053C103.734 90.3763 104.378 88.8393 105.646 87.5521C106.914 86.2649 108.436 85.6069 110.165 85.6069C111.894 85.6069 113.407 86.2553 114.651 87.5329C115.9 88.8153 116.534 90.3475 116.534 92.0861C116.534 93.8248 115.905 95.357 114.67 96.625Z"
                      fill="#161616"/>


                <path class="path8" d="M89.277 1.20074H28.6151V61.8627H89.277V1.20074Z" fill="#8F8F2D"/>
                <path class="path8"
                      d="M42.8224 44.4038L42.1836 40.4029L42.5053 36.6421L43.9462 33.3569L46.026 28.5587L47.6254 25.1149L48.8261 22.3916L49.9116 18.7894L51.2276 13.2659L53.6291 10.3841L56.2708 9.18335H59.1862L62.7548 10.5426L64.7577 13.1843L66.3187 17.4301L67.3177 21.431L69.5607 26.796L71.4819 31.037L73.6432 36.5605V41.2818L72.6826 45.2059L70.4396 49.0483L67.1592 51.2096L63.7154 52.0886L58.5138 51.5314L53.9509 48.8081L51.6695 46.8917L49.3064 47.0454L44.9837 46.008L43.0625 45.1242L42.8224 44.4038Z"
                      fill="#D3D360"/>
                <path class="path8"
                      d="M51.6695 46.8917L49.9116 42.2472V38.1647L51.1892 34.5624L52.7117 31.5317L54.9547 29.4424L57.9134 28.5587L60.9585 29.039L63.6001 30.9602L65.4397 33.6835L66.3187 37.2857V39.5287L64.6376 43.131L62.798 45.2107L59.8346 46.8917L55.435 47.5305H52.1498L51.6695 46.8917Z"
                      fill="#D8B79F"/>
                <path class="path8"
                      d="M90.4825 0H27.4144V63.0682H90.4825V0ZM89.2818 61.8675H28.6199V37.9149C32.9714 40.7343 37.4718 43.5824 42.3228 45.5277C43.7685 49.7879 47.342 53.4478 52.169 55.6092C55.2045 56.9684 58.4802 57.6456 61.6886 57.6456C64.6808 57.6456 67.6154 57.0549 70.2475 55.8685C77.4424 52.6313 83.84 44.8312 88.984 38.5681C89.0849 38.4432 89.1809 38.328 89.2818 38.2031V61.8627V61.8675ZM60.3917 47.1799C63.2591 46.3009 65.3581 44.2741 66.3042 41.4739C67.4185 38.1887 66.7798 34.2838 64.6376 31.2916C63.1391 29.1974 60.9729 27.9487 58.6962 27.867C56.458 27.795 54.3352 28.8132 52.7261 30.7584C49.4553 34.7017 48.1585 41.9398 50.853 46.5699C48.4082 46.2769 45.9155 45.6333 43.2834 44.6199C42.8512 43.131 42.6879 41.546 42.7983 39.9033C43.0625 36.0369 44.7628 32.5644 46.5639 28.8804C47.8751 26.2052 49.2248 23.4338 49.9692 20.5088C50.1037 19.9757 50.2334 19.4281 50.3582 18.8806C51.0259 16.0036 51.7127 13.0257 53.9077 11.1478C56.1699 9.21216 60.1324 9.21216 62.3898 11.1478C64.3542 12.8288 65.2044 16.3206 65.8864 19.1256C66.0161 19.6683 66.1457 20.187 66.2706 20.6673C66.9766 23.3282 68.2543 25.7921 69.4886 28.1744C71.2225 31.5173 72.8603 34.6729 73.0861 38.3616C73.3839 43.1502 71.4435 48.8321 66.4339 50.7341C64.1669 51.5938 61.4916 51.6515 58.8932 50.8926C56.9288 50.321 55.3966 49.3172 53.8693 47.9772C56.045 47.958 58.2496 47.8427 60.3773 47.1895L60.3917 47.1799ZM52.4332 46.7668C49.3304 42.7323 50.5167 35.3117 53.6531 31.5269C55.022 29.8795 56.7943 29.0053 58.653 29.0726C60.555 29.1398 62.3802 30.2061 63.6578 31.9928C65.579 34.6777 66.1553 38.1598 65.1611 41.0897C64.3398 43.5152 62.5195 45.2683 60.0315 46.032C57.6012 46.7764 54.974 46.7716 52.4284 46.7668H52.4332ZM89.277 36.3155C88.8783 36.7958 88.4701 37.2953 88.0474 37.8092C82.9851 43.9763 76.6835 51.6515 69.7432 54.7734C64.5991 57.0933 58.2064 56.9972 52.6493 54.5141C48.4947 52.6553 45.3391 49.6342 43.8166 46.104C46.6551 47.1174 49.3497 47.7034 52.0249 47.8907C54.0374 49.8263 55.9298 51.272 58.5666 52.0405C61.41 52.8714 64.359 52.7994 66.8662 51.8484C72.4472 49.7255 74.6182 43.5056 74.2916 38.2799C74.0467 34.3415 72.2792 30.9217 70.5645 27.6125C69.359 25.2878 68.115 22.8815 67.4426 20.3503C67.3177 19.8796 67.1928 19.3705 67.0631 18.8374C66.3378 15.8643 65.4397 12.1612 63.1775 10.2304C60.4542 7.90094 55.8577 7.90094 53.1344 10.2304C50.6416 12.3677 49.9068 15.5377 49.196 18.6068C49.0711 19.1496 48.9462 19.6875 48.8117 20.211C48.096 23.0208 46.7704 25.7297 45.488 28.3521C43.7157 31.9736 41.8858 35.7199 41.6024 39.8169C41.5015 41.277 41.6024 42.6939 41.9002 44.0531C37.2364 42.0599 32.8561 39.2309 28.6103 36.4788V1.20075H89.2722V36.3107L89.277 36.3155Z"
                      fill="#161616"/>
            </svg>
        </div>
    </div>
<?php } ?>


<header class="ed-header">
    <div class="container">
        <div class="ed-header-bar">
            <?php
            $logo = get_field('header_logo', 'options');
            ?>

            <?php if (is_front_page()) { ?>
                <div class="ed-header-logo">
                    <img src="<?= $logo['url']; ?>" alt="<?= $logo['alt']; ?>">
                </div>
            <?php } else { ?>
                <a href="<?php echo home_url()?>" class="ed-header-logo">
                    <img src="<?= $logo['url']; ?>" alt="<?= $logo['alt']; ?>">
                </a>
            <?php } ?>
            

            <?php
            /** Menu **/
            get_template_part('parts/content', 'menu', array(
                'class' => 'ed-header-menu',
            ));
            ?>
            <a class="ed-header-contacts__link ed-header-contacts__link-mobile" href="tel:+380674750255">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="20" fill="#8F8F2D"/>
                    <path d="M31.8866 26.8807C31.8298 26.5298 31.6098 26.2299 31.2803 26.0518L26.4064 23.1801L26.3663 23.1577C26.1619 23.0554 25.9363 23.003 25.7077 23.0047C25.2994 23.0047 24.9094 23.1594 24.6388 23.4307L23.2002 24.8699C23.1387 24.9284 22.9381 25.0131 22.8772 25.0161C22.8606 25.0148 21.2034 24.8954 18.1526 21.8443C15.1072 18.7996 14.978 17.1373 14.977 17.1373C14.9787 17.0523 15.0623 16.8524 15.1218 16.7905L16.3486 15.5641C16.7807 15.131 16.9102 14.4129 16.6539 13.8567L13.9448 8.75904C13.7479 8.35376 13.3654 8.10352 12.9411 8.10352C12.6409 8.10352 12.3512 8.22796 12.1247 8.45406L8.78083 11.7905C8.46021 12.1094 8.18413 12.667 8.12395 13.1158C8.09471 13.3303 7.50141 18.4514 14.5224 25.4734C20.483 31.4333 25.1308 31.8964 26.4143 31.8964C26.5706 31.8984 26.7268 31.8903 26.8821 31.8722C27.3295 31.8124 27.8865 31.537 28.205 31.2177L31.5462 27.8769C31.8189 27.6029 31.9433 27.2408 31.8866 26.8807V26.8807Z" fill="white"/>
                </svg>
            </a>
            <div class="ed-header-contacts">
                <?php
                $email = get_field('header_email', 'options');
                ?>
                <a class="ed-header-contacts__link" href="tel:+380674750255">
                    +38(067)475-02-55
                </a>

                <?php
                /** Social links **/
                get_template_part('parts/content', 'social-links');
                ?>
            </div>

            <div class="ed-header-button button-speak callpopup" data-popup="callback">
                <svg>
                    <use xlink:href="#ed-svg-speak"></use>
                </svg>
            </div>

            <div class="ed-header-burger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <div class="ed-menu">
        <?php
        /** Menu **/
        get_template_part('parts/content', 'menu');
        ?>
    </div>
</header>
