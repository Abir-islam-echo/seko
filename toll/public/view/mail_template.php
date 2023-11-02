<?php
$cancelData = [
	"[order_id]" => $orderData['id'],
	"[order_number]" => $orderData['name'],
	"[customer_name]" => $orderData['shipping_address']['name'],
	"[customer_address]" => $orderData['shipping_address']['address1'],
	"[customer_phone]" => $orderData['shipping_address']['phone'],
	"[customer_email]" => $orderData['contact_email'],
	"[customer_details]" => '',
	"[product_details]" => '',
	"[cancel_details]" => $orderData['cancel_reason'] . ' ' . $orderData['cancelled_at'],

];

$cancelData["[customer_details]"] = $orderData['shipping_address']['name']
	. "</br>" . $orderData['shipping_address']['phone'] . "</br>" . $orderData['contact_email']
	. "</br>" . $orderData['shipping_address']['address1'] . "</br>" . $orderData['shipping_address']['address2']
	. "</br>" . $orderData['shipping_address']['country'] . " " . $orderData['shipping_address']['zip'];

$productTable = "<table style='width: 750px;border-collapse: collapse;margin: 50px auto;'>
<thead>
	<tr>
		<th style=\"background: #02817c;color: white;font-weight: bold;padding: 10px;border: 1px solid #02817c;text-align: left;font-size: 14px;\">Product Name</th>
		<th style=\"background: #02817c;color: white;font-weight: bold;padding: 10px;border: 1px solid #02817c;text-align: left;font-size: 14px;\">Product Description</th>
		<th style=\"background: #02817c;color: white;font-weight: bold;padding: 10px;border: 1px solid #02817c;text-align: left;font-size: 14px;\">Product Price</th>
		<th style=\"background: #02817c;color: white;font-weight: bold;padding: 10px;border: 1px solid #02817c;text-align: left;font-size: 14px;\">Qunatity</th>
	</tr>
</thead>
<tbody>";

foreach ($orderData['line_items'] as $line_key => $line_item) {
	$barcode = '';
	$productArray = $this->api->getProduct($line_item['product_id'])['product'];
	foreach ($productArray['variants'] as $variant) {
		if ($variant['sku'] == $line_item['sku']) {
			$barcode = $variant['barcode'];
		}
	}
	$productTable .= "<tr>";
	$productTable .= "<td style=\"padding: 10px;border: 1px solid #02817c;text-align: left;font-size: 12px;\">" . $line_item['name'] . "</td>";
	$productTable .= "<td style=\"padding: 10px;border: 1px solid #02817c;text-align: left;font-size: 12px;\">" . "SKU: " . $line_item['sku'] . "</br>" . "Barcode: " . $barcode . "</td>";
	$productTable .= "<td style=\"padding: 10px;border: 1px solid #02817c;text-align: left;font-size: 12px;\">" . $line_item['price'] . " " . $orderData['currency'] . "</td>";
	$productTable .= "<td style=\"padding: 10px;border: 1px solid #02817c;text-align: left;font-size: 12px;\">" . $line_item['quantity'] . "</td>";
	$productTable .= "</tr>";
}
$productTable .= "</tbody>
</table>";
$cancelData["[product_details]"] = $productTable;

foreach ($cancelData as $key => $value) {

	$body = str_replace($key, $value, $body);
}

?>

