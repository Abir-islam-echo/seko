<?php
$myfile = fopen("/var/www/html/tollintegration.chintiandparker.com/crons/CreateTest.txt", "a") or die("Unable to open file!");
$txt = "Cron is running for create order - ".$argv[1]." ".date("h:i:sa")."\n";
fwrite($myfile, $txt);
fclose($myfile);

/*
This will create xml file to certain number of orders.
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
$xml = new \Toll_Integration\XML();
$db = new \Toll_Integration\DB();

foreach ($db->getOrders() as $order) {
    
    $xml->generateOrdersXML($order['order_number']);
    $order['status'] = 1;
    $order['action'] = 'update';
    $db->processOrderNumber($order);
}
