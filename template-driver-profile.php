<?php
/**
 * Template Name: Driver Profile
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

$driverid = (isset($_GET['driverid']))?$_GET['driverid']:false;

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
                <?php if($driverid): ?>
                <div class="driver-profile">
                    <table>
                        <tbody>
                            <tr>
                                <td>Driver Name<td>
                                <td><?php echo get_field('driver_name', $driverid); ?><td>
                            </tr>
                            <tr>
                                <td>Address<td>
                                <td><?php echo get_field('address', $driverid); ?><td>
                            </tr>
                            <tr>
                                <td>Contact Number<td>
                                <td><?php echo get_field('contact_number', $driverid); ?><td>
                            </tr>
                            <tr>
                                <td>Driver License<td>
                                <td><?php echo get_field('driver_license', $driverid); ?><td>
                            </tr>
                            <tr>
                                <td>Position<td>
                                <td><?php echo get_field('position', $driverid); ?><td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="driver-history">
                    <?php list($list, $pagi) = $drivers->getDriverRentalHistory($driverid); ?>
                    <?php
                        $amounttot = 0;
                        $watertot = 0;
                        $extrastot = 0;
                        $lackamount = 0;

                        echo '<table>';
                        echo '<thead>';
                        echo '	<tr>';
                        echo '		<th>Date</th>';
						echo '		<th>Unit</th>';
						echo '		<th>Amount</th>';
                        echo '        <th>Water</th>';
                        echo '        <th>Extra</th>';
                        echo '        <th>Remarks</th>';
						echo '		<th>Override</th>';
						echo '	</tr>';
                        echo '</thead>';
                        
                        foreach($list as $id => $details):
                            $priceoverride = (isset($details['daily_price_override']) && strlen($details['daily_price_override']) > 0)?(float)$details['daily_price_override']:false;
                            $unitprice = ($priceoverride)?$priceoverride:(float)get_field('unit_rental_price', $details['plate_number']);
                            $amounttext = ($unitprice > ((float)$details['amount']))?$rentals->convertNumber((float)$details['amount']).' <span class="warning">('.$rentals->convertNumber($unitprice - (float)$details['amount']).')</span>':$rentals->convertNumber((float)$details['amount']);
                            
                            $overridetext = (isset($details['daily_price_override']) && strlen($details['daily_price_override']) > 0)?'P '.$rentals->convertNumber((float)$details['daily_price_override']):'None';

                            echo '<tr><td>'.get_the_date('F d, Y', $id).'</td><td>'.get_field('plate_number', $details['plate_number']).'</td><td>P '.$amounttext.'</td><td>P '.$rentals->convertNumber((float)$details['water']).'</td><td>P '.$rentals->convertNumber((float)$details['extra']).'</td><td>'.$details['remarks'].'</td><td>'.$overridetext.'</td></tr>';
                            
                            $amounttot += (float)$details['amount'];
                            $watertot += (float)$details['water'];
                            $extrastot += (float)$details['extra'];
                            $lackamount += ((float)$unitprice > ((float)$details['amount']))?((float)$unitprice - (float)$details['amount']):0;
                        endforeach;
                        echo '</table>';
                    ?>

                    <div class="summary">
                        <table>
                            <tbody>
                                <tr><td>Amount Total</td><td>P <?php echo $rentals->convertNumber($amounttot); ?></td></tr>
                                <tr><td>Water Total</td><td>P <?php echo $rentals->convertNumber($watertot); ?></td></tr>
                                <tr><td>Extras Total</td><td>P <?php echo $rentals->convertNumber($extrastot); ?></td></tr>
                                <tr><td><b class="highlight">Grand Total</b></td><td><b class="highlight">P <?php echo $rentals->convertNumber($amounttot + $watertot + $extrastot); ?></b></td></tr>
                                <tr><td><b class="warning">Lacking Total</b></td><td><b class="warning">P <?php echo $rentals->convertNumber($lackamount); ?></b></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();