<?php
/**
 * Post sharing badges rendering template part.
 *
 * @author    eyorsogood.com, Rouie Ilustrisimo
 * @version   1.0.0
 */

/**
 * No direct access to this file.
 *
 * @since 1.0.0
 */
defined( 'ABSPATH' ) || die();

$buttons_set = array(
	'twitter' => 'twitter',
	'facebook' => 'facebook',
	'linkedin' => 'linkedin',
	'googleplus' => 'googlePlus',
	'pinterest' => 'pinterest',
	'stumbleupon' => 'stumbleupon',
);

$buttons_html = array();
foreach ( $buttons_set as $type_key => $btn_code ) {
	if ( qed_get_option( 'social_sharing_' . $type_key, 'option' ) ) {
		$buttons_html[] = sprintf( '<li class="share-badges__item share-badges__item--%s" data-btntype="%s"></li>', $type_key, $btn_code );
	}
}

if ( ! $buttons_html ) {
	return;
}

$sharre_plugin_config = array(
	'itemsSelector' => '.share-badges__item[data-btntype]',
);

wp_enqueue_script( 'sharrre' );
SD_Js_Client_Script::add_script( 'sharreInit', 'Theme.initSharrres(' . wp_json_encode( $sharre_plugin_config ) . ');' );
$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
$thumbnail_img_link = isset( $thumbnail_src[0] ) ? $thumbnail_src[0] : '';

printf( '<div class="share-badges margin-top margin-bottom" data-urlshare="%s" data-imageshare="%s"><ul class="share-badges__list">%s</ul></div>',
	esc_url( get_permalink() ),
	$thumbnail_img_link ? esc_url( $thumbnail_img_link ) : '',
	join( '', $buttons_html )
);
