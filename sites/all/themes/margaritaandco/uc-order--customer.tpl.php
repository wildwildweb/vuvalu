<?php

// $Id: uc_order-customer.tpl.php,v 1.1.2.1 2010/07/16 15:45:09 islandusurper Exp $

/** * @file * This file is the default customer invoice template for Ubercart. */ ?>
<div class="htmlmail-body" style="background-color:#FFFFFF;height:100%;">
<center>
<table width="99%" height="99%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="100%" style="background-color:#ffffff;" bgcolor="#ffffff" style="font-family:'lucida grande',tahoma,verdana,arial,sans-serif">

	<center>
	  <table width="620" align="center" border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="20" style="-webkit-border-radius:8px;border-radius:8px;-moz-border-radius:8px;">
	        <tr>
	 		  <td valign="top" bgcolor="#ffffff">
				<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF" style="font-family: verdana, arial, helvetica; font-size: small;">
				  <tr>
				   <td>
				   <?php $timestamp = substr(strtotime(substr($order_created,6,4).'-'.substr($order_created,3,2).'-'.substr($order_created,0,2).' '.substr($order_created,13,2).':'.substr($order_created,16,2)),3,5); ?>
				  </td>
				  </tr>
				  <tr>
					<td>
					  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF" style="font-family: verdana, arial, helvetica; font-size: small;">
						<tr valign="top">
						  <td>
							<?php if ($thank_you_message) { ?>
							<p><b><?php echo t('Thanks for your order, !order_first_name!', array('!order_first_name' => $order_first_name)); ?></b>
							</p>
							<?php if (isset($order->data['new_user'])) { ?>
							<p><b><?php echo t('An account has been created for you with the following details:'); ?></b>
							</p>
							<p><b><?php echo t('Username:'); ?></b><?php echo $order_new_username; ?><br /><b><?php echo t('Password:'); ?></b><?php echo $order_new_password; ?>
							</p>
							<?php } ?>

							<?php } ?>
							<table cellpadding="4" cellspacing="0" border="0" width="100%" style="font-family: verdana, arial, helvetica; font-size: small;">
							  <tr>
								<td colspan="2">
								  <font size="3" face="Arial, Helvetica, sans-serif"><b><?php echo t('Purchasing Information:'); ?></b></font>
								</td>
							  </tr>
							  <tr>
								<td nowrap="nowrap">
								  <b><?php echo t('E-mail Address:'); ?></b>
								  <?php echo ' '.$order_email;?>
								</td>
							  </tr>
							  <tr>
								<td colspan="2">
								  <table width="100%" cellspacing="0" cellpadding="0" style="font-family: verdana, arial, helvetica; font-size: small;">
									<tr>
									  <td valign="top" width="50%">
										<b><?php echo t('Billing Address:'); ?></b>
										<br /><?php echo $order_billing_address; ?><br /><br />
										<b><?php echo t('Billing Phone:'); ?></b>
										<?php echo ' '.$order_billing_phone; ?><br />
									  </td>
									  <?php if ($shippable) { ?>
									  <td valign="top" width="50%">
										<b><?php echo t('Shipping Address:'); ?></b>
										<br /><?php echo $order_shipping_address; ?><br /><br />
										<b><?php echo t('Shipping Phone:'); ?></b>
										<br /><?php echo ' '.$order_shipping_phone; ?><br />
									  </td><?php } ?>
									</tr>
								  </table>
								</td>
							  </tr>
							  <tr>
								<td nowrap="nowrap">
								  <b><?php echo t('Payment Method:'); ?></b>
								  <?php echo ' '.$order_payment_method; ?><br /><br />
								</td>
							  </tr>
							  <?php if($order->payment_method=='bank_transfer') { ?>
							  <tr>
								<td nowrap="nowrap" vAlign="top">
								  <b><?php echo t('To:'); ?></b><br /><br />
								  <?php echo $order_payment_bank_details; ?><br />
								</td>
								<td>

								</td>
							  </tr>
							  <?php } ?>
							  <?php if ($shippable) { ?>
							  <tr>
								<td colspan="2" bgcolor="#EEEEEE">
								  <font color="#CC6600"><b><?php echo t('Shipping Details:'); ?></b></font>
								</td>
							  </tr>
							  <?php } ?>
							  <tr>
								<td colspan="2">
								  <table border="0" cellpadding="1" cellspacing="0" width="100%" style="font-family: verdana, arial, helvetica; font-size: small;">
									<tr>
									  <td nowrap="nowrap">
										<b><?php echo t('Order Date: '); ?></b><?php echo $order_created; ?><br><br>
									  </td>
									</tr>
									<?php if ($shipping_method && $shippable) { ?>
									<tr>
									  <td nowrap="nowrap">
										<b><?php echo t('Shipping Method:'); ?></b>
									  </td>
									  <td width="98%">
										<?php echo $order_shipping_method; ?>
									  </td>
									</tr>
									<?php } ?>
									<tr>
									  <td colspan="2">
										<table width="100%" style="font-family: verdana, arial, helvetica; font-size: small;">
										  <tr>
											<td valign="top" width="79%">
											  <b><?php echo t('Product'); ?></b>
											</td>
											<td width="25%" align="center">
											  <b><?php echo t('Quantity'); ?></b>
											</td>
											<td width="25%">
											  <b><?php echo t('Price'); ?></b>
											</td>
											<td width="25%" align="right">
											  <b><?php echo t('Total'); ?></b>
											</td>
										  </tr>
										<?php
											$alquiler = FALSE;
											$products = array();
											$iva = uc_taxes_rate_load(1);
											$iva_rate = 1 + $iva->rate;

											// Este pedido no tiene reserva por lo que se trata de una compra.
											  foreach ($order->products as $product) {
												$products[$product->nid] = array(
												  'title' => l($product->title, 'node/' . $product->nid),
												  'attributes' => $product->data['attributes'],
												  'qty' => $product->qty,
												  'price' => $product->price,
												  'total' => $product->price * $product->qty,
												  'precio_con_iva' => $product->price * $iva_rate,
												  'total_con_iva' => $product->price * $product->qty * $iva_rate,
												);
											  }
											foreach ($products as $line) { ?>
										  <tr>
											<td valign="top" width="79%">
												<?php if ($alquiler) {
														echo $line['units'] . ' x ' . $line['title'] . '<br /><br />';
													    echo t('<b>From: </b>') . $line['from'] . '<br />';
														echo t('<b>To: </b>') . $line['to'] . '<br />';
													  }
													  else {
														echo $line['title'] . '<br />';
														foreach ($line['attributes'] as $key => $value) {
														  echo t('@key:', array('@key' => $key)) . ' ' . current($value) . '<br />';
														}
													  } ?>
											</td>
											<td width="25%" align="center">
											  <?php echo $line['qty']; ?><br />
											</td>
											<td width="25%" nowrap="nowrap" align="right">
											  <?php echo theme('uc_price', $variables = array('price' => $line['precio_con_iva'])); ?><br />
											</td>
											<td width="25%" nowrap="nowrap" align="right">
											  <?php echo theme('uc_price', $variables = array('price' => $line['total_con_iva'])); ?><br />
											</td>
										  </tr>
									   <?php } ?>
										  <?php foreach ($line_items as $item) {
												  if ($item['type'] == 'subtotal') {
												    $subtotal_sin_iva = $item['amount'];
													continue;
												  }
												  if ($item['type'] == 'tax') {
												    $iva = $item['amount'];
													continue;
												  }
												  if ($item['type'] == 'shipping') {
												    $envio = array();
												    $envio['titulo'] = $item['title'];
													$envio['precio'] = $item['formatted_amount'];
													continue;
												  }
												  if ($item['type'] == 'total' || $item['type'] == 'tax_subtotal') continue;
										  ?>
										  <tr>
											<td nowrap="nowrap">

											</td>
											<td width="98%" align="right">
											  <b><?php echo $item['title']; ?>:</b>
											</td>
											<td nowrap="nowrap">

											</td>
											<td width="98%" align="right">
											  <?php echo $item['formatted_amount']; ?>
											</td>
										  </tr>
										<?php } ?>
										  <tr>
											<td nowrap="nowrap">

											</td>
											<td width="98%" align="right">
											  <br /><b><?php echo t('Subtotal:'); ?></b>
											</td>
											<td nowrap="nowrap">

											</td>
											<td width="98%" nowrap="nowrap" align="right">
											  <br /><b><?php echo theme('uc_price', $variables = array('price' => $subtotal_sin_iva + $iva)); ?></b>
											</td>
										  </tr>
										  <?php if (count($envio) > 0) { ?>
										  <tr>
											<td nowrap="nowrap">

											</td>
											<td width="98%" align="right">
											  <b><?php echo $envio['titulo']; ?>:</b>
											</td>
											<td nowrap="nowrap">

											</td>
											<td width="98%" align="right">
											  <?php echo $envio['precio']; ?>
											</td>
										  </tr>
										  <?php } ?>
										  <tr>
											<td nowrap="nowrap">

											</td>
											<td width="98%" align="right">
											  <br /><b><?php echo t('Order Grand Total:'); ?></b>
											</td>
											<td nowrap="nowrap">

											</td>
											<td width="98%" nowrap="nowrap" align="right">
											  <br /><b><?php echo $order_total; ?></b>
											</td>
										  </tr>
										</table>

										  <?php if ($alquiler) { ?>
										  <tr height="20px">&nbsp;
										  </tr>
										  <tr>
											<td style="background-color:#e2e1e1; font-size:18px; padding: 0px 0px 25px 15px;" nowrap="nowrap">
											  <br /><b><?php print t('Order #:'); ?>&nbsp;<?php print $order->order_id; ?></b>
											  <span style="font-size:16px"><?php print t('Delivery time') . ': ' . $order->agreservation_transport_schedule['transport_schedule_delivery']['value']; ?></span><br />
											  <span style="font-size:16px"><?php print t('Return time') . ': ' . $order->agreservation_transport_schedule['transport_schedule_return']['value']; ?></span><br />
											</td>
										  </tr>
										<?php } ?>
									  </td>
									</tr>
									<?php if ($help_text || $email_text || $store_footer) { ?>
									<tr>
									  <td colspan="2">
										<hr noshade="noshade" size="1" /><br />
										<?php if ($help_text) { ?>
										<p><b><?php echo t('Where can I get help with reviewing my order?'); ?></b><br />
										  <?php echo t('To learn more about managing your orders on !store_link, please visit our <a href="!store_help_url">help page</a>.', array('!store_link' => $store_link, '!store_help_url' => $store_help_url)); ?><br />
										</p>
										<?php } ?>
										<?php if ($email_text) { ?>
										
										<?php } ?>
										<?php if ($store_footer) { ?>
										<p><b><?php echo $store_link; ?></b><br />
										  <?php echo $store_address; ?>
										  <?php echo $store_phone; ?>
										</p><?php } ?>
									  </td>
									</tr>
									<?php } ?>
								  </table>
								</td>
							  </tr>
							</table>
						  </td>
						</tr>
					  </table>
					</td>
				  </tr>
				</table>
			  </td>
			</tr>
	  </table>
	</center>
</td>
</tr>
</tbody>
</table>
</center>
</div>
