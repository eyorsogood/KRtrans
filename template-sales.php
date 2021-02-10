<?php
/**
 * Template Name: Sales Page
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

$sales = new Sales();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container sales">
					<?php if(!isset($_GET['edit'])): ?>
						<h1>Add Sale </h1>
						<?php $sales->createSaleForm(176, 'Add Sale', 'sales-page'); ?>
					<?php else: ?>
						<h1>Edit Sale </h1>
						<?php $sales->updateSaleForm($_GET['id'], 176, 'Update Sale', 'sales-page'); ?>
					<?php endif; ?>
				</div>
				<div class="list-container">
					<h1>Sales List</h1>
					<table>
						<thead>
							<tr>
								<th>Plate Number</th>
								<th>Product Name</th>
								<th>Quantity</th>
                                <th>Total Price</th>
                                <th>Date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
                            list($list, $pagi) = $sales->getSalesList(); 

                            foreach($list as $id => $details):
								$productprice = (float)get_field('product_price', $details['product']);
								$totalprice = $sales->convertNumber((float)$details['quantity'] * $productprice);

                                echo '<tr><td>'.get_field('plate_number', $details['plate_number']).'</td><td><a href="'.get_permalink(160).'?search='.get_field('product_name', $details['product']).'">'.get_field('product_name', $details['product']).'</a></td><td>'.$details['quantity'].'</td><td>P '.$totalprice.'</td><td>'.$details['purchase_date'].'</td><td><a href="'.get_permalink(get_the_ID()).'?edit=1&id='.$id.'" class="item-edit"><i class="far fa-edit"></i> Edit</a> <a href="'.get_delete_post_link($id).'" class="item-delete" title="Are you sure to delete Sale Record?"><i class="far fa-trash-alt"></i> Delete</a></td></tr>';
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