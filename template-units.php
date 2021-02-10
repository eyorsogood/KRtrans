<?php
/**
 * Template Name: Units
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

$units = new Units();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container">
					<div class="units-container">
					<?php if(!isset($_GET['edit'])): ?>
						<h1>Add Unit </h1>
						<?php $units->createUnitForm(72, 'Add Unit', 'units-page'); ?>
					<?php else: ?>
						<h1>Edit Unit </h1>
						<?php $units->updateUnitForm($_GET['id'], 72, 'Update Unit', 'units-page'); ?>
					<?php endif; ?>
					</div>
				</div>
				<div class="list-container">
					<h1>Units List</h1>
					<table>
						<thead>
							<tr>
								<th>Plate Number</th>
								<th>Body Number</th>
								<th>Unit Owner</th>
								<th>Unit Rental Price</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
							list($list, $pagi) = $units->getUnitsList(); 

							foreach($list as $id => $details):
								echo '<tr><td>'.$details['plate_number'].'</td><td>'.$details['body_number'].'</td><td>'.get_field('owner_name',$details['unit_owner']).'</td><td>P '.$details['unit_rental_price'].'</td><td><a href="'.get_permalink(get_the_ID()).'?edit=1&id='.$id.'" class="item-edit"><i class="far fa-edit"></i> Edit</a> <a href="'.get_delete_post_link($id).'" class="item-delete" title="Are you sure to delete Unit?"><i class="far fa-trash-alt"></i> Delete</a></td></tr>';
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