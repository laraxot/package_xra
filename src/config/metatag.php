<?php



return [
    'title' => 'title',
    'sottotitolo_comune' => 'subtitle',
    'generator' => 'generator',
    'charset' => 'UTF-8',
    'author' => 'author',
    'description' => 'description',
    'keywords' => 'keywords comma separated',
    'nome_regione' => 'country',
    'nome_comune' => 'city',
    'site_title' => 'city title',
    'logo_img' => '/theme/pub/img/logo.png',
    'logo_alt' => 'Logo alt',
    'hide_megamenu' => true,
    'hero_type' => 'with_megamenu_bottom',
    'facebook_href' => 'aa',
    'twitter_href' => '',
    'youtube_href' => '',
    'fastlink' => false,
    'color_primary' => '#0071b0',
    'color_title' => 'white',
    'color_megamenu' => '#d60021',
    'color_hamburger' => '#000',
    'color_banner' => '#000',
];

if (isset($_SERVER['SERVER_NAME'])) {
    $site = include str_slug(\str_replace('www.', '', $_SERVER['SERVER_NAME'])).'/'.\basename(__FILE__);
} else {
    $site = [];
}
$out = \array_merge($default, $site);
//echo '<pre>';print_r($out);echo '</pre>';
return $out;
