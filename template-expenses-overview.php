<?php
/**
 * Template Name: Expenses Overview Page
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
$sales = new Sales();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container">
                    <h1>Select Date Range and Owner</h1>
                    <form action="./" method="GET" id="daterange-form">
                        <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start" value="<?php echo (isset($_GET['start']))?$_GET['start']:date('F d, Y');?>"/>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="end" value="<?php echo (isset($_GET['end']))?$_GET['end']:date('F d, Y');?>"/>
                        </div>
                        <div class="select-owner">
                            <?php
                                list($owners, $pagi) = $expenses->getUnitOwnerList();
                            ?>
                            <select name="owner" class="select-custom">
                                <option value="<?php echo (isset($_GET['owner']))?$_GET['owner']:'0'; ?>"><?php echo (isset($_GET['owner']))?get_field('owner_name', $_GET['owner']):'Select Owner';?></option>
                                <?php foreach($owners as $id => $deets): ?>
                                    <option value="<?php echo $id; ?>"><?php echo $deets['owner_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="submit" class="button datepick-btn" value="Submit Filter">
                    </form>
				</div>
				<div class="list-container">
					<h1>Overall Expenses (<?php echo (isset($_GET['start']) && isset($_GET['end']))?$_GET['start'].' - '.$_GET['end']:date('F d, Y');?>)</h1>
					<div class="search-filter"><input type="text" placeholder="Type Search" class="search-text"></div>
					<table class="search-table">
						<thead>
							<tr>
								<th>Plates</th>
								<th>Qty</th>
								<th>Description</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
								<th class="text-center">Owner</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
                            list($list, $pagi) = (isset($_GET['start']) && isset($_GET['end']))?$expenses->getExpensesListByDateRange($_GET['start'], $_GET['end'], $_GET['owner']):$expenses->getExpensesListByDate(date("m"), date("d"), date("Y"));

                            $tot = 0;

                            foreach($list as $id => $details):
                                $owner = get_field('unit_owner', $details['plate_number']);
                                $ownername = get_field('owner_name', $owner);

                                echo '<tr><td>'.get_field('plate_number', $details['plate_number']).'</td><td>'.$details['quantity'].'</td><td>'.$details['item_description'].'</td><td>P '.$expenses->convertNumber((float)$details['unit_price']).'</td><td>P '.$expenses->convertNumber((float)$details['unit_price'] * (float)$details['quantity']).'</td><td class="text-center">'.$ownername.'</td></tr>';

                                $tot += (float)$details['unit_price'] * (float)$details['quantity'];
                            endforeach;
                            
                            list($list, $pagi) = (isset($_GET['start']) && isset($_GET['end']))?$sales->getSalesListByDateRange($_GET['start'], $_GET['end'], $_GET['owner']):$sales->getSalesListByDate(date("m"), date("d"), date("Y"));

                            foreach($list as $id => $details):
                                $owner = get_field('unit_owner', $details['plate_number']);
                                $ownername = get_field('owner_name', $owner);

                                echo '<tr><td>'.get_field('plate_number', $details['plate_number']).'</td><td>'.$details['quantity'].'</td><td>'.get_field('product_name', $details['product']).'</td><td>P '.$sales->convertNumber((float)get_field('product_price', $details['product'])).'</td><td>P '.$sales->convertNumber((float)get_field('product_price', $details['product']) * (float)$details['quantity']).'</td><td class="text-center">'.$ownername.'</td></tr>';

                                $tot += (float)get_field('product_price', $details['product']) * (float)$details['quantity'];
                            endforeach;
                            

							?>
                        </tbody>
					</table>
					<?php //echo $pagi; ?>
                    <div class="summary">
                        <table>
                            <tbody>
                                <tr><td><b class="highlight">Grand Total</b></td><td><b class="highlight">P <?php echo $expenses->convertNumber($tot); ?></b></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="printing__button"><a href="#" class="button btn printing__init" data-title="Expense Overview" data-date="<?php echo (isset($_GET['start']) && isset($_GET['end']))?$_GET['start'].' - '.$_GET['end']:date('F d, Y');?>">Print Expense Overview</a></div>
				</div>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();