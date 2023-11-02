<?php
$myfile = fopen("/var/www/html/tollintegration.chintiandparker.com/crons/CancelTest.txt", "a") or die("Unable to open file!");
$txt = "Cron is running for cancel order - ".$argv[1]." ".date("h:i:sa")."\n";
fwrite($myfile, $txt);
fclose($myfile);

/*
This will send cancelation  email to certain number of orders.
A corn Job will run this file with certain periord of time
*/

$dbName = 'tollapp';
$site = isset($argv[1]) ? $argv[1] : '';
if ($site == 'us'){
    $dbName = 'tollapp_us';
} else if ($site == 'eu'){
    $dbName = 'tollapp_eu';
}
 else if ($site == 'b2bgbp'){
    $dbName = 'tollapp_b2bgbp';
}
 else if ($site == 'b2bu_sd'){
    $dbName = 'tollapp_b2busd';
}
 else if ($site == 'b2be_ur'){
    $dbName = 'tollapp_b2beur';
}
 else if ($site == 'ss'){
    $dbName = 'tollapp_ss';
}

define('DATABASE_NAME', $dbName);
define('CURRENTSITE', $site);

require_once dirname( dirname(__FILE__) ). "/app.php";
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
