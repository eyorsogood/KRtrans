<?php
/**
 * Template Name: Odo Meter
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

$theme = new Theme();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container">
                    <h1>Current ODO Meter Value </h1>
                    <?php $theme->updateAcfForm(get_the_ID(), 134, 'Update ODO Meter', 'odo-meter'); ?>
				</div>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();