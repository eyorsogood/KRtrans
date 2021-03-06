<?php
/**
 * Page focus button section.
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

/**
 * Page header view for the default mode (mode without any specific settings).
 *
 * @var string $title
 * @var string $focus_buttons_is_enabled
 * @var string $focus_button_group
 */

if ( is_404() ) {
	return;
}

$section_meta_service = qed_di( 'focus_button_section' );
$section_meta = $section_meta_service ? $section_meta_service->get_section_meta() : array();
$is_home = ( 'home' === qed_check_if_home() || 'default' === qed_check_if_home()  ) ? true : false;

if ( ! $section_meta['focus_buttons_is_enabled'] ) {
	return;
}

$focus_buttons = $section_meta['focus_button_group'];
$fb_contents = array();
$fb_count = 0;

$focus_buttons_id = 'focus_buttons_' . get_the_ID();
SD_Js_Client_Script::add_script( $focus_buttons_id, 'Theme.initFocusButtonContents();' );
?>
<div class="focus-buttons<?php echo ( $is_home ) ? '' : ' block-after-indent'; ?>">
	<div class="focus-buttons__<?php echo ( $is_home ) ? 'home-inner-wrap' : 'inner-wrap'; ?>">
		<section class="focus-button__items">
			<?php
			foreach ( $focus_buttons as $focus_button ) {
				$fb_count++;
				// Use list to wrap focus buttons.
				$default_image = ! empty( $focus_button['focus_button_image'] ) ? $focus_button['focus_button_image']:'';

				switch ( true ) {
					case 'external' === $focus_button['link_type']:
						printf( '<div class="focus-button focus-button__item">
									<div class="focus-button">
										<div class="focus-button__image" style="background-image: url(%s)">

										</div>
										<div class="focus-button__content">
											<div class="focus-button__title">
													%s
											</div>
											<div class="focus-button__intro-text">
													%s
											</div>
											<a class="focus-button__link button button--default" href="%s" target="_blank">
												Learn More
											</a>
										</div>
									</div>
								</div>',
								$default_image,
								$focus_button['focus_button_title'],
								$focus_button['intro_text'],
								esc_url( qed_vp_focus_button_link( $focus_button ) )
						);
						break;
					case 'content' === $focus_button['link_type']:
						printf( '<div class="focus-button focus-button__item">
									<div class="focus-button">
										<div class="focus-button__image" style="background-image: url(%s)">

										</div>
										<div class="focus-button__content">
											<div class="focus-button__title">
													%s
											</div>
											<div class="focus-button__intro-text">
													%s
											</div>
											<a class="focus-button__link button button--default" href="%s">
												Learn More
											</a>
										</div>
									</div>
								</div>',
								$default_image,
								$focus_button['focus_button_title'],
								$focus_button['intro_text'],
								'fbc_key_' . $fb_count
						);
						$fb_contents[ 'fbc_key_' . $fb_count ] = $focus_button['fb_content'];
						break;
					case 'page' === $focus_button['link_type']:
					case 'post' === $focus_button['link_type']:
					default:
						printf( '<div class="focus-button focus-button__item">
									<div class="focus-button">
										<div class="focus-button__image" style="background-image: url(%s)">

										</div>
										<div class="focus-button__content">
											<div class="focus-button__title">
													%s
											</div>
											<div class="focus-button__intro-text">
													%s
											</div>
											<a class="focus-button__link button button--default" href="%s">
												Learn More
											</a>
										</div>
									</div>
								</div>',
								$default_image,
								$focus_button['focus_button_title'],
								$focus_button['intro_text'],
								esc_url( qed_vp_focus_button_link( $focus_button ) )
						);
				}
			}
			?>
		</section>
	</div>
</div>
<?php if ( count( $fb_contents ) > 0 ) :?>
	<div id="focus-buttons__content-wrapper" class="page-single">
		<main class="page-single__content" role="main">
			<div class="focus-buttons__content margin-bottom">
				<?php
				foreach ( $fb_contents as $fb_key => $fb_content ) {
					printf( '<div id="%s" class="focus-buttons__content-wrap">%s</div>',
							$fb_key,
							do_shortcode( $fb_content )
					);
				}
				?>
			</div>
		</main>
	</div>
<?php endif; ?>
