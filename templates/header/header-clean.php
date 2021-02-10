<?php
/**
 * Header clean template part.
 *
 * @author    eyorsogood.com, Rouie Ilustrisimo
 * @version   1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
	if ( ! qed_check( 'is_wordpress_seo_in_use' ) ) {
		printf( '<meta name="description" content="%s">', get_bloginfo( 'description', 'display' ) );
	}
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php acf_form_head(); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="printing">
	<div class="printing__container">
		<div class="printing__close"><i class="fas fa-times"></i></div>
		<div class="printing__inner" id="print-canvas"></div>
	</div>
</div>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
<div class="layout-content">