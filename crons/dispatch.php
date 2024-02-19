#!/usr/bin/php -q
<?php
$myfile = fopen("/var/www/html/tollintegration.chintiandparker.com/crons/DispatchTest.txt", "a") or die("Unable to open file!");
$txt = "Cron is running for dispatching order - ".$argv[1]." ".date("h:i:sa")."\n";
fwrite($myfile, $txt);
fclose($myfile);

/*
This will change the fulfillment status of orders.
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
$sftp = new \Toll_Integration\RemoteSFTP();
$sftp->getDespatchedFile();
