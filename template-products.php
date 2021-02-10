<?php
/**
 * Template Name: Products Page
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

$products = new Products();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container">
					<?php if(!isset($_GET['edit'])): ?>
						<h1>Add Product </h1>
						<?php $products->createProductForm(167, 'Add Product', 'products-page'); ?>
					<?php else: ?>
						<h1>Edit Product </h1>
						<?php $products->updateProductForm($_GET['id'], 167, 'Update Product', 'products-page'); ?>
					<?php endif; ?>
				</div>
				<div class="list-container">
					<?php echo (isset($_GET['search']))?'<input type="hidden" id="search-prod" value="'.$_GET['search'].'">':'';?>
					<h1>Products List</h1>
					<div class="search-filter"><input type="text" placeholder="Type Search" class="search-text"></div>
					<table class="search-table">
						<thead>
							<tr>
								<th>Product Name</th>
								<th>Product Price</th>
								<th>Current Stock</th>
                                <th>Overall Sold</th>
                                <th>Total Sales</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
                            list($list, $pagi) = $products->getProductsList(); 

							foreach($list as $productid => $details):
								$sold = $products->getProductOverallSoldCount($productid);

                                echo '<tr><td>'.$details['product_name'].'</td><td>P '.$products->convertNumber($details['product_price']).'</td><td>'.$details['stock_quantity'].'</td><td>'.$sold.'</td><td>P '.$products->convertNumber($sold * (float)$details['product_price']).'</td><td><a href="'.get_permalink(get_the_ID()).'?edit=1&id='.$productid.'" class="item-edit"><i class="far fa-edit"></i> Edit</a> <a href="'.get_delete_post_link($productid).'" class="item-delete" title="Are you sure to delete Product Record?"><i class="far fa-trash-alt"></i> Delete</a></td></tr>';
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