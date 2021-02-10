<?php
/**
 * Template Name: Expenses Page
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

$expenses = new Expenses();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container expenses">
					<?php if(!isset($_GET['edit'])): ?>
						<h1>Add Expense </h1>
						<?php $expenses->createExpenseForm(200, 'Add Expense', 'expenses-page'); ?>
					<?php else: ?>
						<h1>Edit Expense </h1>
						<?php $expenses->updateExpenseForm($_GET['id'], 200, 'Update Expense', 'expenses-page'); ?>
					<?php endif; ?>
				</div>
				<div class="list-container">
					<h1>Expenses List</h1>
					<table>
						<thead>
							<tr>
								<th>Plates</th>
								<th>Qty</th>
								<th>Description</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
                            list($list, $pagi) = $expenses->getExpensesList(); 

                            foreach($list as $id => $details):
                                echo '<tr><td>'.get_field('plate_number', $details['plate_number']).'</td><td>'.$details['quantity'].'</td><td>'.$details['item_description'].'</td><td>P '.$expenses->convertNumber((float)$details['unit_price']).'</td><td>P '.$expenses->convertNumber((float)$details['unit_price'] * (float)$details['quantity']).'</td><td><a href="'.get_permalink(get_the_ID()).'?edit=1&id='.$id.'" class="item-edit"><i class="far fa-edit"></i> Edit</a> <a href="'.get_delete_post_link($id).'" class="item-delete" title="Are you sure to delete Expense Record?"><i class="far fa-trash-alt"></i> Delete</a></td></tr>';
							endforeach;

							?>
                        </tbody>
					</table>
					<?php echo $pagi; ?>
				</div>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();