<?php
/*
This will create xml file to certain number of orders.
A corn Job will run this file with certain periord of time
*/

$xml = new \Toll_Integration\XML();
$db = new \Toll_Integration\DB();

foreach ($db->getOrders() as $order) {
    $orderPutStatus = $xml->generateOrdersXML($order['order_number']);
    $order['status'] = 1;
    $order['action'] = 'update';
    if ($orderPutStatus) {
        $db->processOrderNumber($order);
    }
}
