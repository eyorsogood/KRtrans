<?php
/**
 * Template Name: Daily Gasoline Sales History
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
					<h1>Daily Gasoline Sales List (<?php echo (isset($_GET['start']) && isset($_GET['end']))?$_GET['start'].' - '.$_GET['end']:date('F d, Y');?>)</h1>
					<div class="search-filter"><input type="text" placeholder="Type Search" class="search-text"></div>
					<table class="search-table">
						<thead>
							<tr>
								<th>Plate Number</th>
								<th>Extras</th>
                                <th>Number of Liters</th>
                                <th>Amount</th>
                                <th>Date</th>
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
                                $date = get_the_date('F d, Y', $id);

								foreach($details as $gas):
									foreach($gas as $gasdeets):
										$extras = (is_array($gasdeets['extras']))?implode(',', $gasdeets['extras']):'';

										$accum[$gasdeets['plate_number']][] = array($extras, $gasdeets['number_of_liters'],ceil($gasdeets['amount']),$date);
										
										$amountot += ceil((float)$gasdeets['amount']);
										$literstot += (float)$gasdeets['number_of_liters'];
									endforeach;
								endforeach;
							endforeach;

							foreach($accum as $plate => $deets):
								$extras = '';
								$liters = 0;
								$amount = 0;
								$date = '';

								foreach($deets as $entries):
									$extras .= (strlen($entries[0]) > 0)?'<li>'.$entries[0].'</li>':'';
									$liters += (float) $entries[1];
									$amount += (float) $entries[2];
									$date .= (strlen($entries[3]) > 0)?'<li>'.$entries[3].'</li>':'';
								endforeach;

								echo '<tr><td>'.get_field('plate_number', $plate).'</td><td><ul>'.$extras.'</ul></td><td>'.$gasoline->convertNumber($liters).'</td><td>P '.$gasoline->convertNumber($amount).'</td><td><ul>'.$date.'</ul></td></tr>';
							endforeach;
							?>
                        </tbody>
                    </table>
                    <div class="summary">
                        <table>
                            <tbody>
                                <tr><td>Liters Total</td><td><?php echo $gasoline->convertNumber($literstot); ?> L</td></tr>
                                <tr><td><b class="highlight">Grand Total</b></td><td><b class="highlight">P <?php echo $gasoline->convertNumber($amountot); ?></b></td></tr>
                            </tbody>
                        </table>
                    </div>
					<div class="printing__button"><a href="#" class="button btn printing__init" data-mode="1" data-title="Gasoline Sales" data-date="<?php echo (isset($_GET['start']) && isset($_GET['end']))?$_GET['start'].' - '.$_GET['end']:date('F d, Y');?>">Print Gasoline Sales</a></div>
				</div>
			</main>
		</div>
	<?php } ?>
<?php else :
	get_template_part( 'templates/content', 'none' );
endif;

get_footer();