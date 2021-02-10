<?php
/**
 * Template Name: Owners
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

$owners = new Owners();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container">
					<?php if(!isset($_GET['edit'])): ?>
						<h1>Add Unit Owner </h1>
						<?php $owners->createOwnerForm(13, 'Add Unit Owner', 'owners-page'); ?>
					<?php else: ?>
						<h1>Edit Unit Owner </h1>
						<?php $owners->updateOwnerForm($_GET['id'], 13, 'Update Unit Owner', 'owners-page'); ?>
					<?php endif; ?>
				</div>
				<div class="list-container">
					<h1>Owners List</h1>
					<table>
						<thead>
							<tr>
								<th>Name</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
							list($list, $pagi) = $owners->getOwnersList(); 

							foreach($list as $id => $details):
								echo '<tr><td>'.$details['owner_name'].'</td><td><a href="'.get_permalink(get_the_ID()).'?edit=1&id='.$id.'" class="item-edit"><i class="far fa-edit"></i> Edit</a> <a href="'.get_delete_post_link($id).'" class="item-delete" title="Are you sure to delete owner?"><i class="far fa-trash-alt"></i> Delete</a></td></tr>';
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