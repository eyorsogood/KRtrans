<?php
/**
 * Template Name: Rental History
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
                    <h1>Select Date Range </h1>
                    <form action="./" method="GET" id="daterange-form">
                        <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start" value="<?php echo (isset($_GET['start']))?$_GET['start']:date('F d, Y');?>"/>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="end" value="<?php echo (isset($_GET['end']))?$_GET['end']:date('F d, Y');?>"/>
                        </div>
                        <input type="submit" class="button datepick-btn" value="Submit Range">
                    </form>
				</div>
				<div class="list-container">
					<h1>Rentals List (<?php echo (isset($_GET['start']) && isset($_GET['end']))?$_GET['start'].' - '.$_GET['end']:date('F d, Y');?>)</h1>
					<div class="search-filter"><input type="text" placeholder="Type Search" class="search-text"></div>
					<table class="search-table">
						<thead>
							<tr>
								<th>Plate Number</th>
								<th>Driver Name</th>
								<th>Amount</th>
                                <th>Water</th>
                                <th>Extra</th>
                                <th>Remarks</th>
							</tr>
						</thead>
						<tbody>
							<?php 

                            list($list, $pagi) = (isset($_GET['start']) && isset($_GET['end']))?$rentals->getRentalsListByDateRange($_GET['start'], $_GET['end']):$rentals->getRentalsListByDate(date("m"), date("d"), date("Y")); 

                            $allunits = $rentals->getAllUnitsList()[0];

                            $accumulate = array();

                            $amounttot = 0;
                            $watertot = 0;
                            $extrastot = 0;

                            foreach($list as $id => $details):
                                $accumulate[$details['plate_number']][$details['driver']][] = $details;                                
                            endforeach;

                            foreach($accumulate as $unitid => $unit):
                                unset($allunits[$unitid]);

                                foreach($unit as $driverid => $driver):
                                    $amounttemp = 0;
                                    $amountlack = 0;
                                    $amountwater = 0;
                                    $amountextra = 0;
                                    $allremarks = '';

                                    foreach($driver as $id => $details):
                                        $priceoverride = (isset($details['daily_price_override']) && strlen($details['daily_price_override']) > 0)?(float)$details['daily_price_override']:false;
                                        $unitprice = ($priceoverride)?$priceoverride:(float)get_field('unit_rental_price', $unitid);
                                        $amounttemp += (float)$details['amount'];
                                        $amountlack += ($unitprice > ((float)$details['amount']))?($unitprice - (float)$details['amount']):0;

                                        $amountwater += (float)$details['water'];
                                        $amountextra += (float)$details['extra'];
                                        $allremarks .= (strlen($details['remarks']) > 0)?'<li>'.$details['remarks'].'</li>':'';

                                        $amounttot += (float)$details['amount'];
                                        $watertot += (float)$details['water'];
                                        $extrastot += (float)$details['extra'];
                                    endforeach;

                                    $amounttext = ($amountlack > 0)?$rentals->convertNumber($amounttemp).' <span class="warning">('.$rentals->convertNumber($amountlack).')</span>':$rentals->convertNumber($amounttemp);

                                    echo '<tr><td>'.get_field('plate_number', $unitid).'</td><td>'.get_field('driver_name', $driverid).'</td><td>P '.$amounttext.'</td><td>P '.$rentals->convertNumber($amountwater).'</td><td>P '.$rentals->convertNumber($amountextra).'</td><td><ul>'.$allremarks.'</ul></td></tr>';
                                endforeach;
                            endforeach;

                            foreach($allunits as $unit):
                                echo '<tr><td>'.$unit['plate_number'].'</td><td><span class="warning">No Driver</span></td><td><span class="warning">No Record</span></td><td><span class="warning">No Record</span></td><td><span class="warning">No Record</span></td><td><span class="warning">Unit Standby</span></td></tr>';
                            endforeach;
							
							?>
                        </tbody>
                    </table>
                    <div class="summary">
                        <table>
                            <tbody>
                                <tr><td>Amount Total</td><td>P <?php echo $rentals->convertNumber($amounttot); ?></td></tr>
                                <tr><td>Water Total</td><td>P <?php echo $rentals->convertNumber($watertot); ?></td></tr>
                                <tr><td>Extras Total</td><td>P <?php echo $rentals->convertNumber($extrastot); ?></td></tr>
                                <tr><td><b class="highlight">Grand Total</b></td><td><b class="highlight">P <?php echo $rentals->convertNumber($amounttot + $watertot + $extrastot); ?></b></td></tr>
                            </tbody>
                        </table>
                    </div>
				</div>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();