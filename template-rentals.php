<?php
/**
 * Template Name: Rentals
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

$rentals = new Rentals();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container">
					<?php if(!isset($_GET['edit'])): ?>
						<?php if(isset($_GET['active'])): ?>
						<h2>PLATE NUMBER: <?php echo get_field('plate_number', $_GET['id']); ?></h2>
						<?php $rentals->createRentalForm(113, 'Add Rental', 'rentals-page/'.$rentals->getPreDateRangeString()); ?>
						<?php endif; ?>
					<?php else: ?>
						<h1>Edit Rental </h1>
						<h2>PLATE NUMBER: <?php echo get_field('plate_number', get_field('plate_number', $_GET['id'])); ?></h2>
						<?php $rentals->updateRentalForm($_GET['id'], 113, 'Update Rental', 'rentals-page/'.$rentals->getPreDateRangeString()); ?>
					<?php endif; ?>
				</div>
				<div class="list-container search-date-filter">
					<?php
						$date = (isset($_GET['start']))?$_GET['start']:date('F d, Y');
					?>
					<h1>Rentals List (<?php echo (isset($_GET['start']))?$_GET['start']:date('F d, Y');?>) <div class="date-buttons"><a href="<?php echo get_permalink(get_the_ID()); ?>?start=<?php echo date('F d, Y',strtotime($date . "-1 day"));?>&end=<?php echo date('F d, Y',strtotime($date . "-1 day"));?>" class="date-prev"><i class="fas fa-chevron-left"></i></a><a href="<?php echo get_permalink(get_the_ID()); ?>?start=<?php echo date('F d, Y',strtotime($date . "+1 day"));?>&end=<?php echo date('F d, Y',strtotime($date . "+1 day"));?>" class="date-next"><i class="fas fa-chevron-right"></i></a></div></h1>
					<div class="date-filter"><form action="./" method="GET"><input type="text" name="start" placeholder="Select Date" class="date-text"> <input type="hidden" name="end" value=""><input type="submit" class="btn button" value="Filter Date"></form></div>
					<div class="search-filter"><input type="text" placeholder="Type Search" class="search-text"></div>
					<table class="search-table">
						<thead>
							<tr>
								<th>Plate Number</th>
								<th>Driver Name</th>
								<th>Amount</th>
                                <th>Vehicle Status</th>
                                <th>Extra</th>
                                <th>Remarks</th>
								<th>Override</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
							list($list, $pagi) = (isset($_GET['start']) && isset($_GET['end']))?$rentals->getRentalsListByDateRange($_GET['start'], $_GET['end']):$rentals->getRentalsListByDate(date("m"), date("d"), date("Y")); 
							$allunits = $rentals->getAllUnitsList()[0];

                            $amounttot = 0;
                            $watertot = 0;
							$extrastot = 0;

							foreach($list as $id => $details):
								$priceoverride = (isset($details['daily_price_override']) && strlen($details['daily_price_override']) > 0)?(float)$details['daily_price_override']:false;
                                $unitprice = ($priceoverride)?$priceoverride:(float)get_field('unit_rental_price', $details['plate_number']);
								$amounttext = ($unitprice > ((float)$details['amount']))?$rentals->convertNumber((float)$details['amount']).' <span class="warning">('.$rentals->convertNumber($unitprice - (float)$details['amount']).')</span>':$rentals->convertNumber((float)$details['amount']);
								
								$overridetext = (isset($details['daily_price_override']) && strlen($details['daily_price_override']) > 0)?'P '.$rentals->convertNumber((float)$details['daily_price_override']):'None';

                                echo '<tr><td><a href="'.get_permalink(get_the_ID()).$rentals->getPreDateRangeString().'&edit=1&id='.$id.'" class="item-edit">'.get_field('plate_number', $details['plate_number']).'</a></td><td><a href="'.site_url().'/driver-profile/?driverid='.$details['driver'].'" target="_blank">'.get_field('driver_name', $details['driver']).'</a></td><td>P '.$amounttext.'</td><td>P '.$rentals->convertNumber((float)$details['water']).'</td><td>P '.$rentals->convertNumber((float)$details['extra']).'</td><td>'.$details['remarks'].'</td><td>'.$overridetext.'</td><td><a href="'.get_permalink(get_the_ID()).$rentals->getPreDateRangeString().'&edit=1&id='.$id.'" class="item-edit"><i class="far fa-edit"></i> Edit</a> <a href="'.get_delete_post_link($id).'" class="item-delete" title="Are you sure to delete Rental Record?"><i class="far fa-trash-alt"></i> Delete</a></td></tr>';
                                
                                $amounttot += (float)$details['amount'];
                                $watertot += (float)$details['water'];
								$extrastot += (float)$details['extra'];
								
								unset($allunits[$details['plate_number']]);
							endforeach;

							foreach($allunits as $id => $details):
								echo '<tr><td><a href="'.get_permalink(get_the_ID()).$rentals->getPreDateRangeString().'&active=1&id='.$id.'" class="item-active">'.$details['plate_number'].'</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td><a href="'.get_permalink(get_the_ID()).$rentals->getPreDateRangeString().'&active=1&id='.$id.'" class="item-active">Active <i class="fas fa-angle-double-right"></i></a></td></tr>';
							endforeach;
							
							?>
                        </tbody>
                    </table>
                    <div class="summary">
                        <table>
                            <tbody>
                                <tr><td>Amount Total</td><td>P <?php echo $rentals->convertNumber($amounttot); ?></td></tr>
                                <tr><td>Vehicle Status Total</td><td>P <?php echo $rentals->convertNumber($watertot); ?></td></tr>
                                <tr><td>Extras Total</td><td>P <?php echo $rentals->convertNumber($extrastot); ?></td></tr>
                                <tr><td><b class="highlight">Grand Total</b></td><td><b class="highlight">P <?php echo $rentals->convertNumber($amounttot + $watertot + $extrastot); ?></b></td></tr>
                            </tbody>
                        </table>
					</div>
					<div class="printing__button"><a href="#" class="button btn printing__init" data-title="Taxi Rental" data-date="<?php echo $date; ?>">Print Rental Record</a></div>
				</div>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();