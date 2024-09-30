<?php

// Display RedQ Menu
wp_nav_menu(array(
    'theme_location' => 'redq-menu',
    'menu_class'     => 'redq-menu', // Add custom class for styling
    'container'      => 'nav', // Optional container tag
));


// Output custom text
$redq_custom_text = get_theme_mod('redq_text_setting', __('Default text', 'text_domain'));
echo '<p>' . esc_html($redq_custom_text) . '</p>';

// Output custom accent color
$redq_custom_color = get_theme_mod('redq_color_setting', '#ff0000');
?>
<style>
    body {
        color: <?php echo esc_attr($redq_custom_color); ?>;
    }
</style>

<?php
// Output uploaded logo
$redq_custom_logo = get_theme_mod('redq_image_setting');
if ($redq_custom_logo) {
    echo '<img src="' . esc_url($redq_custom_logo) . '" alt="Custom Logo" />';
}
?>


<?php get_search_form(); 
?>

<!DOCTYPE html>
<html lang="<?php language_attributes(); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title> wordpress theme </title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

 

