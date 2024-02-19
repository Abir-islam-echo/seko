<?php
/*
This will send cancelation  email to certain number of orders.
A corn Job will run this file with certain periord of time
*/

$db = new \Toll_Integration\DB();
$api = new \Toll_Integration\API();
$mail = new \Toll_Integration\Mail();
foreach ($db->getCancelOrders() as $order) {
    $cancelSubject = $db->getConfig('cancel_subject', 'cancel');
    $cancelBody = $db->getConfig('cancel_template', 'cancel');
    $orderData = $api->getOrder($order['order_number'])['order'];
    $mail->sendMail($cancelSubject, $cancelBody, $orderData);
    $order['status'] = 1;
    $order['action'] = 'update';
    $db->processCancel($order);
}