<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
		<tbody>
			<tr>
				<td align="center" valign="center" style="text-align:center; padding: 40px">
					<a href="https://www.chintiandparker.com/" rel="noopener" target="_blank">
						<img alt="Logo" src="<?php echo APP_URL; ?>public/assets/media/chinti.png" />
						<!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 388.5 29.7" width="288px" height="24px" class="u-fill-current">
							<path d="M20.7 23.8v4.7c-.8.5-1.7.8-2.7.9-1 .2-2 .2-3 .2-4.2 0-7.7-1.4-10.6-4.2-3-2.8-4.4-6.3-4.4-10.6C0 10.6 1.5 7 4.4 4.2S10.8 0 15 0c1 0 2 .1 3 .2 1 .2 1.8.4 2.6.8v4.8c-.8-.4-1.7-.7-2.6-.9-.9-.2-1.9-.3-3-.3-2.9 0-5.2 1-7.2 2.9S5 11.9 5 14.8s1 5.4 2.9 7.3 4.3 2.9 7.2 2.9c1.1 0 2.1-.1 3-.3.9-.2 1.7-.5 2.6-.9zM49.9 29.1v-13H34.4V29h-4.9V.4h4.9v11.2h15.5V.4h4.9v28.7h-4.9zM65.7 29.1V.4h4.9v28.7h-4.9zM107 .4v28.8h-.8L86.3 10.6v18.5h-4.9V.2h.7L102 18.6V.4h5zM122.2 29.1V4.9h-7V.4h18.9V5h-7v24.1h-4.9zM142.5 29.1V.4h4.9v28.7h-4.9zM190.6 29.1l-2.7-2.9c-1.4 1.2-2.9 2.1-4.5 2.7-1.6.6-3.1.9-4.7.9s-3-.3-4.2-.8c-1.2-.5-2.2-1.2-2.9-2-.6-.6-1-1.3-1.3-2.1-.3-.8-.4-1.5-.4-2.3 0-1.2.3-2.4.9-3.7.6-1.2 1.8-2.4 3.6-3.5l2.3-1.4-.3-.3c-1.1-1.2-1.8-2.3-2.3-3.3s-.7-2.1-.7-3.1c0-.9.2-1.8.5-2.6.4-.8.9-1.5 1.5-2.2.7-.7 1.6-1.3 2.6-1.7 1-.4 2.1-.6 3.2-.6s2.1.2 3 .5c.9.4 1.6.8 2.2 1.4.7.6 1.2 1.4 1.5 2.2.3.8.5 1.7.5 2.5 0 1.6-.5 3-1.4 4.3-.9 1.3-2.2 2.5-3.8 3.5l4.6 5.2c.5-.7.9-1.5 1.4-2.4.4-.9.8-1.8 1.1-2.7h4.4c-.5 1.7-1.1 3.2-1.8 4.6-.7 1.4-1.4 2.6-2.2 3.7l5.5 6.2h-5.6zm-15.2-4.8c.4.4.8.7 1.4.9.6.2 1.3.4 2 .4 1.1 0 2.2-.2 3.2-.7 1.1-.4 2.1-1.1 3-1.9l-5.6-6.2-1.8 1.1c-1.1.7-2 1.4-2.4 2-.5.7-.7 1.4-.7 2.1 0 .4.1.8.2 1.2.3.4.5.8.7 1.1zm4.5-13.7l.8.9c1-.7 1.9-1.3 2.5-2 .6-.7.9-1.5.9-2.4 0-.4-.1-.7-.2-1-.1-.3-.3-.6-.6-.9-.2-.2-.5-.4-.9-.6-.3-.1-.7-.2-1.1-.2-.4 0-.8.1-1.2.2-.4.2-.7.4-1 .6-.3.3-.5.6-.6.9-.2.4-.2.8-.2 1.2 0 .5.1 1.1.4 1.6.2.5.7 1.1 1.2 1.7zM226.6 21.8c-.9 0-1.6 0-2.3-.1-.6-.1-1.2-.1-1.8-.2v7.6h-4.9V.4h9.9c3.8 0 6.8 1 8.9 2.9 2.2 2 3.2 4.4 3.2 7.4 0 3.4-1.2 6.1-3.5 8.1s-5.4 3-9.5 3zm.5-17h-4.6v12.3c.5.1 1.1.2 1.8.2s1.4.1 2.2.1c2.9 0 5-.6 6.2-1.8 1.2-1.2 1.8-2.8 1.8-4.8 0-1.6-.6-3-1.7-4.2-1.1-1.2-3-1.8-5.7-1.8zM263.5 29.1l-2.4-5.6h-11.7l-2.4 5.6h-5.5L254.9 0h.7L269 29.1h-5.5zm-12.3-9.9h8.1l-4.1-9.6-4 9.6zM285.1 20.2c-.9 0-1.7 0-2.4-.1s-1.3-.2-1.9-.3V29h-4.9V.4h10.8c3.8 0 6.7.8 8.7 2.5 2 1.7 3 4 3 7 0 2.2-.5 4-1.5 5.5s-2.4 2.7-4.2 3.5l6.3 10.2h-5.5l-5.5-9c-.5.1-.9.1-1.4.1h-1.5zm1.2-15.4h-5.4v10.7c.6.1 1.2.2 1.9.2.7.1 1.5.1 2.4.1 2.9 0 4.9-.5 6.2-1.5 1.3-1 1.9-2.5 1.9-4.4 0-1.5-.5-2.8-1.5-3.7-1.1-.9-2.9-1.4-5.5-1.4zM324.4 29.1l-12.7-14.6v14.6h-4.9V.4h4.9v12.7L323.9.4h6.3l-12.9 13.2 13.5 15.5h-6.4zM356.2 24.5v4.6h-18.7V.4h18.3V5h-13.6v6.7h12.9v4.5h-12.9v8.4h14zM374.6 20.2c-.9 0-1.7 0-2.4-.1s-1.3-.2-1.9-.3V29h-4.9V.4h10.8c3.8 0 6.7.8 8.7 2.5 2 1.7 3 4 3 7 0 2.2-.5 4-1.5 5.5s-2.4 2.7-4.2 3.5l6.3 10.2H383l-5.5-9c-.5.1-.9.1-1.4.1h-1.5zm1.2-15.4h-5.4v10.7c.6.1 1.2.2 1.9.2.7.1 1.5.1 2.4.1 2.9 0 4.9-.5 6.2-1.5 1.3-1 1.9-2.5 1.9-4.4 0-1.5-.5-2.8-1.5-3.7-1.1-.9-2.9-1.4-5.5-1.4z"></path>
						</svg> -->
					</a>
				</td>
			</tr>
			<tr>
				<td align="left" valign="center">
					<div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
						<?php
						echo $body;

						?>
						<!--end:Email content-->
			<tr>
				<td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
					<p>2nd Floor, 10-11 Greenland Place, London NW1 0AP</p>
					<p>Copyright ©
						<a href="https://www.chintiandparker.com/" rel="noopener" target="_blank">Chinti & Parker</a>.
					</p>
				</td>
			</tr></br>
</div>
</td>
</tr>
</tbody>
</table>
</div>