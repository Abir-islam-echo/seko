<?php


$xml = new \Toll_Integration\XML();

$log = new \Toll_Integration\Log();
$api = new \Toll_Integration\API();
$db = new \Toll_Integration\DB();
$sftp = new \Toll_Integration\RemoteSFTP();

// print_r($sftp->alldirectoreis());
// echo "<pre>";
//$sam = $api->getOrder('4777522724942');
//print_r($sam);
// print_r($api->getOrder('5255641235759'));
// $fruits = array();
// foreach ($fruits as $fruit) {
//     $xml->generateOrdersXML($fruit);
// }

// $xml->generateOrdersXML('5230647312462'); //swap
// $xml->generateOrdersXML('5655652401237');
// $xml->generateOrdersXML('5172725219406'); //BT
// $xml->generateOrdersXML('5194018947150'); //IM
// $xml->generateOrdersXML('5164077875278'); //JE
// $xml->generateOrdersXML('5166609760334'); //GY
$xml->generateOrdersXML('5284860067972');




// $sftp->getDespatchedFileFullfilment();
// AB    
// echo '<pre>';
// $newData = (array) $api->getOrder('5082674528334');
// print_r($newData['order']['fulfillment_status']);
// AB

// $xml->generateOrdersXML('5307029782699'); //gift note
// $api->test();
// echo APP_DIR;
// echo "<pre>";
// print_r($api->updateOrderPrefix(4417031405739));
// print_r($api->createOrder());

// echo "<pre>";

//$api->test();
// print_r($api->getFulfillment(4912108961963));
//print_r($sftp->fileCheckSftp());







