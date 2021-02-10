<?php
/**
 * Template Name: Daily Gasoline Sales
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

$gasoline = new Gasolines();

get_header();

if ( have_posts() ) : ?>
	<?php while ( have_posts() ) { the_post(); ?>
		<div class="page-single">
			<main class="page-single__content" role="main">
				<div class="form-container gasoline-sales-filter search-date-filter">
					<?php
						$date = (isset($_GET['start']))?$_GET['start']:date('F d, Y');
					?>
                    <h1>Gasoline Sales (<?php echo (isset($_GET['start']))?$_GET['start']:date('F d, Y');?>) <div class="date-buttons"><a href="<?php echo get_permalink(get_the_ID()); ?>?start=<?php echo date('F d, Y',strtotime($date . "-1 day"));?>&end=<?php echo date('F d, Y',strtotime($date . "-1 day"));?>" class="date-prev"><i class="fas fa-chevron-left"></i></a><a href="<?php echo get_permalink(get_the_ID()); ?>?start=<?php echo date('F d, Y',strtotime($date . "+1 day"));?>&end=<?php echo date('F d, Y',strtotime($date . "+1 day"));?>" class="date-next"><i class="fas fa-chevron-right"></i></a></div></h1>
					<div class="date-filter"><form action="./" method="GET"><input type="text" name="start" placeholder="Select Date" class="date-text"> <input type="hidden" name="end" value=""><input type="submit" class="btn button" value="Filter Date"></form></div>
                    <div class="odo-container"><?php $gasoline->getOdoFormToShow(); ?></div>
                    <div class="sales-container">
						<h1>Add Gasoline Daily Sales</h1>
						<?php $gasoline->getGasolineFormToShow(); ?>
					</div>
				</div>
				<div class="list-container">
					<h1>Daily Gasoline Sales List (<?php echo (isset($_GET['start']))?$_GET['start']:date('F d, Y');?>)</h1>
					<div class="search-filter"><input type="text" placeholder="Type Search" class="search-text"></div>
					<table class="search-table">
						<thead>
							<tr>
								<th>Plate Number</th>
								<th>Extras</th>
                                <th>Number of Liters</th>
                                <th>Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
							list($list, $pagi) = (isset($_GET['start']) && isset($_GET['end']))?$gasoline->getGasolinesListByDateRange($_GET['start'], $_GET['end']):$gasoline->getGasolinesListByDate(date("m"), date("d"), date("Y"));
							
                            $literstot = 0;
							$amountot = 0;
							$accum = array();

							foreach($list as $id => $details):
								unset($details['_validate_email']); //remove unwanted 
								foreach($details as $gas):
									if($gas):
										foreach($gas as $gasdeets):
											$extras = (is_array($gasdeets['extras']))?implode(',', $gasdeets['extras']):'';

											$accum[$gasdeets['plate_number']][] = array($extras, $gasdeets['number_of_liters'],ceil($gasdeets['amount']));
											
											$amountot += ceil((float)$gasdeets['amount']);
											$literstot += (float)$gasdeets['number_of_liters'];
										endforeach;
									endif;
								endforeach;
							endforeach;

							foreach($accum as $plate => $deets):
								$extras = '';
								$liters = 0;
								$amount = 0;

								foreach($deets as $entries):
									$extras .= (strlen($entries[0]) > 0)?'<li>'.$entries[0].'</li>':'';
									$liters += (float) $entries[1];
									$amount += (float) $entries[2];
								endforeach;

								echo '<tr><td>'.get_field('plate_number', $plate).'</td><td><ul>'.$extras.'</ul></td><td>'.$gasoline->convertNumber($liters).'</td><td>P '.$gasoline->convertNumber($amount).'</td></tr>';
							endforeach;
							
							list($odo, $pagi) = (isset($_GET['start']) && isset($_GET['end']))?$gasoline->getOdoListByDateRange($_GET['start'], $_GET['end']):$gasoline->getOdoListByDate(date("m"), date("d"), date("Y"));

							$begin = 0;
							$end = 0;
							$price = 0;
							foreach($odo as $o):
								unset($o['_validate_email']);
								$begin = (float)$o['odo_meter_beginning'];
								$end = (float)$o['odo_meter_ending'];
								$price = (float)$o['price_per_liter'];

								break;
							endforeach;

							?>
                        </tbody>
                    </table>
                    <div class="summary">
                        <table>
                            <tbody>
								<tr><td>ODO Meter Beginning</td><td><?php echo $gasoline->convertNumber($begin); ?> L</td></tr>
								<tr><td>ODO Meter Ending</td><td><?php echo $gasoline->convertNumber($end); ?> L</td></tr>
                                <tr><td>Current ODO Meter</td><td><?php echo $gasoline->convertNumber($literstot + $begin); ?> L</td></tr>
                                <tr><td>Liters Total</td><td><?php echo $gasoline->convertNumber($literstot); ?> L</td></tr>
								<tr><td>Price Per Liter</td><td>P <?php echo $gasoline->convertNumber($price); ?></td></tr>
                                <tr><td><b class="highlight">Grand Total</b></td><td><b class="highlight">P <?php echo $gasoline->convertNumber($amountot); ?></b></td></tr>
                            </tbody>
                        </table>
                    </div>
					<div class="printing__button"><a href="#" class="button btn printing__init" data-mode="1" data-title="Gasoline Sales" data-date="<?php echo (isset($_GET['start']))?$_GET['start']:date('F d, Y'); ?>">Print Gasoline Sales</a></div>
				</div>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();	