<?php
/**
 * Template Name: Drivers
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

$drivers = new Drivers();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container">
					<?php if(!isset($_GET['edit'])): ?>
						<h1>Add Driver </h1>
						<?php $drivers->createDriverForm(35, 'Add Driver', 'drivers-page'); ?>
					<?php else: ?>
						<h1>Edit Driver </h1>
						<?php $drivers->updateDriverForm($_GET['id'], 35, 'Update Driver', 'drivers-page'); ?>
					<?php endif; ?>
				</div>
				<div class="list-container">
					<h1>Drivers List</h1>
					<div class="search-filter"><input type="text" placeholder="Type Search" class="search-text"></div>
					<table class="search-table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Contact Number</th>
								<th>Driver License</th>
								<th>Position</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
							list($list, $pagi) = $drivers->getDriversList(); 

							foreach($list as $id => $details):
								echo '<tr><td>'.$details['driver_name'].'</td><td>'.$details['contact_number'].'</td><td>'.$details['driver_license'].'</td><td>'.$details['position'].'</td><td><a href="'.get_permalink(get_the_ID()).'?edit=1&id='.$id.'" class="item-edit"><i class="far fa-edit"></i> Edit</a> <a href="'.get_delete_post_link($id).'" class="item-delete" title="Are you sure to delete Driver?"><i class="far fa-trash-alt"></i> Delete</a></td></tr>';
							endforeach;
							
							?>
						</tbody>
					</table>
				</div>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();